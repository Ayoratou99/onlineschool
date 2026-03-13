<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\Batiment\CreateBatimentDTO;
use Modules\Academique\DTOs\Batiment\UpdateBatimentDTO;
use Modules\Academique\Http\Requests\Batiment\StoreBatimentRequest;
use Modules\Academique\Http\Requests\Batiment\UpdateBatimentRequest;
use Modules\Academique\Models\Batiment;
use Modules\Academique\Services\BatimentService;

class BatimentController extends Controller
{
    public function __construct(BatimentService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Batiment::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(Batiment $batiment, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $batiment);
        return $this->getModel($batiment, $request->validated());
    }

    public function store(StoreBatimentRequest $request): JsonResponse
    {
        $dto = CreateBatimentDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateBatimentRequest $request, Batiment $batiment): JsonResponse
    {
        $dto = UpdateBatimentDTO::fromRequest($request);
        return $this->updateFromDTO($batiment, $dto);
    }

    public function destroy(Batiment $batiment): JsonResponse
    {
        $this->authorize('delete', $batiment);
        return $this->deleteModel($batiment);
    }
}
