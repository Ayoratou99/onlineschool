<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\Matiere;

class MatiereService extends BaseService
{
    public function __construct(Matiere $model)
    {
        parent::__construct($model);
    }
}
