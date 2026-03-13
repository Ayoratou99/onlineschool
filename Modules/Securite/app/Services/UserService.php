<?php

namespace Modules\Securite\Services;

use App\Services\BaseService;
use Illuminate\Support\Str;
use Modules\Securite\Events\UserCreated;
use Modules\Securite\Models\User;

class UserService extends BaseService
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function assignRole(string $userId, string $roleId): User
    {
        $user = $this->model->findOrFail($userId);
        $user->roles()->syncWithoutDetaching([$roleId]);
        return $user->load('roles');
    }

    public function create(array $data): User
    {
        $data['password'] = $data['password'] ?? Str::random(32);
        $user = $this->model->create($data);
        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }
        UserCreated::dispatch($user);
        return $user->load('roles');
    }

    /**
     * Update user and optionally sync roles. Returns the user with roles loaded.
     */
    public function update(string $id, array $data): ?User
    {
        $roleIds = $data['roles'] ?? null;
        unset($data['roles']);

        $user = $this->model->find($id);
        if (!$user) {
            return null;
        }

        $user->update($data);
        if (is_array($roleIds)) {
            $user->roles()->sync($roleIds);
        }
        return $user->load('roles');
    }

    /**
     * Retirer un rôle d'un utilisateur
     * 
     * @param string $userId Identifiant de l'utilisateur
     * @param string $roleId Identifiant du rôle à retirer
     * @return User
     */
    public function unassignRole(string $userId, string $roleId): User
    {
        $user = $this->model->findOrFail($userId);
        $user->roles()->detach($roleId);
        return $user->load('roles');
    }
}

