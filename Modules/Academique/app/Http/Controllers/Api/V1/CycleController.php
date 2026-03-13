<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\Cycle\CreateCycleDTO;
use Modules\Academique\DTOs\Cycle\UpdateCycleDTO;
use Modules\Academique\Http\Requests\Cycle\StoreCycleRequest;
use Modules\Academique\Http\Requests\Cycle\UpdateCycleRequest;
use Modules\Academique\Models\Cycle;
use Modules\Academique\Services\CycleService;

class CycleController extends Controller
{
    public function __construct(CycleService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Cycle::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(Cycle $cycle, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $cycle);
        return $this->getModel($cycle, $request->validated());
    }

    public function store(StoreCycleRequest $request): JsonResponse
    {
        $dto = CreateCycleDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateCycleRequest $request, Cycle $cycle): JsonResponse
    {
        $dto = UpdateCycleDTO::fromRequest($request);
        return $this->updateFromDTO($cycle, $dto);
    }

    public function destroy(Cycle $cycle): JsonResponse
    {
        $this->authorize('delete', $cycle);
        return $this->deleteModel($cycle);
    }
}
