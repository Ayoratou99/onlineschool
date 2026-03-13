<?php

namespace Modules\Parametrage\Services;

use App\Services\BaseService;
use Modules\Parametrage\DTOs\BaremeMention\CreateBaremeMentionDTO;
use Modules\Parametrage\DTOs\BaremeMention\UpdateBaremeMentionDTO;
use Modules\Parametrage\Models\BaremeMention;

class BaremeMentionService extends BaseService
{
    public function __construct(BaremeMention $model)
    {
        parent::__construct($model);
    }

    public function createFromDTO(CreateBaremeMentionDTO $dto): BaremeMention
    {
        return $this->model->create($dto->toArray());
    }

    public function updateFromDTO(string $id, UpdateBaremeMentionDTO $dto): ?BaremeMention
    {
        $record = $this->model->find($id);
        if (! $record) {
            return null;
        }
        $record->update($dto->toArray());
        return $record->fresh();
    }
}
