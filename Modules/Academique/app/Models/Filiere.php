<?php

namespace Modules\Academique\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academique\Traits\BelongsToExternalUser;

class Filiere extends Model
{
    use BelongsToExternalUser, HasUuids, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'cycle_id', 'domaine_id', 'responsable_id', 'code', 'libelle', 'description', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class, 'cycle_id');
    }

    public function domaine(): BelongsTo
    {
        return $this->belongsTo(Domaine::class, 'domaine_id');
    }

    public function parcours(): HasMany
    {
        return $this->hasMany(Parcours::class, 'filiere_id');
    }

    public function niveaux(): HasMany
    {
        return $this->hasMany(Niveau::class, 'filiere_id');
    }
}
