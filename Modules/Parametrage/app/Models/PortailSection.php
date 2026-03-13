<?php

declare(strict_types=1);

namespace Modules\Parametrage\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortailSection extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'portail_sections';

    protected $keyType = 'string';

    public $incrementing = false;

    public const TYPE_TEXTE = 'texte';
    public const TYPE_IMAGE = 'image';
    public const TYPE_GALERIE = 'galerie';
    public const TYPE_STATS = 'stats';
    public const TYPE_COLONNES = 'colonnes';
    public const TYPE_ACTUALITES = 'actualites';
    public const TYPE_CONTACT = 'contact';

    public const TYPES = [
        self::TYPE_TEXTE,
        self::TYPE_IMAGE,
        self::TYPE_GALERIE,
        self::TYPE_STATS,
        self::TYPE_COLONNES,
        self::TYPE_ACTUALITES,
        self::TYPE_CONTACT,
    ];

    protected $fillable = [
        'type',
        'titre',
        'contenu',
        'ordre',
        'is_active',
        'bg_couleur',
    ];

    protected $casts = [
        'ordre' => 'integer',
        'is_active' => 'boolean',
        'contenu' => 'array',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('ordre', fn ($q) => $q->orderBy('ordre'));
    }
}
