<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\Matiere\CreateMatiereDTO;
use Modules\Academique\DTOs\Matiere\UpdateMatiereDTO;
use Modules\Academique\Http\Requests\Matiere\StoreMatiereRequest;
use Modules\Academique\Http\Requests\Matiere\UpdateMatiereRequest;
use Modules\Academique\Models\Matiere;
use Modules\Academique\Services\MatiereService;

class MatiereController extends Controller
{
    public function __construct(MatiereService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Matiere::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(Matiere $matiere, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $matiere);
        return $this->getModel($matiere, $request->validated());
    }

    public function store(StoreMatiereRequest $request): JsonResponse
    {
        $dto = CreateMatiereDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateMatiereRequest $request, Matiere $matiere): JsonResponse
    {
        $dto = UpdateMatiereDTO::fromRequest($request);
        return $this->updateFromDTO($matiere, $dto);
    }

    public function destroy(Matiere $matiere): JsonResponse
    {
        $this->authorize('delete', $matiere);
        return $this->deleteModel($matiere);
    }
}
