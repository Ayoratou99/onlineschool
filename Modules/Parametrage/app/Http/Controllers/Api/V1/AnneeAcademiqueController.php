<?php

namespace Modules\Parametrage\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Parametrage\DTOs\AnneeAcademique\CreateAnneeAcademiqueDTO;
use Modules\Parametrage\DTOs\AnneeAcademique\UpdateAnneeAcademiqueDTO;
use Modules\Parametrage\Http\Requests\AnneeAcademique\StoreAnneeAcademiqueRequest;
use Modules\Parametrage\Http\Requests\AnneeAcademique\UpdateAnneeAcademiqueRequest;
use Modules\Parametrage\Models\AnneeAcademique;
use Modules\Parametrage\Services\AnneeAcademiqueService;

class AnneeAcademiqueController extends Controller
{
    public function __construct(AnneeAcademiqueService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', AnneeAcademique::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(AnneeAcademique $annee_academique, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $annee_academique);
        return $this->getModel($annee_academique, $request->validated());
    }

    public function store(StoreAnneeAcademiqueRequest $request): JsonResponse
    {
        $dto = CreateAnneeAcademiqueDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(AnneeAcademique $annee_academique, UpdateAnneeAcademiqueRequest $request): JsonResponse
    {
        $dto = UpdateAnneeAcademiqueDTO::fromRequest($request);
        return $this->updateFromDTO($annee_academique, $dto);
    }

    public function destroy(AnneeAcademique $annee_academique): JsonResponse
    {
        $this->authorize('delete', $annee_academique);
        return $this->deleteModel($annee_academique);
    }
}
