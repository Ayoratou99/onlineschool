<?php

declare(strict_types=1);

namespace Modules\Parametrage\Services;

use App\Services\BaseService;
use Modules\Parametrage\Models\PortailGalerie;

class PortailGalerieService extends BaseService
{
    public function __construct(PortailGalerie $model)
    {
        parent::__construct($model);
    }

    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $ordre => $id) {
            PortailGalerie::where('id', $id)->update(['ordre' => $ordre]);
        }
    }
}
