<?php

namespace Modules\Academique\Services;

use App\Contracts\UeResolverInterface;
use Modules\Academique\Models\UniteEnseignement;

class UeResolver implements UeResolverInterface
{
    public function getUe(string $id): ?object
    {
        return UniteEnseignement::find($id);
    }
}
