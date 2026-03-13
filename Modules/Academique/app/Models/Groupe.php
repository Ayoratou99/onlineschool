<?php

namespace Modules\Academique\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academique\Traits\BelongsToExternalAnneeAcademique;

class Groupe extends Model
{
    use BelongsToExternalAnneeAcademique, HasUuids, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'niveau_id', 'annee_academique_id', 'code', 'libelle', 'type', 'effectif_max', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'effectif_max' => 'integer',
    ];

    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveau::class, 'niveau_id');
    }
}
