<?php

namespace Modules\Securite\Services;

use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Modules\Securite\Models\Role;

class RoleService extends BaseService
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    /**
     * Create a role with permissions in a transaction.
     *
     * @param array $data Must contain name, description?, state, permission_ids? (array of UUIDs)
     * @return Role
     */
    public function createWithPermissions(array $data): Role
    {
        $permissionIds = $data['permissions'] ?? [];
        unset($data['permissions']);

        return DB::transaction(function () use ($data, $permissionIds) {
            $role = $this->model->create($data);
            if (!empty($permissionIds)) {
                $role->permissions()->sync($permissionIds);
            }
            return $role->load('permissions');
        });
    }

    /**
     * Update a role and optionally sync permissions. Returns the role with permissions loaded.
     *
     * @param string $roleId
     * @param array $data Must contain name, description?, state, permissions? (array of UUIDs)
     * @return Role|null
     */
    public function updateWithPermissions(string $roleId, array $data): ?Role
    {
        $permissionIds = $data['permissions'] ?? null;
        unset($data['permissions']);

        $role = $this->model->find($roleId);
        if (!$role) {
            return null;
        }

        return DB::transaction(function () use ($role, $data, $permissionIds) {
            $role->update($data);
            if (is_array($permissionIds)) {
                $role->permissions()->sync($permissionIds);
            }
            return $role->load('permissions');
        });
    }

    /**
     * Assigner une permission à un rôle
     * 
     * @param string $roleId Identifiant du rôle
     * @param string $permissionId Identifiant de la permission à assigner
     * @return Role
     */
    public function assignPermission(string $roleId, string $permissionId): Role
    {
        $role = $this->model->findOrFail($roleId);
        $role->permissions()->syncWithoutDetaching([$permissionId]);
        return $role->load('permissions');
    }

    /**
     * Retirer une permission d'un rôle
     * 
     * @param string $roleId Identifiant du rôle
     * @param string $permissionId Identifiant de la permission à retirer
     * @return Role
     */
    public function unassignPermission(string $roleId, string $permissionId): Role
    {
        $role = $this->model->findOrFail($roleId);
        $role->permissions()->detach($permissionId);
        return $role->load('permissions');
    }
}

