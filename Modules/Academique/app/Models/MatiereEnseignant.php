<?php

namespace Modules\Academique\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Academique\Traits\BelongsToExternalAnneeAcademique;
use Modules\Academique\Traits\BelongsToExternalUser;

class MatiereEnseignant extends Model
{
    use BelongsToExternalAnneeAcademique, BelongsToExternalUser, HasUuids;

    protected $table = 'matiere_enseignant';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'matiere_id', 'enseignant_id', 'annee_academique_id', 'groupe_id',
        'type_seance', 'is_principal',
    ];

    protected $casts = ['is_principal' => 'boolean'];

    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }

    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class, 'groupe_id');
    }
}
