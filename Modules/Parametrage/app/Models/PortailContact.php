<?php

declare(strict_types=1);

namespace Modules\Parametrage\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PortailContact extends Model
{
    use HasUuids;

    protected $table = 'portail_contact';

    protected $keyType = 'string';

    public $incrementing = false;

    public const SINGLETON_ID = '00000000-0000-0000-0000-000000000003';

    protected $fillable = [
        'id',
        'adresse',
        'telephone',
        'email',
        'horaires_semaine',
        'horaires_samedi',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'instagram_url',
        'google_maps_url',
    ];
}
