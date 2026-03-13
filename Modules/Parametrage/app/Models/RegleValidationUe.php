<?php

namespace Modules\Parametrage\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Parametrage\Traits\BelongsToExternalUe;

class RegleValidationUe extends Model
{
    use BelongsToExternalUe, HasUuids, LogsActivity, SoftDeletes;

    protected $table = 'regles_validation_ue';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'ue_id',
        'annee_academique_id',
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
