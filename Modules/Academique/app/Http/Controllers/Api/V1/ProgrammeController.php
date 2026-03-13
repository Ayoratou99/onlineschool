<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\Programme\CreateProgrammeDTO;
use Modules\Academique\DTOs\Programme\UpdateProgrammeDTO;
use Modules\Academique\Http\Requests\Programme\StoreProgrammeRequest;
use Modules\Academique\Http\Requests\Programme\UpdateProgrammeRequest;
use Modules\Academique\Models\Programme;
use Modules\Academique\Services\ProgrammeService;

class ProgrammeController extends Controller
{
    public function __construct(ProgrammeService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Programme::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(Programme $programme, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $programme);
        return $this->getModel($programme, $request->validated());
    }

    public function store(StoreProgrammeRequest $request): JsonResponse
    {
        $dto = CreateProgrammeDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateProgrammeRequest $request, Programme $programme): JsonResponse
    {
        $dto = UpdateProgrammeDTO::fromRequest($request);
        return $this->updateFromDTO($programme, $dto);
    }

    public function destroy(Programme $programme): JsonResponse
    {
        $this->authorize('delete', $programme);
        return $this->deleteModel($programme);
    }
}
