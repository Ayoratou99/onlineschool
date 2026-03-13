<?php

namespace Modules\Parametrage\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Parametrage\DTOs\BaremeMention\CreateBaremeMentionDTO;
use Modules\Parametrage\DTOs\BaremeMention\UpdateBaremeMentionDTO;
use Modules\Parametrage\Http\Requests\BaremeMention\StoreBaremeMentionRequest;
use Modules\Parametrage\Http\Requests\BaremeMention\UpdateBaremeMentionRequest;
use Modules\Parametrage\Models\BaremeMention;
use Modules\Parametrage\Services\BaremeMentionService;

class BaremeMentionController extends Controller
{
    public function __construct(BaremeMentionService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', BaremeMention::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(BaremeMention $bareme_mention, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $bareme_mention);
        return $this->getModel($bareme_mention, $request->validated());
    }

    public function store(StoreBaremeMentionRequest $request): JsonResponse
    {
        $dto = CreateBaremeMentionDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(BaremeMention $bareme_mention, UpdateBaremeMentionRequest $request): JsonResponse
    {
        $dto = UpdateBaremeMentionDTO::fromRequest($request);
        return $this->updateFromDTO($bareme_mention, $dto);
    }

    public function destroy(BaremeMention $bareme_mention): JsonResponse
    {
        $this->authorize('delete', $bareme_mention);
        return $this->deleteModel($bareme_mention);
    }
}
