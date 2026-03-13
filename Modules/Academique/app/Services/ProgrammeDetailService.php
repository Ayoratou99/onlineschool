<?php

namespace Modules\Academique\Services;

use App\Services\BaseService;
use Modules\Academique\Models\ProgrammeDetail;

class ProgrammeDetailService extends BaseService
{
    public function __construct(ProgrammeDetail $model)
    {
        parent::__construct($model);
    }
}
