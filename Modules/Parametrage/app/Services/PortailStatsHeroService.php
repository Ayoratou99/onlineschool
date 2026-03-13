<?php

declare(strict_types=1);

namespace Modules\Parametrage\Services;

use App\Services\BaseService;
use Modules\Parametrage\Models\PortailStatsHero;

class PortailStatsHeroService extends BaseService
{
    public function __construct(PortailStatsHero $model)
    {
        parent::__construct($model);
    }

    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $ordre => $id) {
            PortailStatsHero::where('id', $id)->update(['ordre' => $ordre]);
        }
    }
}
