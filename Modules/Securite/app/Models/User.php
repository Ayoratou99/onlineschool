<?php

namespace Modules\Securite\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Contracts\AuthorizableUser;
use App\Traits\LogsActivity;
use Modules\Securite\Traits\HasPermissions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tymon\JWTAuth\Contracts\JWTSubject; 
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Modules\Securite\Database\Factories\UserFactory;
use App\Traits\SoftDeletesWithUniqueFields;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "User",
    title: "User Model",
    description: "Représentation d'un utilisateur dans le système",
    required: ["id", "nom", "email", "state"],
    properties: [
        new OA\Property(property: "id", type: "string", format: "uuid"),
        new OA\Property(property: "nom", type: "string"),
        new OA\Property(property: "prenom", type: "string", nullable: true),
        new OA\Property(property: "email", type: "string", format: "email"),
        new OA\Property(property: "state", type: "string"),
        new OA\Property(property: "two_factor_enabled", type: "boolean")
    ],
    additionalProperties: false
)]

class User extends Authenticatable implements AuthorizableUser, JWTSubject , MustVerifyEmail
{
    use HasFactory, HasPermissions, LogsActivity, SoftDeletes, HasUuids, SoftDeletesWithUniqueFields;

    protected static $ignoreActivityAttributes = ['two_factor_secret'];

    protected function getUniqueFields(): array
    {
        return ['email'];
    }

    // No global scope: UserTodos (user_id = current user) is for models with a user_id column, not for User itself.

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['nom', 'prenom', 'email', 'password', 'state', 'two_factor_enabled','two_factor_secret'];

    protected $hidden = ['password', 'remember_token', 'two_factor_secret'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'state' => 'string',
        'password' => 'hashed',
        'two_factor_enabled' => 'boolean',
    ];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Returns your UUID
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [
            'state' => $this->state,
            'email' => $this->email
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function permissions()
    {
        return $this->roles->permissions;
    }
}
