<?php

namespace Modules\Academique\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Academique\Traits\BelongsToExternalUser;

class SalleIndisponibilite extends Model
{
    use BelongsToExternalUser, HasUuids;

    protected $table = 'salle_indisponibilites';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['salle_id', 'date_debut', 'date_fin', 'motif', 'created_by'];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class, 'salle_id');
    }
}
