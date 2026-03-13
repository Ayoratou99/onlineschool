<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\Parcours\CreateParcoursDTO;
use Modules\Academique\DTOs\Parcours\UpdateParcoursDTO;
use Modules\Academique\Http\Requests\Parcours\StoreParcoursRequest;
use Modules\Academique\Http\Requests\Parcours\UpdateParcoursRequest;
use Modules\Academique\Models\Parcours;
use Modules\Academique\Services\ParcoursService;

class ParcoursController extends Controller
{
    public function __construct(ParcoursService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Parcours::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(Parcours $parcours, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $parcours);
        return $this->getModel($parcours, $request->validated());
    }

    public function store(StoreParcoursRequest $request): JsonResponse
    {
        $dto = CreateParcoursDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateParcoursRequest $request, Parcours $parcours): JsonResponse
    {
        $dto = UpdateParcoursDTO::fromRequest($request);
        return $this->updateFromDTO($parcours, $dto);
    }

    public function destroy(Parcours $parcours): JsonResponse
    {
        $this->authorize('delete', $parcours);
        return $this->deleteModel($parcours);
    }
}
