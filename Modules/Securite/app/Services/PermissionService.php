<?php

namespace Modules\Securite\Services;

use App\Services\BaseService;
use Modules\Securite\Models\Permission;

class PermissionService extends BaseService
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }
}

