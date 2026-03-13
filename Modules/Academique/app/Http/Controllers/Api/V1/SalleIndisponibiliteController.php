<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\SalleIndisponibilite\CreateSalleIndisponibiliteDTO;
use Modules\Academique\DTOs\SalleIndisponibilite\UpdateSalleIndisponibiliteDTO;
use Modules\Academique\Http\Requests\SalleIndisponibilite\StoreSalleIndisponibiliteRequest;
use Modules\Academique\Http\Requests\SalleIndisponibilite\UpdateSalleIndisponibiliteRequest;
use Modules\Academique\Models\SalleIndisponibilite;
use Modules\Academique\Services\SalleIndisponibiliteService;

class SalleIndisponibiliteController extends Controller
{
    public function __construct(SalleIndisponibiliteService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', SalleIndisponibilite::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(SalleIndisponibilite $salle_indisponibilite, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $salle_indisponibilite);
        return $this->getModel($salle_indisponibilite, $request->validated());
    }

    public function store(StoreSalleIndisponibiliteRequest $request): JsonResponse
    {
        $dto = CreateSalleIndisponibiliteDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateSalleIndisponibiliteRequest $request, SalleIndisponibilite $salle_indisponibilite): JsonResponse
    {
        $dto = UpdateSalleIndisponibiliteDTO::fromRequest($request);
        return $this->updateFromDTO($salle_indisponibilite, $dto);
    }

    public function destroy(SalleIndisponibilite $salle_indisponibilite): JsonResponse
    {
        $this->authorize('delete', $salle_indisponibilite);
        return $this->deleteModel($salle_indisponibilite);
    }
}
