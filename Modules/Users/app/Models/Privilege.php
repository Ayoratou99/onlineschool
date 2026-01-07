<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Privilege',
    title: 'Privilege',
    description: 'Privilege model representing a permission/action',
    required: ['id', 'name', 'slug', 'is_active'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', description: 'Privilege unique identifier'),
        new OA\Property(property: 'name', type: 'string', description: 'Privilege display name'),
        new OA\Property(property: 'slug', type: 'string', description: 'Unique slug identifier'),
        new OA\Property(property: 'description', type: 'string', nullable: true, description: 'Privilege description'),
        new OA\Property(property: 'resource', type: 'string', nullable: true, description: 'Resource/Module this privilege belongs to'),
        new OA\Property(property: 'action', type: 'string', nullable: true, description: 'Action: create, read, update, delete, etc.'),
        new OA\Property(property: 'is_active', type: 'boolean', description: 'Whether the privilege is active'),
    ]
)]
class Privilege extends Model
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
        'resource',
        'action',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_privileges', 'privilege_id', 'role_id')
            ->withTimestamps();
    }
}
