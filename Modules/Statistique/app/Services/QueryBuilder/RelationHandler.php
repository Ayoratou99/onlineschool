<?php

namespace Modules\Statistique\Services\QueryBuilder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RelationHandler
{
    /**
     * Applique un groupBy sur un champ de relation (ex: "departement.nom").
     * Effectue un LEFT JOIN automatique basé sur le type de relation Eloquent.
     */
    public function handleRelationGroupBy(Builder $query, string $groupField): string
    {
        [$relation, $column] = explode('.', $groupField, 2);

        $model = $query->getModel();
        $this->assertRelationExists($model, $relation);

        $relationType = $model->$relation();
        $alias = str_replace('.', '_', $groupField);

        if ($relationType instanceof BelongsTo) {
            $this->joinBelongsTo($query, $relationType, $relation, $column, $alias);
        } elseif ($relationType instanceof HasOne) {
            $this->joinHasOne($query, $relationType, $relation, $column, $alias);
        } elseif ($relationType instanceof BelongsToMany) {
            $this->joinBelongsToMany($query, $relationType, $relation, $column, $alias);
        } elseif ($relationType instanceof HasMany) {
            throw new \InvalidArgumentException(
                "Le groupBy sur une relation HasMany ('$relation') n'est pas supporté. " .
                "Utilisez plutôt un filtre (whereHas) ou inversez la relation."
            );
        } else {
            throw new \InvalidArgumentException(
                "Type de relation non supporté pour le groupBy : " . get_class($relationType)
            );
        }

        $query->groupBy($alias);

        return $alias;
    }

    /**
     * Applique un filtre sur un champ de relation (ex: "province.nom" => "Libreville").
     * Utilise whereHas pour filtrer via la relation sans join.
     */
    public function handleRelationFilter(Builder $query, string $field, mixed $value): void
    {
        [$relation, $column] = explode('.', $field, 2);

        $model = $query->getModel();
        $this->assertRelationExists($model, $relation);

        $query->whereHas($relation, function (Builder $q) use ($column, $value) {
            if (is_array($value)) {
                $q->whereIn($column, $value);
            } else {
                $q->where($column, $value);
            }
        });
    }

    /**
     * Vérifie qu'une relation existe sur le modèle et retourne une instance de Relation.
     */
    public function assertRelationExists(Model $model, string $relation): void
    {
        if (!method_exists($model, $relation)) {
            throw new \InvalidArgumentException(
                "La relation '$relation' n'existe pas sur le modèle " . get_class($model) . ". " .
                "Vérifiez que la méthode '$relation()' est définie dans le modèle."
            );
        }

        $instance = $model->$relation();
        if (!($instance instanceof \Illuminate\Database\Eloquent\Relations\Relation)) {
            throw new \InvalidArgumentException(
                "La méthode '$relation()' sur " . get_class($model) . " n'est pas une relation Eloquent valide."
            );
        }
    }

    // ----------------------------------------------------------------
    // Private join helpers
    // ----------------------------------------------------------------

    private function joinBelongsTo(Builder $query, BelongsTo $rel, string $relation, string $column, string $alias): void
    {
        $relatedTable = $rel->getRelated()->getTable();
        $foreignKey   = $rel->getForeignKeyName();
        $ownerKey     = $rel->getOwnerKeyName();
        $baseTable    = $query->getModel()->getTable();

        // Avoid duplicate joins
        if (!$this->hasJoin($query, $relatedTable)) {
            $query->leftJoin(
                $relatedTable,
                "$baseTable.$foreignKey",
                '=',
                "$relatedTable.$ownerKey"
            );
        }

        $query->addSelect("$relatedTable.$column as $alias");
    }

    private function joinHasOne(Builder $query, HasOne $rel, string $relation, string $column, string $alias): void
    {
        $relatedTable = $rel->getRelated()->getTable();
        $foreignKey   = $rel->getForeignKeyName();
        $localKey     = $rel->getLocalKeyName();
        $baseTable    = $query->getModel()->getTable();

        if (!$this->hasJoin($query, $relatedTable)) {
            $query->leftJoin(
                $relatedTable,
                "$baseTable.$localKey",
                '=',
                "$relatedTable.$foreignKey"
            );
        }

        $query->addSelect("$relatedTable.$column as $alias");
    }

    private function joinBelongsToMany(Builder $query, BelongsToMany $rel, string $relation, string $column, string $alias): void
    {
        $relatedTable    = $rel->getRelated()->getTable();
        $pivotTable      = $rel->getTable();
        $foreignPivotKey = $rel->getForeignPivotKeyName();
        $relatedPivotKey = $rel->getRelatedPivotKeyName();
        $baseTable       = $query->getModel()->getTable();
        $relatedKey      = $rel->getRelated()->getKeyName();

        if (!$this->hasJoin($query, $pivotTable)) {
            $query->leftJoin(
                $pivotTable,
                "$baseTable.{$query->getModel()->getKeyName()}",
                '=',
                "$pivotTable.$foreignPivotKey"
            );
        }

        if (!$this->hasJoin($query, $relatedTable)) {
            $query->leftJoin(
                $relatedTable,
                "$pivotTable.$relatedPivotKey",
                '=',
                "$relatedTable.$relatedKey"
            );
        }

        $query->addSelect("$relatedTable.$column as $alias");
    }

    /**
     * Vérifie si un JOIN sur cette table existe déjà dans la requête.
     */
    private function hasJoin(Builder $query, string $table): bool
    {
        $joins = $query->getQuery()->joins ?? [];
        foreach ($joins as $join) {
            if ($join->table === $table) {
                return true;
            }
        }
        return false;
    }
}
