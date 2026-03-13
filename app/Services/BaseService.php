<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

abstract class BaseService
{
    public function __construct(protected Model $model) {}

    public function getEntityName(): string
    {
        return Str::snake(class_basename($this->model));
    }

    /**
     * Récupère tous les enregistrements du modèle
     *
     * @param string|null $populate Relations à charger (eager loading).
     *                              Exemples: "villes", "villes.quartiers", "province,quartiers"
     *                              Permet d'éviter le problème N+1 en chargeant les relations en une seule requête.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(string $populate = null)
    {
        $query = $this->model->query();
        [$with, $resolverRelations, $nestedResolverPaths] = $this->splitPopulateForResolver($populate);
        if ($with) {
            $query->with($with);
        }
        $data = $query->get();
        $this->hydrateResolverRelations($data, $resolverRelations, $nestedResolverPaths);
        return $data;
    }

    /**
     * Récupère les enregistrements avec pagination
     *
     * @param int $perPage Nombre d'éléments par page
     * @param int $page Numéro de la page
     * @param string|null $populate Relations à charger (eager loading).
     *                              Exemples: "villes", "villes.quartiers", "province,quartiers"
     *                              Permet d'éviter le problème N+1 en chargeant les relations en une seule requête.
     *                              Peut être une relation simple (string) ou multiple (séparées par des virgules).
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage, int $page, string $populate = null, array $sort = null, array $search = null): LengthAwarePaginator
    {
        $query = $this->model->query();
        [$with, $resolverRelations, $nestedResolverPaths] = $this->splitPopulateForResolver($populate);
        if ($with) {
            foreach ($with as $relation) {
                $query->with($relation);
            }
        }
        if ($sort) {
            $query->orderBy($sort['field'], $sort['direction'] == 'asc' ? 'asc' : 'desc');
        }
        if ($search) {
            foreach ($search as $field => $value) {
                $query->where($field, 'ILIKE', '%' . $value . '%');
            }
        }
        $data = $query->paginate($perPage, ['*'], 'page', $page);
        $this->hydrateResolverRelations($data->getCollection(), $resolverRelations, $nestedResolverPaths);
        return $data;
    }

    /**
     * Trouve un enregistrement par son ID
     *
     * @param string $id Identifiant unique de l'enregistrement (UUID)
     * @param string|null $populate Relations à charger (eager loading).
     *                              Exemples: "villes", "villes.quartiers", "province,quartiers"
     *                              Permet d'éviter le problème N+1 en chargeant les relations en une seule requête.
     *                              Peut être une relation simple (string) ou multiple (séparées par des virgules).
     * @return Model|null
     */
    public function find(string $id, string $populate = null)
    {
        $query = $this->model->query();
        [$with, $resolverRelations, $nestedResolverPaths] = $this->splitPopulateForResolver($populate);
        if ($with) {
            foreach ($with as $relation) {
                $query->with($relation);
            }
        }
        $record = $query->find($id);
        if ($record && ($resolverRelations || $nestedResolverPaths)) {
            $this->hydrateResolverRelations(collect([$record]), $resolverRelations, $nestedResolverPaths);
        }
        return $record;
    }

    public function create(array $data) { return $this->model->create($data); }

    public function update(string $id, array $data)
    {
        $record = $this->model->find($id);
        if ($record) {
            $record->update($data);
        }
        return $record;
    }

    public function delete(string $id)
    {
        $record = $this->model->find($id);
        return $record ? $record->delete() : false;
    }


    protected function splitPopulateForResolver(?string $populate): array
    {
        if (!$populate) {
            return [[], [], []];
        }
        $with = [];
        $resolverRelations = [];
        $nestedResolverPaths = [];
        $instance = $this->model->newInstance();
        foreach (array_map('trim', explode(',', $populate)) as $relation) {
            if ($relation === '') {
                continue;
            }
            if (str_contains($relation, '.')) {
                $parts = explode('.', $relation);
                $eloquentPrefix = [];
                $currentInstance = $instance;
                $i = 0;
                foreach ($parts as $segment) {
                    $getter = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $segment))) . 'Attribute';
                    if (method_exists($currentInstance, $getter)) {
                        $eloquentPath = implode('.', $eloquentPrefix);
                        if ($eloquentPath !== '' && !in_array($eloquentPath, $with, true)) {
                            $with[] = $eloquentPath;
                        }
                        $nestedResolverPaths[] = $relation;
                        break;
                    }
                    if (!method_exists($currentInstance, $segment)) {
                        $with[] = $relation;
                        break;
                    }
                    $rel = $currentInstance->{$segment}();
                    if (!method_exists($rel, 'getRelated')) {
                        $with[] = $relation;
                        break;
                    }
                    $eloquentPrefix[] = $segment;
                    $currentInstance = $rel->getRelated()->newInstance();
                    $i++;
                    if ($i === count($parts)) {
                        $with[] = $relation;
                    }
                }
                continue;
            }
            if (method_exists($instance, $relation)) {
                $with[] = $relation;
                continue;
            }
            $getter = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $relation))) . 'Attribute';
            if (method_exists($instance, $getter)) {
                $resolverRelations[] = $relation;
            } else {
                $with[] = $relation;
            }
        }
        return [$with, $resolverRelations, $nestedResolverPaths];
    }

    protected function hydrateResolverRelations($models, array $resolverRelations, array $nestedResolverPaths = []): void
    {
        $collection = $models instanceof \Illuminate\Support\Collection ? $models : collect($models);
        foreach ($collection as $model) {
            foreach ($resolverRelations as $name) {
                $model->setRelation($name, $model->{$name});
            }
        }
        foreach ($nestedResolverPaths as $path) {
            $parts = explode('.', $path);
            if (count($parts) < 2) {
                continue;
            }
            $lastSegment = array_pop($parts);
            $this->hydrateNestedResolverPath($collection, $parts, $lastSegment);
        }
    }


    protected function hydrateNestedResolverPath(\Illuminate\Support\Collection $models, array $pathSegments, string $lastSegment): void
    {
        if (empty($pathSegments)) {
            foreach ($models as $model) {
                if (is_object($model)) {
                    $model->setRelation($lastSegment, $model->{$lastSegment});
                }
            }
            return;
        }
        $segment = $pathSegments[0];
        $remaining = array_slice($pathSegments, 1);
        foreach ($models as $model) {
            $related = $model->getRelation($segment);
            if ($related === null) {
                continue;
            }
            $relatedCollection = $related instanceof \Illuminate\Support\Collection ? $related : collect([$related]);
            $this->hydrateNestedResolverPath($relatedCollection, $remaining, $lastSegment);
        }
    }
}
