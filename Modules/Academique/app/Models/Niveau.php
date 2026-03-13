<?php

namespace Modules\Academique\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academique\Traits\BelongsToExternalAnneeAcademique;

class Niveau extends Model
{
    use BelongsToExternalAnneeAcademique, HasUuids, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'filiere_id', 'parcours_id', 'annee_academique_id', 'code', 'libelle',
        'ordre', 'credits_requis', 'effectif_max', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ordre' => 'integer',
        'credits_requis' => 'integer',
        'effectif_max' => 'integer',
    ];

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'filiere_id');
    }

    public function parcours(): BelongsTo
    {
        return $this->belongsTo(Parcours::class, 'parcours_id');
    }

    public function groupes(): HasMany
    {
        return $this->hasMany(Groupe::class, 'niveau_id');
    }

    public function semestres(): HasMany
    {
        return $this->hasMany(Semestre::class, 'niveau_id');
    }

    public function programmes(): HasMany
    {
        return $this->hasMany(Programme::class, 'niveau_id');
    }
}
