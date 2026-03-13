<?php

namespace Modules\Statistique\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Statistique\Services\QueryBuilder\PeriodGroupHandler;
use Modules\Statistique\Services\QueryBuilder\RelationHandler;

class StatistiqueService
{
    public function __construct(
        private RelationHandler $relationHandler,
        private PeriodGroupHandler $periodGroupHandler,
        private StatistiqueCacheManager $cacheManager,
        private EntityRegistry $entityRegistry,
    ) {}

    /**
     * Point d'entrée principal : valide, cache, construit et exécute la requête.
     *
     * @param  array $params  Données validées par StatistiqueQueryRequest
     * @return array          ['result' => [...], 'meta' => [...]]
     */
    public function execute(array $params): array
    {
        // 1. Résoudre l'entité
        $modelClass = $this->resolveEntity($params['entity']);
        /** @var Model $model */
        $model = new $modelClass;

        // 2. Valider les champs
        $this->validateAllFields($model, $params);

        // 3. Vérifier le cache
        $noCache  = $params['no_cache'] ?? false;
        $cacheTtl = $params['cache_ttl'] ?? null;

        if (!$noCache) {
            $cached = $this->cacheManager->get($params);
            if ($cached !== null) {
                return [
                    'result'     => $cached,
                    'from_cache' => true,
                    'meta'       => $this->buildMeta($params),
                ];
            }
        }

        // 4. Construire et exécuter la requête
        $result = $this->buildAndExecute($model, $params);

        // 5. Stocker en cache
        if (!$noCache) {
            $this->cacheManager->put($params, $result, $cacheTtl);
        }

        return [
            'result'     => $result,
            'from_cache' => false,
            'meta'       => $this->buildMeta($params),
        ];
    }

    // ====================================================================
    // Validation complète
    // ====================================================================

    private function validateAllFields(Model $model, array $params): void
    {
        $operation    = $params['operation'];
        $targetColumn = $params['target_column'];
        $periodColumn = $params['period_column'] ?? 'created_at';

        // target_column (sauf COUNT(*) qui accepte "*")
        if ($targetColumn !== '*' && !in_array($operation, ['count'])) {
            $this->assertColumnExists($model, $targetColumn, 'target_column');
        }
        if ($operation === 'count_distinct') {
            $this->assertColumnExists($model, $targetColumn, 'target_column');
        }

        // period_column si période utilisée
        if (($params['with_period'] ?? false) || !empty($params['period_group_by'])) {
            $this->assertColumnExists($model, $periodColumn, 'period_column');
        }

        // filters
        if (!empty($params['filters'])) {
            $this->validateFilters($model, $params['filters']);
        }

        // group_by
        if (!empty($params['group_by'])) {
            $this->validateGroupBy($model, $params['group_by']);
        }
    }

    private function assertColumnExists(Model $model, string $column, string $paramName): void
    {
        if ($column === '*') {
            return;
        }

        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) {
            throw new \InvalidArgumentException(
                "Le nom de colonne '$column' (parametre '$paramName') contient des caracteres invalides. " .
                "Seuls les lettres, chiffres et underscores sont acceptes."
            );
        }

        $table = $model->getTable();

