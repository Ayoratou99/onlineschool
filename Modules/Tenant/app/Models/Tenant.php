<?php

namespace Modules\Tenant\Models;

use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    /**
     * VirtualColumn stores all non-custom attributes (like 'locked') as
     * top-level model attributes decoded from the JSON 'data' column.
     * Reading from $this->attributes avoids the null that VirtualColumn
     * sets on the raw 'data' key after decoding.
     */
    public function getLockedAttribute(): bool
    {
        return (bool) ($this->attributes['locked'] ?? false);
    }
}
