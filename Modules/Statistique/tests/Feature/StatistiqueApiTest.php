<?php

use Modules\Securite\Models\User;

function authenticatedUser(): User
{
    return test()->runInTenantContext(function () {
        $userClass = config('statistique.entities.users');

        if ($userClass && class_exists($userClass)) {
            return $userClass::factory()->create();
        }

        return User::factory()->create();
    });
}

test('query requires authentication', function () {
    $response = $this->postJson('http://' . $this->tenantDomain . '/api/v1/statistique/query', [
        'entity' => 'users',
        'target_column' => '*',
        'operation' => 'count',
    ]);

    $response->assertStatus(401);
});

test('query requires entity', function () {
    $user = authenticatedUser();

    $response = $this->actingAs($user, 'api')->postJson('http://' . $this->tenantDomain . '/api/v1/statistique/query', [
        'target_column' => '*',
        'operation' => 'count',
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false)
        ->assertJsonPath('app_code', 'FUIP_422');
});

test('query rejects unknown entity', function () {
    $user = authenticatedUser();

    $response = $this->actingAs($user, 'api')->postJson('http://' . $this->tenantDomain . '/api/v1/statistique/query', [
        'entity' => 'nonexistent_entity',
        'target_column' => '*',
        'operation' => 'count',
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

test('query rejects invalid operation', function () {
    $user = authenticatedUser();

    $response = $this->actingAs($user, 'api')->postJson('http://' . $this->tenantDomain . '/api/v1/statistique/query', [
        'entity' => 'users',
        'target_column' => '*',
        'operation' => 'median',
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

test('query rejects invalid period interval', function () {
    $user = authenticatedUser();

    $response = $this->actingAs($user, 'api')->postJson('http://' . $this->tenantDomain . '/api/v1/statistique/query', [
        'entity' => 'users',
        'target_column' => '*',
        'operation' => 'count',
        'period_group_by' => 'decade',
    ]);

    $response->assertStatus(422);
});

test('query validates period end after start', function () {
    $user = authenticatedUser();

    $response = $this->actingAs($user, 'api')->postJson('http://' . $this->tenantDomain . '/api/v1/statistique/query', [
        'entity' => 'users',
        'target_column' => '*',
        'operation' => 'count',
        'with_period' => true,
        'period' => [
            'start' => '2025-12-31',
            'end' => '2025-01-01',
        ],
    ]);

    $response->assertStatus(422);
});

test('query rejects nonexistent target column', function () {
    $user = authenticatedUser();

    $response = $this->actingAs($user, 'api')->postJson('http://' . $this->tenantDomain . '/api/v1/statistique/query', [
        'entity' => 'roles',
        'target_column' => 'nonexistent_column',
        'operation' => 'sum',
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false)
        ->assertJsonStructure(['errors' => ['details']]);
});

test('query rejects nonexistent relation in group by', function () {
    $user = authenticatedUser();

    $response = $this->actingAs($user, 'api')->postJson('http://' . $this->tenantDomain . '/api/v1/statistique/query', [
        'entity' => 'users',
        'target_column' => '*',
        'operation' => 'count',
        'group_by' => ['fakeRelation.name'],
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

test('query rejects nonexistent filter field', function () {
    $user = authenticatedUser();

    $response = $this->actingAs($user, 'api')->postJson('http://' . $this->tenantDomain . '/api/v1/statistique/query', [
        'entity' => 'roles',
        'target_column' => '*',
        'operation' => 'count',
        'filters' => ['ghost_column' => 'value'],
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

test('count roles returns success', function () {
    $user = authenticatedUser();

    $response = $this->actingAs($user, 'api')->postJson('http://' . $this->tenantDomain . '/api/v1/statistique/query', [
        'entity' => 'roles',
        'target_column' => '*',
        'operation' => 'count',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('app_code', 'FUIP_100')
        ->assertJsonStructure([
            'success',
            'app_code',
            'message',
            'data',
            'meta' => ['entity', 'operation', 'target_column', 'executed_at'],
            'from_cache',
        ]);
});

test('count with filter returns success', function () {
    $user = authenticatedUser();

    $response = $this->actingAs($user, 'api')->postJson('http://' . $this->tenantDomain . '/api/v1/statistique/query', [
        'entity' => 'roles',
        'target_column' => '*',
        'operation' => 'count',
        'filters' => ['state' => 'ACTIVE'],
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true);
});

test('count with group by returns success', function () {
    $user = authenticatedUser();

    $response = $this->actingAs($user, 'api')->postJson('http://' . $this->tenantDomain . '/api/v1/statistique/query', [
        'entity' => 'roles',
        'target_column' => '*',
        'operation' => 'count',
        'group_by' => ['state'],
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true);
});

test('no cache flag bypasses cache', function () {
    $user = authenticatedUser();

    $response = $this->actingAs($user, 'api')->postJson('http://' . $this->tenantDomain . '/api/v1/statistique/query', [
        'entity' => 'roles',
        'target_column' => '*',
        'operation' => 'count',
        'no_cache' => true,
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('from_cache', false);
});

test('clear cache requires authentication', function () {
    $response = $this->deleteJson('http://' . $this->tenantDomain . '/api/v1/statistique/cache');

    $response->assertStatus(401);
});

test('clear cache returns success', function () {
    $user = authenticatedUser();

    $response = $this->actingAs($user, 'api')->deleteJson('http://' . $this->tenantDomain . '/api/v1/statistique/cache');

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('app_code', 'FUIP_200');
});
