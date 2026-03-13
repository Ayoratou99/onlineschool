<?php

namespace Modules\Document\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Document\Documentation\Swagger\GeneratedDocumentSwagger;
use Modules\Document\Models\GeneratedDocument;
use Modules\Document\Services\GeneratedDocumentService;

class GeneratedDocumentController extends Controller
{
    use GeneratedDocumentSwagger;

    public function __construct(GeneratedDocumentService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', GeneratedDocument::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(GeneratedDocument $generatedDocument, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $generatedDocument);
        return $this->getModel($generatedDocument, $request->validated());
    }
}
