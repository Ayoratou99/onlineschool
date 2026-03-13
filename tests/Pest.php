<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\TenantTestHelpers;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

uses(Tests\TestCase::class, RefreshDatabase::class)->in('Feature', 'Unit');

// Tenant-scoped module tests: create a tenant DB, migrate, and clean up automatically.
uses(Tests\TestCase::class, RefreshDatabase::class, TenantTestHelpers::class)
    ->beforeEach(fn () => $this->initTenantForTesting())
    ->afterEach(fn () => $this->cleanUpTenantForTesting())
    ->in(
        '../Modules/Securite/tests/Unit',
        '../Modules/Statistique/tests/Feature',
        '../Modules/Workflow/tests/Unit',
        '../Modules/Workflow/tests/Feature',
    );

// Tenant module Feature tests: uses its own TestCase for aggressive DB cleanup.
uses(Modules\Tenant\Tests\TestCase::class, RefreshDatabase::class)
    ->beforeEach(fn () => config(['app.url' => 'http://localhost']))
    ->in('../Modules/Tenant/tests/Feature');
