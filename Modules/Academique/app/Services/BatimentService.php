<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\Batiment;

class BatimentService extends BaseService
{
    public function __construct(Batiment $model)
    {
        parent::__construct($model);
    }
}
