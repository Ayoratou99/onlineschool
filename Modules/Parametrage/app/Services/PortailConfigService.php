<?php

declare(strict_types=1);

namespace Modules\Parametrage\Services;

use Modules\Parametrage\Models\PortailConfig;

class PortailConfigService
{
    public function get(): ?PortailConfig
    {
        return PortailConfig::find(PortailConfig::SINGLETON_ID);
    }

    public function update(array $data): PortailConfig
    {
        $config = PortailConfig::find(PortailConfig::SINGLETON_ID);
        if (! $config) {
            $config = PortailConfig::create(array_merge($data, ['id' => PortailConfig::SINGLETON_ID]));
        } else {
            $config->update($data);
        }
        return $config->fresh();
    }
}
