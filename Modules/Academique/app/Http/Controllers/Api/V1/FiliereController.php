<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\Filiere\CreateFiliereDTO;
use Modules\Academique\DTOs\Filiere\UpdateFiliereDTO;
use Modules\Academique\Http\Requests\Filiere\StoreFiliereRequest;
use Modules\Academique\Http\Requests\Filiere\UpdateFiliereRequest;
use Modules\Academique\Models\Filiere;
use Modules\Academique\Services\FiliereService;

class FiliereController extends Controller
{
    public function __construct(FiliereService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Filiere::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(Filiere $filiere, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $filiere);
        return $this->getModel($filiere, $request->validated());
    }

    public function store(StoreFiliereRequest $request): JsonResponse
    {
        $dto = CreateFiliereDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateFiliereRequest $request, Filiere $filiere): JsonResponse
    {
        $dto = UpdateFiliereDTO::fromRequest($request);
        return $this->updateFromDTO($filiere, $dto);
    }

    public function destroy(Filiere $filiere): JsonResponse
    {
        $this->authorize('delete', $filiere);
        return $this->deleteModel($filiere);
    }
}
