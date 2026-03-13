<?php

namespace Modules\Academique\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UniteEnseignement extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'unites_enseignement';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'semestre_id', 'code', 'libelle', 'type', 'credits', 'coefficient',
        'est_capitalisable', 'est_compensable', 'note_minimale', 'is_active',
    ];

    protected $casts = [
        'credits' => 'decimal:2',
        'coefficient' => 'decimal:2',
        'note_minimale' => 'decimal:2',
        'est_capitalisable' => 'boolean',
        'est_compensable' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class, 'semestre_id');
    }

    public function matieres(): HasMany
    {
        return $this->hasMany(Matiere::class, 'ue_id');
    }
}
