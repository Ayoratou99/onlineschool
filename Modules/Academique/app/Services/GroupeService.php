<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\Groupe;

class GroupeService extends BaseService
{
    public function __construct(Groupe $model)
    {
        parent::__construct($model);
    }
}
