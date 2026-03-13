<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\EmploiDuTemps\CreateEmploiDuTempsDTO;
use Modules\Academique\DTOs\EmploiDuTemps\UpdateEmploiDuTempsDTO;
use Modules\Academique\Http\Requests\EmploiDuTemps\StoreEmploiDuTempsRequest;
use Modules\Academique\Http\Requests\EmploiDuTemps\UpdateEmploiDuTempsRequest;
use Modules\Academique\Models\EmploiDuTemps;
use Modules\Academique\Services\EmploiDuTempsService;

class EmploiDuTempsController extends Controller
{
    public function __construct(EmploiDuTempsService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', EmploiDuTemps::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(EmploiDuTemps $emploi_du_temps, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $emploi_du_temps);
        return $this->getModel($emploi_du_temps, $request->validated());
    }

    public function store(StoreEmploiDuTempsRequest $request): JsonResponse
    {
        $dto = CreateEmploiDuTempsDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateEmploiDuTempsRequest $request, EmploiDuTemps $emploi_du_temps): JsonResponse
    {
        $dto = UpdateEmploiDuTempsDTO::fromRequest($request);
        return $this->updateFromDTO($emploi_du_temps, $dto);
    }

    public function destroy(EmploiDuTemps $emploi_du_temps): JsonResponse
    {
        $this->authorize('delete', $emploi_du_temps);
        return $this->deleteModel($emploi_du_temps);
    }
}
