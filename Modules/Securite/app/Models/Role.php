<?php

namespace Modules\Securite\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Role",
    title: "Role Model",
    description: "Représentation d'un rôle dans le système",
    required: ["id", "name", "state"],
    properties: [
        new OA\Property(property: "id", type: "string", format: "uuid"),
        new OA\Property(property: "name", type: "string"),
        new OA\Property(property: "description", type: "string", nullable: true),
        new OA\Property(property: "state", type: "string"),
    ],
    additionalProperties: false
)]

class Role extends Model
{
    use HasFactory, LogsActivity, SoftDeletes, HasUuids;

    /**
     * Set the key type to string for UUID.
     */
    protected $keyType = 'string';

    /**
     * Disable auto-incrementing.
     */
    public $incrementing = false;

    protected $fillable = ['name', 'description', 'state'];

    protected $hidden = ['deleted_at'];

    protected $casts = [
        'state' => 'string',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
}
