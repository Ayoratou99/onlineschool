<?php

namespace Modules\Securite\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use Modules\Securite\DTOs\User\CreateUserDTO;
use Modules\Securite\DTOs\User\UpdateUserDTO;
use Modules\Securite\Models\User;
use Modules\Securite\Services\UserService;
use Illuminate\Http\JsonResponse;
use Modules\Securite\Http\Requests\AssignRoleRequest;
use Modules\Securite\Http\Requests\StoreUserRequest;
use Modules\Securite\Http\Requests\UnassignRoleRequest;
use Modules\Securite\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function __construct(UserService $service)
    {
        parent::__construct($service);
    }

    public function index(IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('viewAny', User::class);
        $params = array_merge(['per_page' => 15, 'page' => 1], $request->validated());
        return $this->paginateModel($params);
    }

    public function show(User $user, IndexQueryRequest $request): JsonResponse
    {
        $this->authorize('view', $user);
        return $this->getModel($user, $request->validated());
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $dto = CreateUserDTO::fromRequest($request);
        return $this->createFromDTO($dto);
    }

    public function update(User $user, UpdateUserRequest $request): JsonResponse
    {
        $dto = UpdateUserDTO::fromRequest($request);
        return $this->updateFromDTO($user, $dto);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);
        return $this->deleteModel($user);
    }

    public function assignRole(User $user, AssignRoleRequest $request): JsonResponse
    {
        $user = $this->service->assignRole($user->id, $request->validated('role_id'));
        return $this->sendResponse($user, 'FUIP_200');
    }

    public function unassignRole(User $user, UnassignRoleRequest $request): JsonResponse
    {
        $user = $this->service->unassignRole($user->id, $request->validated('role_id'));
        return $this->sendResponse($user, 'FUIP_200');
    }
}

