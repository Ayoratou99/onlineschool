<?php

namespace Modules\Document\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Document\Documentation\Swagger\TemplateDocumentSwagger;
use Modules\Document\Http\Requests\StoreTemplateDocumentRequest;
use Modules\Document\Http\Requests\UpdateTemplateDocumentRequest;
use Modules\Document\Models\TemplateDocument;
use Modules\Document\Services\TemplateDocumentService;

class TemplateDocumentController extends Controller
{
    use TemplateDocumentSwagger;

    public function __construct(TemplateDocumentService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', TemplateDocument::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(TemplateDocument $templateDocument, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $templateDocument);
        return $this->getModel($templateDocument, $request->validated());
    }

    public function store(StoreTemplateDocumentRequest $request): JsonResponse
    {
        $this->authorize('create', TemplateDocument::class);
        $path = TemplateDocumentService::buildPathFromName($request->validated('name'));
        $this->service->storeFile($request->file('file'), $path);
        $record = $this->service->create([
            'name' => $request->validated('name'),
            'description' => $request->validated('description'),
            'path' => $path,
        ]);
        return $this->sendResponse($record, 'FUIP_201', 201);
    }

    public function update(UpdateTemplateDocumentRequest $request, TemplateDocument $templateDocument): JsonResponse
    {
        $this->authorize('update', $templateDocument);
        if ($request->hasFile('file')) {
            $this->service->replaceFile($request->file('file'), $templateDocument->path);
        }
        $record = $this->service->update($templateDocument->id, [
            'name' => $request->validated('name'),
            'description' => $request->validated('description'),
        ]);
        return $this->sendResponse($record, 'FUIP_200');
    }

    public function destroy(TemplateDocument $templateDocument): JsonResponse
    {
        $this->authorize('delete', $templateDocument);
        return $this->deleteModel($templateDocument);
    }
}
