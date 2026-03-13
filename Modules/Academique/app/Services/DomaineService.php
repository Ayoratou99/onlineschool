<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\Domaine;

class DomaineService extends BaseService
{
    public function __construct(Domaine $model)
    {
        parent::__construct($model);
    }
}
