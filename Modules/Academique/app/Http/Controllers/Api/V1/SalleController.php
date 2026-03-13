<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\Salle\CreateSalleDTO;
use Modules\Academique\DTOs\Salle\UpdateSalleDTO;
use Modules\Academique\Http\Requests\Salle\StoreSalleRequest;
use Modules\Academique\Http\Requests\Salle\UpdateSalleRequest;
use Modules\Academique\Models\Salle;
use Modules\Academique\Services\SalleService;

class SalleController extends Controller
{
    public function __construct(SalleService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Salle::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(Salle $salle, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $salle);
        return $this->getModel($salle, $request->validated());
    }

    public function store(StoreSalleRequest $request): JsonResponse
    {
        $dto = CreateSalleDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateSalleRequest $request, Salle $salle): JsonResponse
    {
        $dto = UpdateSalleDTO::fromRequest($request);
        return $this->updateFromDTO($salle, $dto);
    }

    public function destroy(Salle $salle): JsonResponse
    {
        $this->authorize('delete', $salle);
        return $this->deleteModel($salle);
    }
}
