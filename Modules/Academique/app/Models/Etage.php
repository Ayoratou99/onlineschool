<?php

namespace Modules\Academique\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Etage extends Model
{
    use HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['batiment_id', 'numero', 'libelle', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'numero' => 'integer'];

    public function batiment(): BelongsTo
    {
        return $this->belongsTo(Batiment::class, 'batiment_id');
    }

    public function salles(): HasMany
    {
        return $this->hasMany(Salle::class, 'etage_id');
    }
}
