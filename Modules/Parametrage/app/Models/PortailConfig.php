<?php

declare(strict_types=1);

namespace Modules\Parametrage\Models;

use Illuminate\Database\Eloquent\Model;

class PortailConfig extends Model
{
    protected $table = 'portail_config';

    protected $keyType = 'string';

    public $incrementing = false;

    /** Singleton ID: single row per tenant. */
    public const SINGLETON_ID = '00000000-0000-0000-0000-000000000001';

    protected $fillable = [
        'id',
        'nom_etablissement',
        'slogan',
        'logo_url',
        'favicon_url',
        'couleur_primaire',
        'couleur_secondaire',
        'couleur_texte',
    ];

    protected $attributes = [
        'couleur_primaire' => '#0B3D6E',
        'couleur_secondaire' => '#C8A84B',
        'couleur_texte' => '#18182E',
    ];
}
