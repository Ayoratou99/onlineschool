<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Modules\Users\Enums\UserType;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'User',
    title: 'User',
    description: 'User model',
    required: ['id', 'email', 'first_name', 'last_name', 'phone', 'address', 'city', 'state', 'zip', 'country', 'user_type', 'is_active'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', description: 'User unique identifier'),
        new OA\Property(property: 'email', type: 'string', format: 'email', description: 'User email address'),
        new OA\Property(property: 'first_name', type: 'string', description: 'User first name'),
        new OA\Property(property: 'last_name', type: 'string', description: 'User last name'),
        new OA\Property(property: 'phone', type: 'string', description: 'User phone number'),
        new OA\Property(property: 'address', type: 'string', description: 'User street address'),
        new OA\Property(property: 'city', type: 'string', description: 'User city'),
        new OA\Property(property: 'state', type: 'string', description: 'User state/province'),
        new OA\Property(property: 'zip', type: 'string', description: 'User postal/zip code'),
        new OA\Property(property: 'country', type: 'string', description: 'User country'),
        new OA\Property(property: 'user_type', type: 'string', enum: ['admin', 'academic_staff', 'student'], description: 'User role type'),
        new OA\Property(property: 'is_active', type: 'boolean', description: 'Whether the user account is active'),
    ]
)]

class User extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The "type" of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'keycloak_id',
        'username',
        'email',
        'first_name',
        'last_name',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'user_type',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'user_type' => UserType::class,
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id')
            ->withTimestamps();
    }

    public function getPrivilegesAttribute()
    {
        return $this->roles()->with('privileges')->get()->pluck('privileges')->flatten()->unique('id');
    }

    public function hasRole(string $roleSlug): bool
    {
        return $this->roles()->where('slug', $roleSlug)->exists();
    }

    public function hasPrivilege(string $privilegeSlug): bool
    {
        return $this->roles()->whereHas('privileges', function ($query) use ($privilegeSlug) {
            $query->where('slug', $privilegeSlug);
        })->exists();
    }
}
