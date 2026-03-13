<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\Filiere;

class FiliereService extends BaseService
{
    public function __construct(Filiere $model)
    {
        parent::__construct($model);
    }
}
