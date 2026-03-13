<?php

namespace Modules\Academique\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batiment extends Model
{
    use HasUuids, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['etablissement_id', 'code', 'libelle', 'adresse', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class, 'etablissement_id');
    }

    public function etages(): HasMany
    {
        return $this->hasMany(Etage::class, 'batiment_id');
    }

    public function salles(): HasMany
    {
        return $this->hasMany(Salle::class, 'batiment_id');
    }
}
