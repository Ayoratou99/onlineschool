<?php

namespace Modules\Academique\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academique\Traits\BelongsToExternalAnneeAcademique;
use Modules\Academique\Traits\BelongsToExternalUser;

class EmploiDuTemps extends Model
{
    use BelongsToExternalAnneeAcademique, BelongsToExternalUser, HasUuids, SoftDeletes;

    protected $table = 'emplois_du_temps';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'semestre_id', 'niveau_id', 'groupe_id', 'matiere_id', 'salle_id',
        'enseignant_id', 'annee_academique_id', 'type_seance', 'jour',
        'heure_debut', 'heure_fin', 'frequence', 'date_specifique',
        'date_debut_effectif', 'date_fin_effectif', 'is_active',
    ];

    protected $casts = [
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'date_specifique' => 'date',
        'date_debut_effectif' => 'date',
        'date_fin_effectif' => 'date',
        'is_active' => 'boolean',
    ];

    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class, 'semestre_id');
    }

    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveau::class, 'niveau_id');
    }

    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class, 'groupe_id');
    }

    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }

    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class, 'salle_id');
    }

    public function exceptions(): HasMany
    {
        return $this->hasMany(EmploiDuTempsException::class, 'emploi_du_temps_id');
    }
}
