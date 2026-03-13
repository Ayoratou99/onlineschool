<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\Etablissement\CreateEtablissementDTO;
use Modules\Academique\DTOs\Etablissement\UpdateEtablissementDTO;
use Modules\Academique\Http\Requests\Etablissement\StoreEtablissementRequest;
use Modules\Academique\Http\Requests\Etablissement\UpdateEtablissementRequest;
use Modules\Academique\Models\Etablissement;
use Modules\Academique\Services\EtablissementService;

class EtablissementController extends Controller
{
    public function __construct(EtablissementService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Etablissement::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(Etablissement $etablissement, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $etablissement);
        return $this->getModel($etablissement, $request->validated());
    }

    public function store(StoreEtablissementRequest $request): JsonResponse
    {
        $dto = CreateEtablissementDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateEtablissementRequest $request, Etablissement $etablissement): JsonResponse
    {
        $dto = UpdateEtablissementDTO::fromRequest($request);
        return $this->updateFromDTO($etablissement, $dto);
    }

    public function destroy(Etablissement $etablissement): JsonResponse
    {
        $this->authorize('delete', $etablissement);
        return $this->deleteModel($etablissement);
    }
}
