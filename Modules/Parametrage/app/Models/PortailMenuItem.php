<?php

declare(strict_types=1);

namespace Modules\Parametrage\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortailMenuItem extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'portail_menu_items';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'label',
        'url',
        'type',
        'target',
        'build_in_page',
        'ordre',
        'is_active',
    ];

    protected $casts = [
        'ordre' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('ordre', fn ($q) => $q->orderBy('ordre'));
    }
}
