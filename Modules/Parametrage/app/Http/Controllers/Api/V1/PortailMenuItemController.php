<?php

declare(strict_types=1);

namespace Modules\Parametrage\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Parametrage\Http\Requests\Portail\ReorderPortailRequest;
use Modules\Parametrage\Http\Requests\Portail\StorePortailMenuItemRequest;
use Modules\Parametrage\Http\Requests\Portail\UpdatePortailMenuItemRequest;
use Modules\Parametrage\Models\PortailMenuItem;
use Modules\Parametrage\Services\PortailMenuItemService;

class PortailMenuItemController extends Controller
{
    public function __construct(PortailMenuItemService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function store(StorePortailMenuItemRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['is_active'] = $data['is_active'] ?? true;
        $record = PortailMenuItem::create($data);
        return $this->sendResponse($record, 'FUIP_201', 201);
    }

    public function show(PortailMenuItem $portail_menu_item, IndexQueryRequest $request): JsonResponse
    {
        $params = $request->validated();
        $record = $this->service->find($portail_menu_item->id, $params['populate'] ?? null);
        return $record ? $this->sendResponse($record, 'FUIP_101') : $this->sendError('FUIP_404');
    }

    public function update(PortailMenuItem $portail_menu_item, UpdatePortailMenuItemRequest $request): JsonResponse
    {
        $record = $this->service->update($portail_menu_item->id, $request->validated());
        return $record ? $this->sendResponse($record, 'FUIP_200') : $this->sendError('FUIP_404');
    }

    public function destroy(PortailMenuItem $portail_menu_item): JsonResponse
    {
        $deleted = $this->service->delete($portail_menu_item->id);
        return $deleted ? $this->sendResponse($portail_menu_item, 'FUIP_204') : $this->sendError('FUIP_404');
    }

    public function reorder(ReorderPortailRequest $request): JsonResponse
    {
        $this->service->reorder($request->validated('ids'));
        return $this->sendResponse(['ids' => $request->validated('ids')], 'FUIP_200');
    }
}
