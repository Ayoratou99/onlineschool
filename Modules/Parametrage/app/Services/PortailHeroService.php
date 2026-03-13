<?php

declare(strict_types=1);

namespace Modules\Parametrage\Services;

use Modules\Parametrage\Models\PortailHero;

class PortailHeroService
{
    public function get(): ?PortailHero
    {
        return PortailHero::find(PortailHero::SINGLETON_ID);
    }

    public function update(array $data): PortailHero
    {
        $hero = PortailHero::find(PortailHero::SINGLETON_ID);
        if (! $hero) {
            $hero = PortailHero::create(array_merge($data, ['id' => PortailHero::SINGLETON_ID]));
        } else {
            $hero->update($data);
        }
        return $hero->fresh();
    }
}
