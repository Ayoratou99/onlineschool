<?php

namespace Modules\Parametrage\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaremeMention extends Model
{
    use HasUuids, SoftDeletes, LogsActivity;

    protected $table = 'baremes_mention';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'annee_academique_id',
        'mention',
        'bareme_min',
        'bareme_max',
        'ordre',
    ];

    protected $casts = [
        'bareme_min' => 'decimal:2',
        'bareme_max' => 'decimal:2',
        'ordre' => 'integer',
    ];

    public function anneeAcademique(): BelongsTo
    {
        return $this->belongsTo(AnneeAcademique::class, 'annee_academique_id');
    }
}
