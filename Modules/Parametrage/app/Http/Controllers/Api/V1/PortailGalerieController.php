<?php

declare(strict_types=1);

namespace Modules\Parametrage\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use App\Services\TenantStorageService;
use Illuminate\Http\JsonResponse;
use Modules\Parametrage\Http\Requests\Portail\ReorderPortailRequest;
use Modules\Parametrage\Http\Requests\Portail\StorePortailGalerieRequest;
use Modules\Parametrage\Http\Requests\Portail\UpdatePortailGalerieRequest;
use Modules\Parametrage\Models\PortailGalerie;
use Modules\Parametrage\Services\PortailGalerieService;

class PortailGalerieController extends Controller
{
    public function __construct(
        PortailGalerieService $service,
        protected TenantStorageService $storage
    ) {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function store(StorePortailGalerieRequest $request): JsonResponse
    {
        $data = $request->validated();
        unset($data['image']);
        $data['is_active'] = $data['is_active'] ?? true;
        if ($request->hasFile('image')) {
            $data['url'] = $this->storage->put('portail/galerie/' . uniqid() . '.jpg', $request->file('image'));
        }
        if (empty($data['url'])) {
            return $this->sendError('FUIP_422', ['url' => ['Either url or image file is required.']], 422);
        }
        $record = PortailGalerie::create($data);
        return $this->sendResponse($record, 'FUIP_201', 201);
    }

    public function show(PortailGalerie $portail_galerie, IndexQueryRequest $request): JsonResponse
    {
        $params = $request->validated();
        $record = $this->service->find($portail_galerie->id, $params['populate'] ?? null);
        return $record ? $this->sendResponse($record, 'FUIP_101') : $this->sendError('FUIP_404');
    }

    public function update(PortailGalerie $portail_galerie, UpdatePortailGalerieRequest $request): JsonResponse
    {
        $data = $request->validated();
        unset($data['image']);
        if ($request->hasFile('image')) {
            $data['url'] = $this->storage->put('portail/galerie/' . uniqid() . '.jpg', $request->file('image'));
        }
        $record = $this->service->update($portail_galerie->id, $data);
        return $record ? $this->sendResponse($record, 'FUIP_200') : $this->sendError('FUIP_404');
    }

    public function destroy(PortailGalerie $portail_galerie): JsonResponse
    {
        $deleted = $this->service->delete($portail_galerie->id);
        return $deleted ? $this->sendResponse($portail_galerie, 'FUIP_204') : $this->sendError('FUIP_404');
    }

    public function reorder(ReorderPortailRequest $request): JsonResponse
    {
        $this->service->reorder($request->validated('ids'));
        return $this->sendResponse(['ids' => $request->validated('ids')], 'FUIP_200');
    }
}
