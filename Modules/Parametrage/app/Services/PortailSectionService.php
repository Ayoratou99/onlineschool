<?php

declare(strict_types=1);

namespace Modules\Parametrage\Services;

use App\Services\BaseService;
use Modules\Parametrage\Models\PortailSection;

class PortailSectionService extends BaseService
{
    public function __construct(PortailSection $model)
    {
        parent::__construct($model);
    }

    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $ordre => $id) {
            PortailSection::where('id', $id)->update(['ordre' => $ordre]);
        }
    }
}
