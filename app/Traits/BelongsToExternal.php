<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trait BelongsToExternal
 *
 * Permet de résoudre des relations vers des modèles
 * situés dans d'autres modules sans créer de dépendance forte.
 * Pas de contrainte FK en base pour ces relations — juste un champ UUID indexé.
 */
trait BelongsToExternal
{
    /**
     * Crée une relation "belongs to" vers un modèle externe.
     * Utilisé pour profiter du lazy loading d'Eloquent
     * sans contrainte FK en base.
     *
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $modelClass
     * @param  string  $foreignKey  Nom de la colonne UUID
     * @return BelongsTo
     */
    protected function externalBelongsTo(string $modelClass, string $foreignKey): BelongsTo
    {
        return $this->belongsTo($modelClass, $foreignKey, 'id');
    }
}
