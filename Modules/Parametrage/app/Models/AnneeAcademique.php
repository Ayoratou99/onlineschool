<?php

namespace Modules\Parametrage\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Parametrage\Models\BaremeMention;
use Modules\Parametrage\Traits\BelongsToExternalUser;

class AnneeAcademique extends Model
{
    use BelongsToExternalUser, HasUuids, LogsActivity, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'code',
        'libelle',
        'date_debut',
        'date_fin',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'is_active' => 'boolean',
    ];

    public function baremesMention(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BaremeMention::class, 'annee_academique_id');
    }

    public function reglesValidation(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RegleValidation::class, 'annee_academique_id');
    }
}
