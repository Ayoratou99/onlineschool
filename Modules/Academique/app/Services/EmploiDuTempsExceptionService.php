<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\EmploiDuTempsException;

class EmploiDuTempsExceptionService extends BaseService
{
    public function __construct(EmploiDuTempsException $model)
    {
        parent::__construct($model);
    }
}
