<?php

namespace Modules\Securite\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Modules\Securite\DTOs\Role\CreateRoleDTO;
use Modules\Securite\DTOs\Role\UpdateRoleDTO;
use Modules\Securite\Http\Requests\AssignPermissionRequest;
use Modules\Securite\Http\Requests\StoreRoleRequest;
use Modules\Securite\Http\Requests\UnassignPermissionRequest;
use Modules\Securite\Http\Requests\UpdateRoleRequest;
use Modules\Securite\Models\Role;
use Modules\Securite\Services\RoleService;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public function __construct(RoleService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Role::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(Role $role, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $role);
        return $this->getModel($role, $request->validated());
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $dto = CreateRoleDTO::fromRequest($request);
        $role = $this->service->createWithPermissions($dto->toArray());
        return $this->sendResponse($role, 'FUIP_201', 201);
    }

    public function update(Role $role, UpdateRoleRequest $request): JsonResponse
    {
        $dto = UpdateRoleDTO::fromRequest($request);
        $record = $this->service->updateWithPermissions($role->id, $dto->toArray());
        return $this->sendResponse($record, 'FUIP_200');
    }

    public function destroy(Role $role): JsonResponse
    {
        $this->authorize('delete', $role);
        return $this->deleteModel($role);
    }

    public function assignPermission(Role $role, AssignPermissionRequest $request): JsonResponse
    {
        $role = $this->service->assignPermission($role->id, $request->validated('permission_id'));
        return $this->sendResponse($role, 'FUIP_200');
    }

    public function unassignPermission(Role $role, UnassignPermissionRequest $request): JsonResponse
    {
        $role = $this->service->unassignPermission($role->id, $request->validated('permission_id'));
        return $this->sendResponse($role, 'FUIP_200');
    }
}

