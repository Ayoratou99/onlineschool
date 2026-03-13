<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\Etablissement;

class EtablissementService extends BaseService
{
    public function __construct(Etablissement $model)
    {
        parent::__construct($model);
    }
}
