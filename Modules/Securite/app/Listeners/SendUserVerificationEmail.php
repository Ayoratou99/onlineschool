<?php

namespace Modules\Securite\Listeners;

use App\Jobs\SendAninfPushEmailJob;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Securite\Events\UserCreated;

class SendUserVerificationEmail
{
    public function handle(UserCreated $event): void
    {
        $user = $event->user;
        $token = Str::random(64);
        $ttl = config('aninfpush.email_verification.token_ttl_minutes', 60 * 24);
        Cache::put('email_verify_' . $token, $user->id, now()->addMinutes($ttl));

        $frontendUrl = rtrim(config('aninfpush.email_verification.frontend_url'), '/');
        $url = $frontendUrl
            ? $frontendUrl . '?token=' . urlencode($token) . '&email=' . urlencode($user->email)
            : '';

        $payload = [
            'recipient_email' => $user->email,
            'template_identifier' => config('aninfpush.email_verification.template_identifier', 'email-verification'),
            'from_email' => config('mail.from.address'),
            'variables' => [
                'name' => trim($user->nom . ' ' . ($user->prenom ?? '')),
                'url' => $url
            ],
        ];

        try {
            SendAninfPushEmailJob::dispatch($payload);
        } catch (\Throwable $e) {
            Log::warning('SendUserVerificationEmail: failed to dispatch job', ['user_id' => $user->id, 'error' => $e->getMessage()]);
        }
    }
}
