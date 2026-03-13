<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Securite\Models\Role;
use Modules\Securite\Models\User;
use Modules\Tenant\Models\Admin;
use Modules\Tenant\Models\Tenant;
use Modules\Tenant\Services\TenantBucketService;

function createAdmin(): Admin
{
    return Admin::create([
        'id' => Str::uuid()->toString(),
        'name' => 'Super Admin',
        'email' => 'admin@central.local',
        'password' => Hash::make('password'),
        'state' => 'ACTIVE',
    ]);
}

function adminToken(): string
{
    $login = test()->postJson(route('api.tenant.admin.login'), [
        'email' => 'admin@central.local',
        'password' => 'password',
    ]);

    return $login->json('access_token');
}

test('admin can login with valid credentials', function () {
    createAdmin();

    $response = $this->postJson(route('api.tenant.admin.login'), [
        'email' => 'admin@central.local',
        'password' => 'password',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['access_token', 'token_type', 'expires_in', 'admin']);
    expect($response->json('access_token'))->not->toBeEmpty();
});

test('login fails with invalid credentials', function () {
    $response = $this->postJson(route('api.tenant.admin.login'), [
        'email' => 'admin@central.local',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(401);
});

test('create tenant and check bucket was created', function () {
    createAdmin();
    $token = adminToken();

    $response = $this->postJson(route('api.tenant.store'), [
        'id' => 'tenant-a',
        'data' => [],
        'domains' => ['tenant-a.test'],
    ], [
        'Authorization' => 'Bearer ' . $token,
    ]);

    $response->assertStatus(201);
    $response->assertJsonPath('data.id', 'tenant-a');
    $response->assertJsonPath('data.domains.0.domain', 'tenant-a.test');

    $tenant = Tenant::find('tenant-a');
    expect($tenant)->not->toBeNull();
    expect($tenant->bucket)->not->toBeNull();
    expect($tenant->bucket)->toBe(TenantBucketService::bucketNameForTenant($tenant));
});

test('update tenant', function () {
    createAdmin();
    $token = adminToken();

    $tenant = Tenant::create(['id' => 'tenant-a']);
    $tenant->domains()->create(['domain' => 'tenant-a.test']);

    $response = $this->putJson(route('api.tenant.update', ['tenant' => 'tenant-a']), [
        'data' => ['name' => 'Tenant A Updated'],
        'domains' => ['tenant-a.test', 'app-tenant-a.test'],
    ], [
        'Authorization' => 'Bearer ' . $token,
    ]);

    $response->assertStatus(200);
    $tenant->refresh();
    expect($tenant->name)->toBe('Tenant A Updated');
    expect($tenant->domains->pluck('domain')->toArray())
        ->toContain('tenant-a.test')
        ->toContain('app-tenant-a.test');
});

test('block tenant', function () {
    createAdmin();
    $token = adminToken();

    $tenant = Tenant::create(['id' => 'tenant-a']);
    $tenant->domains()->create(['domain' => 'tenant-a.test']);

    $response = $this->postJson(route('api.tenant.lock', ['tenant' => 'tenant-a']), [], [
        'Authorization' => 'Bearer ' . $token,
    ]);

    $response->assertStatus(200);
    $tenant->refresh();
    expect($tenant->locked)->toBeTrue();
});

test('add user in tenant a and get all users on tenant a domain returns only tenant a users', function () {
    createAdmin();
    $token = adminToken();

    $this->postJson(route('api.tenant.store'), [
        'id' => 'tenant-a',
        'data' => [],
        'domains' => ['tenant-a.test'],
    ], ['Authorization' => 'Bearer ' . $token]);

    Artisan::call('tenants:migrate', ['--tenants' => ['tenant-a']]);

    $tenantA = Tenant::find('tenant-a');
    tenancy()->initialize($tenantA);

    $userA = User::factory()->create([
        'email' => 'usera@tenant-a.test',
        'password' => Hash::make('password'),
    ]);
    $adminRole = Role::where('name', 'ADMIN')->first();
    $userA->roles()->attach($adminRole->id);
    tenancy()->end();

    $loginTenantA = $this->postJson('http://tenant-a.test/api/v1/auth/login', [
        'email' => 'usera@tenant-a.test',
        'password' => 'password',
    ]);
    $loginTenantA->assertStatus(200);
    $tenantAToken = $loginTenantA->json('access_token');

    $usersResponse = $this->getJson('http://tenant-a.test/api/v1/securite/user', [
        'Authorization' => 'Bearer ' . $tenantAToken,
    ]);
    $usersResponse->assertStatus(200);

    $data = $usersResponse->json('data');
    $ids = isset($data['data']) ? collect($data['data'])->pluck('id')->toArray() : collect($data)->pluck('id')->toArray();
    expect($ids)->toContain($userA->id);
});

test('tenant b domain does not expose tenant a users', function () {
    createAdmin();
    $token = adminToken();

    $this->postJson(route('api.tenant.store'), [
        'id' => 'tenant-a',
        'data' => [],
        'domains' => ['tenant-a.test'],
    ], ['Authorization' => 'Bearer ' . $token]);
    Artisan::call('tenants:migrate', ['--tenants' => ['tenant-a']]);

    $tenantA = Tenant::find('tenant-a');
    tenancy()->initialize($tenantA);
    $userA = User::factory()->create([
        'email' => 'usera@tenant-a.test',
        'password' => Hash::make('password'),
    ]);
    $adminRoleA = Role::where('name', 'ADMIN')->first();
    $userA->roles()->attach($adminRoleA->id);
    $userAId = $userA->id;
    tenancy()->end();

    $this->postJson(route('api.tenant.store'), [
        'id' => 'tenant-b',
        'data' => [],
        'domains' => ['tenant-b.test'],
    ], ['Authorization' => 'Bearer ' . $token]);
    Artisan::call('tenants:migrate', ['--tenants' => ['tenant-b']]);

    $tenantB = Tenant::find('tenant-b');
    tenancy()->initialize($tenantB);
    $userB = User::factory()->create([
        'email' => 'userb@tenant-b.test',
        'password' => Hash::make('password'),
    ]);
    $adminRoleB = Role::where('name', 'ADMIN')->first();
    $userB->roles()->attach($adminRoleB->id);
    tenancy()->end();

    $loginTenantB = $this->postJson('http://tenant-b.test/api/v1/auth/login', [
        'email' => 'userb@tenant-b.test',
        'password' => 'password',
    ]);
    $loginTenantB->assertStatus(200);
    $tenantBToken = $loginTenantB->json('access_token');

    $usersResponse = $this->getJson('http://tenant-b.test/api/v1/securite/user', [
        'Authorization' => 'Bearer ' . $tenantBToken,
    ]);
    $usersResponse->assertStatus(200);

    $data = $usersResponse->json('data');
    $ids = isset($data['data']) ? collect($data['data'])->pluck('id')->toArray() : collect($data)->pluck('id')->toArray();
    expect($ids)->not->toContain($userAId);
    expect($ids)->toContain($userB->id);
});
