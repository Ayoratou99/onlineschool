<?php

namespace Modules\Academique\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academique\Traits\BelongsToExternalAnneeAcademique;
use Modules\Academique\Traits\BelongsToExternalUser;

class Programme extends Model
{
    use BelongsToExternalAnneeAcademique, BelongsToExternalUser, HasUuids, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'niveau_id', 'annee_academique_id', 'version', 'is_active', 'valide_par', 'valide_le',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'version' => 'integer',
        'valide_le' => 'datetime',
    ];

    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveau::class, 'niveau_id');
    }

    public function programmeDetails(): HasMany
    {
        return $this->hasMany(ProgrammeDetail::class, 'programme_id');
    }
}
