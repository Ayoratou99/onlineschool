<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AninfPushService
{
    public function login(string $appClientId, string $appClientSecret): array
    {
        $baseUrl = rtrim(config('aninfpush.api.base_url'), '/');
        $url = "{$baseUrl}/api/v1/auth/login";

        $response = Http::acceptJson()->post($url, [
            'app_client_id' => $appClientId,
            'app_client_secret' => $appClientSecret,
        ]);

        if (!$response->successful()) {
            Log::warning('AninfPush login failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException(
                $response->json('message', 'AninfPush login failed')
            );
        }

        $data = $response->json('data', $response->json());
        return [
            'access_token' => $data['access_token'] ?? null,
            'refresh_token' => $data['refresh_token'] ?? null,
            'expires_in' => (int) ($data['expires_in'] ?? 3600),
            'refresh_expires_in' => (int) ($data['refresh_expires_in'] ?? 36000),
            'token_type' => $data['token_type'] ?? 'Bearer',
        ];
    }

    public function sendEmail(array $payload): void
    {
        $baseUrl = rtrim(config('aninfpush.api.base_url'), '/');
        if (empty($baseUrl)) {
            Log::info('AninfPush sendEmail skipped: no API base_url', ['payload' => $payload]);
            return;
        }

        $tokenData = $this->login(
            config('aninfpush.api.client_id'),
            config('aninfpush.api.client_secret')
        );

        $url = "{$baseUrl}/api/v1/messages/email";
        $response = Http::withToken($tokenData['access_token'])
            ->acceptJson()
            ->post($url, $payload);

        if (!$response->successful()) {
            Log::error('AninfPush sendEmail failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('AninfPush send email failed: ' . $response->body());
        }
    }
}
