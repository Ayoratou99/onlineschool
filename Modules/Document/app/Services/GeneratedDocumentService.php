<?php

namespace Modules\Document\Services;

use App\Services\BaseService;
use Modules\Document\Models\GeneratedDocument;

class GeneratedDocumentService extends BaseService
{
    public function __construct(GeneratedDocument $model)
    {
        parent::__construct($model);
    }
}
