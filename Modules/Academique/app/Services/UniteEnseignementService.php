<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\UniteEnseignement;

class UniteEnseignementService extends BaseService
{
    public function __construct(UniteEnseignement $model)
    {
        parent::__construct($model);
    }
}
