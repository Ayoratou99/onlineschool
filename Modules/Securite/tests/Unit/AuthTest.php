<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Modules\Securite\Models\User;

test('login with valid credentials', function () {
    $user = $this->runInTenantContext(fn () => User::factory()->create());

    $response = $this->postJson($this->tenantUrl('api/v1/auth/login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200);
});

test('login with invalid credentials', function () {
    $response = $this->postJson($this->tenantUrl('api/v1/auth/login'), [
        'email' => 'invalid@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(401);
});

test('reset password with valid email', function () {
    $user = $this->runInTenantContext(fn () => User::factory()->create());

    $response = $this->postJson($this->tenantUrl('api/v1/auth/reset-password'), [
        'email' => $user->email,
    ]);

    $response->assertStatus(200);
});

test('reset password with invalid email', function () {
    $response = $this->postJson($this->tenantUrl('api/v1/auth/reset-password'), [
        'email' => 'invalid@example.com',
    ]);

    $response->assertStatus(422);
});

test('reset password with valid email and confirm email', function () {
    $user = $this->runInTenantContext(fn () => User::factory()->create());

    $resetResponse = $this->postJson($this->tenantUrl('api/v1/auth/reset-password'), [
        'email' => $user->email,
    ]);
    $resetResponse->assertStatus(200);

    $fixedToken = str_repeat('x', 64);
    Cache::put('email_verify_' . $fixedToken, $user->id, now()->addMinutes(60));

    $response = $this->postJson($this->tenantUrl('api/v1/auth/confirm-email'), [
        'email' => $user->email,
        'token' => $fixedToken,
        'password' => 'newpassword',
        'password_confirmation' => 'newpassword',
    ]);
    $response->assertStatus(200);

    $loginResponse = $this->postJson($this->tenantUrl('api/v1/auth/login'), [
        'email' => $user->email,
        'password' => 'newpassword',
    ]);
    $loginResponse->assertStatus(200);
});

test('create user with 2fa and connect with code', function () {
    $user = $this->runInTenantContext(fn () => User::factory()->create([
        'password' => Hash::make('password'),
        'two_factor_enabled' => true,
    ]));

    $loginResponse = $this->postJson($this->tenantUrl('api/v1/auth/login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);
    $loginResponse->assertStatus(200);
    $loginResponse->assertJsonPath('data.requires_2fa', true);
    $temp2faToken = $loginResponse->json('data.temp_2fa_token');
    expect($temp2faToken)->not->toBeEmpty();

    $payload = Cache::get('2fa_temp_' . $temp2faToken);
    expect($payload)->toBeArray()->toHaveKey('code');
    $code = $payload['code'];

    $verifyResponse = $this->postJson($this->tenantUrl('api/v1/auth/2fa/verify'), [
        'temp_2fa_token' => $temp2faToken,
        'otp' => $code,
    ]);
    $verifyResponse->assertStatus(200);
    $verifyResponse->assertJsonPath('success', true);
    expect($verifyResponse->json('access_token'))->not->toBeEmpty();
});
