<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserRole',
    title: 'UserRole',
    description: 'Pivot model for user-role relationship',
    required: ['id', 'user_id', 'role_id'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', description: 'UserRole unique identifier'),
        new OA\Property(property: 'user_id', type: 'string', format: 'uuid', description: 'User identifier'),
        new OA\Property(property: 'role_id', type: 'string', format: 'uuid', description: 'Role identifier'),
    ]
)]
class UserRole extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'user_roles';

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
        'user_id',
        'role_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
