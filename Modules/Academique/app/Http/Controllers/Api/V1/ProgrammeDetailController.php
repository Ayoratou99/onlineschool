<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\ProgrammeDetail\CreateProgrammeDetailDTO;
use Modules\Academique\DTOs\ProgrammeDetail\UpdateProgrammeDetailDTO;
use Modules\Academique\Http\Requests\ProgrammeDetail\StoreProgrammeDetailRequest;
use Modules\Academique\Http\Requests\ProgrammeDetail\UpdateProgrammeDetailRequest;
use Modules\Academique\Models\ProgrammeDetail;
use Modules\Academique\Services\ProgrammeDetailService;

class ProgrammeDetailController extends Controller
{
    public function __construct(ProgrammeDetailService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', ProgrammeDetail::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(ProgrammeDetail $programme_detail, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $programme_detail);
        return $this->getModel($programme_detail, $request->validated());
    }

    public function store(StoreProgrammeDetailRequest $request): JsonResponse
    {
        $dto = CreateProgrammeDetailDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateProgrammeDetailRequest $request, ProgrammeDetail $programme_detail): JsonResponse
    {
        $dto = UpdateProgrammeDetailDTO::fromRequest($request);
        return $this->updateFromDTO($programme_detail, $dto);
    }

    public function destroy(ProgrammeDetail $programme_detail): JsonResponse
    {
        $this->authorize('delete', $programme_detail);
        return $this->deleteModel($programme_detail);
    }
}
