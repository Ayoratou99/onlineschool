<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Role',
    title: 'Role',
    description: 'Role model representing a user role',
    required: ['id', 'name', 'slug', 'is_active'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', description: 'Role unique identifier'),
        new OA\Property(property: 'name', type: 'string', description: 'Role display name'),
        new OA\Property(property: 'slug', type: 'string', description: 'Unique slug identifier'),
        new OA\Property(property: 'description', type: 'string', nullable: true, description: 'Role description'),
        new OA\Property(property: 'is_system', type: 'boolean', description: 'Whether this is a system role (cannot be deleted)'),
        new OA\Property(property: 'is_active', type: 'boolean', description: 'Whether the role is active'),
    ]
)]
class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_system',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function privileges(): BelongsToMany
    {
        return $this->belongsToMany(Privilege::class, 'role_privileges', 'role_id', 'privilege_id')
            ->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id')
            ->withTimestamps();
    }
}
