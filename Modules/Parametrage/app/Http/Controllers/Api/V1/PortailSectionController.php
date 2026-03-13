<?php

declare(strict_types=1);

namespace Modules\Parametrage\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Parametrage\Http\Requests\Portail\ReorderPortailRequest;
use Modules\Parametrage\Http\Requests\Portail\StorePortailSectionRequest;
use Modules\Parametrage\Http\Requests\Portail\UpdatePortailSectionRequest;
use Modules\Parametrage\Models\PortailSection;
use Modules\Parametrage\Services\PortailSectionService;

class PortailSectionController extends Controller
{
    public function __construct(PortailSectionService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function store(StorePortailSectionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['is_active'] = $data['is_active'] ?? true;
        $record = PortailSection::create($data);
        return $this->sendResponse($record, 'FUIP_201', 201);
    }

    public function show(PortailSection $portail_section, IndexQueryRequest $request): JsonResponse
    {
        $params = $request->validated();
        $record = $this->service->find($portail_section->id, $params['populate'] ?? null);
        return $record ? $this->sendResponse($record, 'FUIP_101') : $this->sendError('FUIP_404');
    }

    public function update(PortailSection $portail_section, UpdatePortailSectionRequest $request): JsonResponse
    {
        $record = $this->service->update($portail_section->id, $request->validated());
        return $record ? $this->sendResponse($record, 'FUIP_200') : $this->sendError('FUIP_404');
    }

    public function destroy(PortailSection $portail_section): JsonResponse
    {
        $deleted = $this->service->delete($portail_section->id);
        return $deleted ? $this->sendResponse($portail_section, 'FUIP_204') : $this->sendError('FUIP_404');
    }

    public function reorder(ReorderPortailRequest $request): JsonResponse
    {
        $this->service->reorder($request->validated('ids'));
        return $this->sendResponse(['ids' => $request->validated('ids')], 'FUIP_200');
    }
}
