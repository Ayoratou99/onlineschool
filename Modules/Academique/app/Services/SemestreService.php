<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\Semestre;

class SemestreService extends BaseService
{
    public function __construct(Semestre $model)
    {
        parent::__construct($model);
    }
}
