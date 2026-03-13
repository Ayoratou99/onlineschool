<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\Etage;

class EtageService extends BaseService
{
    public function __construct(Etage $model)
    {
        parent::__construct($model);
    }
}
