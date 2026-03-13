<?php

declare(strict_types=1);

namespace Modules\Parametrage\Services;

use App\Services\BaseService;
use Modules\Parametrage\Models\PortailActualite;

class PortailActualiteService extends BaseService
{
    public function __construct(PortailActualite $model)
    {
        parent::__construct($model);
    }

    public function toggleEpingle(string $id): ?PortailActualite
    {
        $actualite = $this->model->find($id);
        if (! $actualite) {
            return null;
        }
        $actualite->update(['is_epingle' => ! $actualite->is_epingle]);
        return $actualite->fresh();
    }

    public function updateCiblage(string $id, string $ciblage): ?PortailActualite
    {
        $actualite = $this->model->find($id);
        if (! $actualite) {
            return null;
        }
        $actualite->update(['ciblage' => $ciblage]);
        return $actualite->fresh();
    }
}
