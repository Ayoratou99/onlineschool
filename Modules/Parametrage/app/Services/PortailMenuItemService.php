<?php

declare(strict_types=1);

namespace Modules\Parametrage\Services;

use App\Services\BaseService;
use Modules\Parametrage\Models\PortailMenuItem;

class PortailMenuItemService extends BaseService
{
    public function __construct(PortailMenuItem $model)
    {
        parent::__construct($model);
    }

    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $ordre => $id) {
            PortailMenuItem::where('id', $id)->update(['ordre' => $ordre]);
        }
    }
}
