<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\SalleIndisponibilite;

class SalleIndisponibiliteService extends BaseService
{
    public function __construct(SalleIndisponibilite $model)
    {
        parent::__construct($model);
    }
}
