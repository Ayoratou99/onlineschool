<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\Cycle;

class CycleService extends BaseService
{
    public function __construct(Cycle $model)
    {
        parent::__construct($model);
    }
}
