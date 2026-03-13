<?php

declare(strict_types=1);

namespace Modules\Parametrage\Services;

use Modules\Parametrage\Models\PortailContact;

class PortailContactService
{
    public function get(): ?PortailContact
    {
        return PortailContact::find(PortailContact::SINGLETON_ID);
    }

    public function update(array $data): PortailContact
    {
        $contact = PortailContact::find(PortailContact::SINGLETON_ID);
        if (! $contact) {
            $contact = PortailContact::create(array_merge($data, ['id' => PortailContact::SINGLETON_ID]));
        } else {
            $contact->update($data);
        }
        return $contact->fresh();
    }
}
