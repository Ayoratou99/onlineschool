<?php

namespace Modules\Academique\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Academique\DTOs\Groupe\CreateGroupeDTO;
use Modules\Academique\DTOs\Groupe\UpdateGroupeDTO;
use Modules\Academique\Http\Requests\Groupe\StoreGroupeRequest;
use Modules\Academique\Http\Requests\Groupe\UpdateGroupeRequest;
use Modules\Academique\Models\Groupe;
use Modules\Academique\Services\GroupeService;

class GroupeController extends Controller
{
    public function __construct(GroupeService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Groupe::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(Groupe $groupe, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $groupe);
        return $this->getModel($groupe, $request->validated());
    }

    public function store(StoreGroupeRequest $request): JsonResponse
    {
        $dto = CreateGroupeDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdateGroupeRequest $request, Groupe $groupe): JsonResponse
    {
        $dto = UpdateGroupeDTO::fromRequest($request);
        return $this->updateFromDTO($groupe, $dto);
    }

    public function destroy(Groupe $groupe): JsonResponse
    {
        $this->authorize('delete', $groupe);
        return $this->deleteModel($groupe);
    }
}
