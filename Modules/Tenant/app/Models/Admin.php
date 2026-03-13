<?php

namespace Modules\Tenant\Models;

use App\Contracts\AuthorizableUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements AuthorizableUser, JWTSubject
{
    use HasUuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['name', 'email', 'password', 'state'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return ['state' => $this->state, 'email' => $this->email];
    }

    public function hasRole(string $role): bool
    {
        return strtoupper($role) === 'ADMIN';
    }

    public function hasPermissionTo(string $permission): bool
    {
        return true;
    }
}
