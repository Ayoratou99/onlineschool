<?php

namespace Modules\Securite\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Illuminate\Http\JsonResponse;
use Modules\Securite\DTOs\Permission\CreatePermissionDTO;
use Modules\Securite\DTOs\Permission\UpdatePermissionDTO;
use Modules\Securite\Http\Requests\Permission\StorePermissionRequest;
use Modules\Securite\Http\Requests\Permission\UpdatePermissionRequest;
use Modules\Securite\Models\Permission;
use Modules\Securite\Services\PermissionService;

class PermissionController extends Controller
{
    public function __construct(PermissionService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Permission::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(Permission $permission, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $permission);
        return $this->getModel($permission, $request->validated());
    }

    public function store(StorePermissionRequest $request): JsonResponse
    {
        $dto = CreatePermissionDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): JsonResponse
    {
        $dto = UpdatePermissionDTO::fromRequest($request);
        return $this->updateFromDTO($permission, $dto);
    }

    public function destroy(Permission $permission): JsonResponse
    {
        $this->authorize('delete', $permission);
        return $this->deleteModel($permission);
    }
}

