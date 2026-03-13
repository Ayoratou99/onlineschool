<?php

namespace Modules\Parametrage\Services;

use App\Services\BaseService;
use Modules\Parametrage\DTOs\AnneeAcademique\CreateAnneeAcademiqueDTO;
use Modules\Parametrage\DTOs\AnneeAcademique\UpdateAnneeAcademiqueDTO;
use Modules\Parametrage\Models\AnneeAcademique;

class AnneeAcademiqueService extends BaseService
{
    public function __construct(AnneeAcademique $model)
    {
        parent::__construct($model);
    }

    public function createFromDTO(CreateAnneeAcademiqueDTO $dto): AnneeAcademique
    {
        return $this->model->create($dto->toArray());
    }

    public function updateFromDTO(string $id, UpdateAnneeAcademiqueDTO $dto): ?AnneeAcademique
    {
        $record = $this->model->find($id);
        if (! $record) {
            return null;
        }
        $record->update($dto->toArray());
        return $record->fresh();
    }
}
