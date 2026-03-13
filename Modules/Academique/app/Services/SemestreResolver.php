<?php

namespace Modules\Academique\Services;

use App\Contracts\SemestreResolverInterface;
use Modules\Academique\Models\Semestre;

class SemestreResolver implements SemestreResolverInterface
{
    public function getSemestre(string $id): ?object
    {
        return Semestre::find($id);
    }
}
