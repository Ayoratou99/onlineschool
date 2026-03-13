<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\UniteEnseignement\CreateUniteEnseignementDTO;
use Modules\Academique\DTOs\UniteEnseignement\UpdateUniteEnseignementDTO;
use Modules\Academique\Http\Requests\UniteEnseignement\StoreUniteEnseignementRequest;
use Modules\Academique\Http\Requests\UniteEnseignement\UpdateUniteEnseignementRequest;
use Modules\Academique\Models\UniteEnseignement;
use Modules\Academique\Services\UniteEnseignementService;

class UniteEnseignementController extends Controller
{
    public function __construct(UniteEnseignementService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', UniteEnseignement::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(UniteEnseignement $unite_enseignement, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $unite_enseignement);
        return $this->getModel($unite_enseignement, $request->validated());
    }

    public function store(StoreUniteEnseignementRequest $request): JsonResponse
    {
        $dto = CreateUniteEnseignementDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateUniteEnseignementRequest $request, UniteEnseignement $unite_enseignement): JsonResponse
    {
        $dto = UpdateUniteEnseignementDTO::fromRequest($request);
        return $this->updateFromDTO($unite_enseignement, $dto);
    }

    public function destroy(UniteEnseignement $unite_enseignement): JsonResponse
    {
        $this->authorize('delete', $unite_enseignement);
        return $this->deleteModel($unite_enseignement);
    }
}
