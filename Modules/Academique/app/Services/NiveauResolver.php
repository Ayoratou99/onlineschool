<?php

namespace Modules\Academique\Services;

use App\Contracts\NiveauResolverInterface;
use Modules\Academique\Models\Niveau;

class NiveauResolver implements NiveauResolverInterface
{
    public function getNiveau(string $id): ?object
    {
        return Niveau::find($id);
    }
}
