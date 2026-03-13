<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

// Uses the Tenant module's TestCase which drops all tenant databases in setUp/tearDown.
uses(Modules\Tenant\Tests\TestCase::class, RefreshDatabase::class)
    ->beforeEach(fn () => config(['app.url' => 'http://localhost']))
    ->in('Feature');
