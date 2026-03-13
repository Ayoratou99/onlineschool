<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\Etage\CreateEtageDTO;
use Modules\Academique\DTOs\Etage\UpdateEtageDTO;
use Modules\Academique\Http\Requests\Etage\StoreEtageRequest;
use Modules\Academique\Http\Requests\Etage\UpdateEtageRequest;
use Modules\Academique\Models\Etage;
use Modules\Academique\Services\EtageService;

class EtageController extends Controller
{
    public function __construct(EtageService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Etage::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(Etage $etage, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $etage);
        return $this->getModel($etage, $request->validated());
    }

    public function store(StoreEtageRequest $request): JsonResponse
    {
        $dto = CreateEtageDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateEtageRequest $request, Etage $etage): JsonResponse
    {
        $dto = UpdateEtageDTO::fromRequest($request);
        return $this->updateFromDTO($etage, $dto);
    }

    public function destroy(Etage $etage): JsonResponse
    {
        $this->authorize('delete', $etage);
        return $this->deleteModel($etage);
    }
}
