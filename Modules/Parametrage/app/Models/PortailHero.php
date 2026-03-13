<?php

declare(strict_types=1);

namespace Modules\Parametrage\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortailHero extends Model
{
    protected $table = 'portail_hero';

    protected $keyType = 'string';

    public $incrementing = false;

    public const SINGLETON_ID = '00000000-0000-0000-0000-000000000002';

    protected $fillable = [
        'id',
        'image_url',
        'badge_texte',
        'titre',
        'sous_titre',
        'bouton_principal',
        'bouton_secondaire',
    ];
}
