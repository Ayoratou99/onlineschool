<?php

declare(strict_types=1);

namespace Modules\Parametrage\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortailActualite extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'portail_actualites';

    protected $keyType = 'string';

    public $incrementing = false;

    public const CATEGORIE_INFO = 'info';
    public const CATEGORIE_URGENT = 'urgent';
    public const CATEGORIE_EVENEMENT = 'evenement';
    public const CATEGORIE_RESULTAT = 'resultat';

    public const CIBLAGE_TOUS = 'tous';
    public const CIBLAGE_ETUDIANTS = 'etudiants';
    public const CIBLAGE_STAFF = 'staff';

    protected $fillable = [
        'auteur_id',
        'titre',
        'contenu',
        'image_url',
        'categorie',
        'ciblage',
        'is_epingle',
        'is_active',
        'publie_le',
    ];

    protected $casts = [
        'is_epingle' => 'boolean',
        'is_active' => 'boolean',
        'publie_le' => 'datetime',
    ];

}