        if (!Schema::hasColumn($table, $column)) {
            $available = Schema::getColumnListing($table);
            throw new \InvalidArgumentException(
                "Le champ '$column' (parametre '$paramName') n'existe pas sur la table '$table'. " .
                "Colonnes disponibles : " . implode(', ', $available) . "."
            );
        }
    }

    private function validateFilters(Model $model, array $filters): void
    {
        foreach ($filters as $field => $value) {
            if (str_contains($field, '.')) {
                [$relation, $column] = explode('.', $field, 2);
                $this->relationHandler->assertRelationExists($model, $relation);
                $relatedModel = $model->$relation()->getRelated();
                $this->assertColumnExists($relatedModel, $column, "filters.$field");
            } else {
                $this->assertColumnExists($model, $field, "filters.$field");
            }
        }
    }

    private function validateGroupBy(Model $model, array $groupBy): void
    {
        foreach ($groupBy as $field) {
            if (str_contains($field, '.')) {
                [$relation, $column] = explode('.', $field, 2);
                $this->relationHandler->assertRelationExists($model, $relation);
                $relatedModel = $model->$relation()->getRelated();
                $this->assertColumnExists($relatedModel, $column, "group_by.$field");
            } else {
                $this->assertColumnExists($model, $field, "group_by.$field");
            }
        }
    }

    // ====================================================================
    // Construction de la requête
    // ====================================================================

    private function buildAndExecute(Model $model, array $params): array
    {
        $query = $model->newQuery();

        $operation    = $params['operation'];
        $targetColumn = $params['target_column'];
        $periodColumn = $params['period_column'] ?? 'created_at';
        $table        = $model->getTable();

        // --- Filtres directs et relationnels ---
        if (!empty($params['filters'])) {
            $this->applyFilters($query, $params['filters']);
        }

        // Qualify period column with main table to avoid ambiguity on joins
        $qualifiedPeriodColumn = "$table.$periodColumn";

        // --- Filtre de période ---
        if (($params['with_period'] ?? false) && !empty($params['period'])) {
            $this->applyPeriod($query, $params['period'], $qualifiedPeriodColumn);
        }

        // --- Sélections ---
        $selections = [];

        // Regroupement temporel (always on the main entity's table)
        if (!empty($params['period_group_by'])) {
            $alias = $this->periodGroupHandler->groupByPeriod($query, $qualifiedPeriodColumn, $params['period_group_by']);
            $selections[] = $alias;
        }

        // Regroupement par champs (direct ou relation)
        if (!empty($params['group_by'])) {
            foreach ($params['group_by'] as $field) {
                if (str_contains($field, '.')) {
                    $alias = $this->relationHandler->handleRelationGroupBy($query, $field);
                    $selections[] = $alias;
                } else {
                    $query->groupBy("$table.$field");
                    $query->addSelect("$table.$field");
                    $selections[] = $field;
                }
            }
        }

        // --- Agrégation ---
        $aggExpr = $this->buildAggregation($operation, $targetColumn, $table);
        $query->addSelect(DB::raw("$aggExpr as result"));

        return $query->get()->toArray();
    }

    // ====================================================================
    // Helpers
    // ====================================================================

    private function applyFilters(Builder $query, array $filters): void
    {
        foreach ($filters as $field => $value) {
            if (str_contains($field, '.')) {
                $this->relationHandler->handleRelationFilter($query, $field, $value);
            } else {
                if (is_array($value)) {
                    $query->whereIn($field, $value);
                } else {
                    $query->where($field, $value);
                }
            }
        }
    }

    private function applyPeriod(Builder $query, array $period, string $column): void
    {
        if (!empty($period['start'])) {
            $query->where($column, '>=', $period['start']);
        }
        if (!empty($period['end'])) {
            $query->where($column, '<=', $period['end']);
        }
    }

    private function buildAggregation(string $operation, string $column, string $table): string
    {
        $qualifiedColumn = ($column === '*') ? '*' : "$table.$column";

        return match ($operation) {
            'count'          => 'COUNT(*)',
            'count_distinct' => "COUNT(DISTINCT $qualifiedColumn)",
            'sum'            => "SUM($qualifiedColumn)",
            'avg'            => "AVG($qualifiedColumn)",
            'min'            => "MIN($qualifiedColumn)",
            'max'            => "MAX($qualifiedColumn)",
            default          => throw new \InvalidArgumentException(
                "Opération '$operation' non supportée. " .
                "Valeurs acceptées : " . implode(', ', config('statistique.operations', []))
            ),
        };
    }

    private function resolveEntity(string $entity): string
    {
        return $this->entityRegistry->resolve($entity);
    }

    /**
     * Expose le registre pour que le FormRequest puisse lister les slugs valides.
     */
    public function getEntityRegistry(): EntityRegistry
    {
        return $this->entityRegistry;
    }

    private function buildMeta(array $params): array
    {
        return [
            'entity'          => $params['entity'],
            'operation'        => $params['operation'],
            'target_column'    => $params['target_column'],
            'period_group_by'  => $params['period_group_by'] ?? null,
            'group_by'         => $params['group_by'] ?? null,
            'with_period'      => $params['with_period'] ?? false,
            'executed_at'      => now()->toIso8601String(),
        ];
    }
}
