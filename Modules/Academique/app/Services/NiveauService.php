<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\Niveau;

class NiveauService extends BaseService
{
    public function __construct(Niveau $model)
    {
        parent::__construct($model);
    }
}
