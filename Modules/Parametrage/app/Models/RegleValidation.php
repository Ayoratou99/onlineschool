<?php

namespace Modules\Parametrage\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Parametrage\Traits\BelongsToExternalNiveau;
use Modules\Parametrage\Traits\BelongsToExternalSemestre;

class RegleValidation extends Model
{
    use BelongsToExternalNiveau, BelongsToExternalSemestre, HasUuids, LogsActivity, SoftDeletes;

    protected $table = 'regles_validation';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'annee_academique_id',
        'niveau_id',
        'semestre_id',
        'type_regle',
        'config',
    ];

    protected $casts = [
        'config' => 'array',
    ];

    public function anneeAcademique(): BelongsTo
    {
        return $this->belongsTo(AnneeAcademique::class, 'annee_academique_id');
    }
}
