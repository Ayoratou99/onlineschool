<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\EmploiDuTemps;

class EmploiDuTempsService extends BaseService
{
    public function __construct(EmploiDuTemps $model)
    {
        parent::__construct($model);
    }
}
