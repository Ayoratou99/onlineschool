<?php

namespace Modules\Academique\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matiere extends Model
{
    use HasUuids, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'ue_id', 'code', 'libelle', 'credits', 'coefficient',
        'vh_cm', 'vh_td', 'vh_tp', 'est_compensable', 'note_eliminatoire', 'is_active',
    ];

    protected $casts = [
        'credits' => 'decimal:2',
        'coefficient' => 'decimal:2',
        'note_eliminatoire' => 'decimal:2',
        'est_compensable' => 'boolean',
        'is_active' => 'boolean',
        'vh_cm' => 'integer',
        'vh_td' => 'integer',
        'vh_tp' => 'integer',
    ];

    public function ue(): BelongsTo
    {
        return $this->belongsTo(UniteEnseignement::class, 'ue_id');
    }

    public function programmeDetails(): HasMany
    {
        return $this->hasMany(ProgrammeDetail::class, 'matiere_id');
    }
}
