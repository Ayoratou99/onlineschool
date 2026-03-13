<?php

namespace Modules\Academique\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Etablissement extends Model
{
    use HasUuids, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['code', 'libelle', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function batiments(): HasMany
    {
        return $this->hasMany(Batiment::class, 'etablissement_id');
    }
}
