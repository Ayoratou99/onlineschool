<?php

declare(strict_types=1);

namespace Modules\Parametrage\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use App\Services\TenantStorageService;
use Illuminate\Http\JsonResponse;
use Modules\Parametrage\Http\Requests\Portail\StorePortailActualiteRequest;
use Modules\Parametrage\Http\Requests\Portail\UpdatePortailActualiteRequest;
use Modules\Parametrage\Http\Requests\Portail\UpdatePortailActualiteCiblageRequest;
use Modules\Parametrage\Models\PortailActualite;
use Modules\Parametrage\Services\PortailActualiteService;

class PortailActualiteController extends Controller
{
    public function __construct(
        PortailActualiteService $service,
        protected TenantStorageService $storage
    ) {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function store(StorePortailActualiteRequest $request): JsonResponse
    {
        $data = $request->validated();
        unset($data['image']);
        $data['auteur_id'] = $request->user()->id;
        $data['ciblage'] = $data['ciblage'] ?? PortailActualite::CIBLAGE_TOUS;
        $data['is_epingle'] = $data['is_epingle'] ?? false;
        $data['is_active'] = $data['is_active'] ?? true;
        if ($request->hasFile('image')) {
            $data['image_url'] = $this->storage->put('portail/actualites/' . uniqid() . '.jpg', $request->file('image'));
        }
        $record = PortailActualite::create($data);
        return $this->sendResponse($record, 'FUIP_201', 201);
    }

    public function show(PortailActualite $portail_actualite, IndexQueryRequest $request): JsonResponse
    {
        $params = $request->validated();
        $record = $this->service->find($portail_actualite->id, $params['populate'] ?? null);
        return $record ? $this->sendResponse($record, 'FUIP_101') : $this->sendError('FUIP_404');
    }

    public function update(PortailActualite $portail_actualite, UpdatePortailActualiteRequest $request): JsonResponse
    {
        $data = $request->validated();
        unset($data['image']);
        if ($request->hasFile('image')) {
            $data['image_url'] = $this->storage->put('portail/actualites/' . uniqid() . '.jpg', $request->file('image'));
        }
        $record = $this->service->update($portail_actualite->id, $data);
        return $record ? $this->sendResponse($record, 'FUIP_200') : $this->sendError('FUIP_404');
    }

    public function destroy(PortailActualite $portail_actualite): JsonResponse
    {
        $deleted = $this->service->delete($portail_actualite->id);
        return $deleted ? $this->sendResponse($portail_actualite, 'FUIP_204') : $this->sendError('FUIP_404');
    }

    public function toggleEpingle(PortailActualite $portail_actualite): JsonResponse
    {
        $record = $this->service->toggleEpingle($portail_actualite->id);
        return $record ? $this->sendResponse($record, 'FUIP_200') : $this->sendError('FUIP_404');
    }

    public function updateCiblage(UpdatePortailActualiteCiblageRequest $request, PortailActualite $portail_actualite): JsonResponse
    {
        $record = $this->service->updateCiblage($portail_actualite->id, $request->validated('ciblage'));
        return $record ? $this->sendResponse($record, 'FUIP_200') : $this->sendError('FUIP_404');
    }
}
