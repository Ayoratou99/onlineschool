<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\Programme;

class ProgrammeService extends BaseService
{
    public function __construct(Programme $model)
    {
        parent::__construct($model);
    }
}
