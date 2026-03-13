<?php

namespace Modules\Academique\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salle extends Model
{
    use HasUuids, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'batiment_id', 'etage_id', 'code', 'libelle', 'type', 'capacite',
        'has_projecteur', 'has_climatisation', 'has_tableau_blanc', 'has_internet', 'is_active',
    ];

    protected $casts = [
        'capacite' => 'integer',
        'has_projecteur' => 'boolean',
        'has_climatisation' => 'boolean',
        'has_tableau_blanc' => 'boolean',
        'has_internet' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function batiment(): BelongsTo
    {
        return $this->belongsTo(Batiment::class, 'batiment_id');
    }

    public function etage(): BelongsTo
    {
        return $this->belongsTo(Etage::class, 'etage_id');
    }

    public function indisponibilites(): HasMany
    {
        return $this->hasMany(SalleIndisponibilite::class, 'salle_id');
    }
}
