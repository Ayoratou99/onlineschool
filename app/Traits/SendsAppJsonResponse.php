<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait SendsAppJsonResponse
{
    protected array $appCodes = [];

    protected function loadAppCodes(): void
    {
        $path = base_path('app_code_responses.json');

        if (!file_exists($path)) {
            \Log::warning('app_code_responses.json not found at: ' . $path);
            $this->appCodes = [];
            return;
        }

        $content = file_get_contents($path);
        $codes = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error('Invalid JSON in app_code_responses.json: ' . json_last_error_msg());
            $this->appCodes = [];
            return;
        }

        $this->appCodes = $codes ?? [];
    }

    protected function sendResponse(mixed $data, string $appCode, int $httpCode = 200): JsonResponse
    {
        return response()->json([
            'success'  => true,
            'app_code' => $appCode,
            'message'  => $this->appCodes[$appCode] ?? 'This action not reported yet',
            'data'     => $data,
        ], $httpCode);
    }

    protected function sendError(string $appCode, array $errors = [], int $httpCode = 404): JsonResponse
    {
        return response()->json([
            'success'  => false,
            'app_code' => $appCode,
            'message'  => $this->appCodes[$appCode] ?? 'This action not reported yet',
            'errors'   => $errors,
        ], $httpCode);
    }
}
