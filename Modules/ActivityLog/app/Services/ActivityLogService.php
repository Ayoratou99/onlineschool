<?php

namespace Modules\ActivityLog\Services;

use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\ActivityLog\Models\ActivityLog;

class ActivityLogService extends BaseService
{
    public function __construct(ActivityLog $model)
    {
        parent::__construct($model);
    }

    public function paginateWithFilters(
        int $perPage,
        int $page,
        array $filters,
        ?string $populate = null,
        ?array $sort = null,
        ?array $search = null
    ): LengthAwarePaginator {
        $query = $this->model->query();
        if (!empty($filters['user_id'])) {
            $query->forUser($filters['user_id']);
        }
        if (!empty($filters['entity'])) {
            $query->forEntity($filters['entity']);
        }
        if (!empty($filters['action'])) {
            $query->forAction($filters['action']);
        }
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->betweenDates($filters['start_date'], $filters['end_date']);
        } elseif (!empty($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        } elseif (!empty($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }
        [$with, $resolverRelations] = $this->splitPopulateForResolver($populate);
        if ($with) {
            foreach ($with as $relation) {
                $query->with($relation);
            }
        }
        if ($sort) {
            $query->orderBy($sort['field'], $sort['direction'] === 'asc' ? 'asc' : 'desc');
        }
        if ($search) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }
        $data = $query->paginate($perPage, ['*'], 'page', $page);
        $this->hydrateResolverRelations($data->getCollection(), $resolverRelations);
        return $data;
    }
}
