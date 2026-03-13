<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\Niveau\CreateNiveauDTO;
use Modules\Academique\DTOs\Niveau\UpdateNiveauDTO;
use Modules\Academique\Http\Requests\Niveau\StoreNiveauRequest;
use Modules\Academique\Http\Requests\Niveau\UpdateNiveauRequest;
use Modules\Academique\Models\Niveau;
use Modules\Academique\Services\NiveauService;

class NiveauController extends Controller
{
    public function __construct(NiveauService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Niveau::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(Niveau $niveau, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $niveau);
        return $this->getModel($niveau, $request->validated());
    }

    public function store(StoreNiveauRequest $request): JsonResponse
    {
        $dto = CreateNiveauDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateNiveauRequest $request, Niveau $niveau): JsonResponse
    {
        $dto = UpdateNiveauDTO::fromRequest($request);
        return $this->updateFromDTO($niveau, $dto);
    }

    public function destroy(Niveau $niveau): JsonResponse
    {
        $this->authorize('delete', $niveau);
        return $this->deleteModel($niveau);
    }
}
