<?php

namespace Modules\Academique\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cycle extends Model
{
    use HasUuids, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'code', 'libelle', 'niveau_bac_requis', 'duree_annees', 'credits_total', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duree_annees' => 'integer',
        'credits_total' => 'integer',
    ];

    public function filieres(): HasMany
    {
        return $this->hasMany(Filiere::class, 'cycle_id');
    }
}
