<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\MatiereEnseignant;

class MatiereEnseignantService extends BaseService
{
    public function __construct(MatiereEnseignant $model)
    {
        parent::__construct($model);
    }
}
