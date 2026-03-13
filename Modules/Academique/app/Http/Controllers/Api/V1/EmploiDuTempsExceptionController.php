<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\EmploiDuTempsException\CreateEmploiDuTempsExceptionDTO;
use Modules\Academique\DTOs\EmploiDuTempsException\UpdateEmploiDuTempsExceptionDTO;
use Modules\Academique\Http\Requests\EmploiDuTempsException\StoreEmploiDuTempsExceptionRequest;
use Modules\Academique\Http\Requests\EmploiDuTempsException\UpdateEmploiDuTempsExceptionRequest;
use Modules\Academique\Models\EmploiDuTempsException;
use Modules\Academique\Services\EmploiDuTempsExceptionService;

class EmploiDuTempsExceptionController extends Controller
{
    public function __construct(EmploiDuTempsExceptionService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', EmploiDuTempsException::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(EmploiDuTempsException $emploi_du_temps_exception, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $emploi_du_temps_exception);
        return $this->getModel($emploi_du_temps_exception, $request->validated());
    }

    public function store(StoreEmploiDuTempsExceptionRequest $request): JsonResponse
    {
        $dto = CreateEmploiDuTempsExceptionDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateEmploiDuTempsExceptionRequest $request, EmploiDuTempsException $emploi_du_temps_exception): JsonResponse
    {
        $dto = UpdateEmploiDuTempsExceptionDTO::fromRequest($request);
        return $this->updateFromDTO($emploi_du_temps_exception, $dto);
    }

    public function destroy(EmploiDuTempsException $emploi_du_temps_exception): JsonResponse
    {
        $this->authorize('delete', $emploi_du_temps_exception);
        return $this->deleteModel($emploi_du_temps_exception);
    }
}
