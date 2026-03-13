<?php

namespace Modules\Academique\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Academique\Traits\BelongsToExternalUser;

class EmploiDuTempsException extends Model
{
    use BelongsToExternalUser, HasUuids;

    protected $table = 'emploi_du_temps_exceptions';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'emploi_du_temps_id', 'date_concernee', 'type',
        'nouvelle_salle_id', 'nouvel_enseignant_id', 'nouvelle_heure_debut', 'nouvelle_heure_fin',
        'motif', 'created_by',
    ];

    protected $casts = [
        'date_concernee' => 'date',
        'nouvelle_heure_debut' => 'datetime:H:i',
        'nouvelle_heure_fin' => 'datetime:H:i',
    ];

    public function emploiDuTemps(): BelongsTo
    {
        return $this->belongsTo(EmploiDuTemps::class, 'emploi_du_temps_id');
    }

    public function nouvelleSalle(): BelongsTo
    {
        return $this->belongsTo(Salle::class, 'nouvelle_salle_id');
    }
}
