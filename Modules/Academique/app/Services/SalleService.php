<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\Salle;

class SalleService extends BaseService
{
    public function __construct(Salle $model)
    {
        parent::__construct($model);
    }
}
