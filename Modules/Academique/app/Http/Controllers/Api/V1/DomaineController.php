<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\Domaine\CreateDomaineDTO;
use Modules\Academique\DTOs\Domaine\UpdateDomaineDTO;
use Modules\Academique\Http\Requests\Domaine\StoreDomaineRequest;
use Modules\Academique\Http\Requests\Domaine\UpdateDomaineRequest;
use Modules\Academique\Models\Domaine;
use Modules\Academique\Services\DomaineService;

class DomaineController extends Controller
{
    public function __construct(DomaineService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Domaine::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(Domaine $domaine, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $domaine);
        return $this->getModel($domaine, $request->validated());
    }

    public function store(StoreDomaineRequest $request): JsonResponse
    {
        $dto = CreateDomaineDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateDomaineRequest $request, Domaine $domaine): JsonResponse
    {
        $dto = UpdateDomaineDTO::fromRequest($request);
        return $this->updateFromDTO($domaine, $dto);
    }

    public function destroy(Domaine $domaine): JsonResponse
    {
        $this->authorize('delete', $domaine);
        return $this->deleteModel($domaine);
    }
}
