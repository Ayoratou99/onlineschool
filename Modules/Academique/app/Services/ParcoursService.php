<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\Parcours;

class ParcoursService extends BaseService
{
    public function __construct(Parcours $model)
    {
        parent::__construct($model);
    }
}
