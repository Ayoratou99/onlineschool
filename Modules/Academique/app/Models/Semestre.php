<?php

namespace Modules\Academique\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academique\Traits\BelongsToExternalAnneeAcademique;

class Semestre extends Model
{
    use BelongsToExternalAnneeAcademique, HasUuids, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'niveau_id', 'annee_academique_id', 'code', 'libelle', 'type', 'ordre',
        'date_debut', 'date_fin', 'date_debut_examen', 'date_fin_examen', 'is_locked',
    ];

    protected $casts = [
        'ordre' => 'integer',
        'is_locked' => 'boolean',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_debut_examen' => 'date',
        'date_fin_examen' => 'date',
    ];

    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveau::class, 'niveau_id');
    }

    public function unitesEnseignement(): HasMany
    {
        return $this->hasMany(UniteEnseignement::class, 'semestre_id');
    }
}
