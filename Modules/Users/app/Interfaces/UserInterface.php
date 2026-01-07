<?php

namespace Modules\Users\Interfaces;

use Modules\Users\Models\User;

interface UserInterface 
{
    public function findById(int $id): User;
    public function findAll(): Collection;
    public function create(array $data): User;
    public function updateOrCreate(array $data, array $update): User;
    public function update(int $id, array $data): User;
    public function delete(int $id): bool;
    public function findByEmail(string $email): User;
    public function suspend(int $id): bool;
    public function activate(int $id): bool;

}
