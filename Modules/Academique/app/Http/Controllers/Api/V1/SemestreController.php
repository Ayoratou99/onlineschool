<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\Semestre\CreateSemestreDTO;
use Modules\Academique\DTOs\Semestre\UpdateSemestreDTO;
use Modules\Academique\Http\Requests\Semestre\StoreSemestreRequest;
use Modules\Academique\Http\Requests\Semestre\UpdateSemestreRequest;
use Modules\Academique\Models\Semestre;
use Modules\Academique\Services\SemestreService;

class SemestreController extends Controller
{
    public function __construct(SemestreService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Semestre::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(Semestre $semestre, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $semestre);
        return $this->getModel($semestre, $request->validated());
    }

    public function store(StoreSemestreRequest $request): JsonResponse
    {
        $dto = CreateSemestreDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateSemestreRequest $request, Semestre $semestre): JsonResponse
    {
        $dto = UpdateSemestreDTO::fromRequest($request);
        return $this->updateFromDTO($semestre, $dto);
    }

    public function destroy(Semestre $semestre): JsonResponse
    {
        $this->authorize('delete', $semestre);
        return $this->deleteModel($semestre);
    }
}
