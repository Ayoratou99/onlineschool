<?php

namespace Modules\Users\Services;

use Modules\Users\Interfaces\UserInterface;
use Modules\Users\Models\User;
use Illuminate\Support\Collection;

class UserService implements UserInterface
{

    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Find a record by ID.
     */
    public function findById(int $id): User
    {
        return $this->userModel->find($id);
    }

    public function updateOrCreate(array $data, array $update): User
    {
        return $this->userModel->updateOrCreate($data, $update);
    }

    /**
     * Get all records.
     */
    public function findAll(): Collection
    {
        // TODO: Implement this method
    }

    /**
     * Create a new record.
     */
    public function create(array $data): User
    {
        // TODO: Implement this method
    }

    /**
     * Update an existing record.
     */
    public function update(int $id, array $data): User
    {
        // TODO: Implement this method
    }

    /**
     * Delete a record.
     */
    public function delete(int $id): bool
    {
        // TODO: Implement this method
    }

    /**
     * Find a record by email.
     */
    public function findByEmail(string $email): User
    {
        // TODO: Implement this method
    }

    /**
     * Suspend a record.
     */
    public function suspend(int $id): bool
    {
        // TODO: Implement this method
    }

    /**
     * Activate a record.
     */
    public function activate(int $id): bool
    {
        // TODO: Implement this method
    }
}
