<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RolePrivilege',
    title: 'RolePrivilege',
    description: 'Pivot model for role-privilege relationship',
    required: ['id', 'role_id', 'privilege_id'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', description: 'RolePrivilege unique identifier'),
        new OA\Property(property: 'role_id', type: 'string', format: 'uuid', description: 'Role identifier'),
        new OA\Property(property: 'privilege_id', type: 'string', format: 'uuid', description: 'Privilege identifier'),
    ]
)]
class RolePrivilege extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'role_privileges';

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
        'role_id',
        'privilege_id',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function privilege(): BelongsTo
    {
        return $this->belongsTo(Privilege::class);
    }
}
