<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            $centralDomains = config('tenancy.central_domains', ['127.0.0.1', 'localhost']);
            $statusesFile = base_path('modules_statuses.json');
            $enabledModules = $statusesFile && file_exists($statusesFile)
                ? array_keys(array_filter((array) json_decode(file_get_contents($statusesFile), true)))
                : [];

            // Central domains only: Tenant module (admin login, tenant CRUD, clean). Not accessible on tenant domains.
            foreach ($centralDomains as $domain) {
                Route::domain($domain)->group(function () use ($enabledModules) {
                    Route::middleware('web')->group(base_path('routes/web.php'));
                    Route::middleware('api')->prefix('api')->name('api.')->group(function () use ($enabledModules) {
                        $apiFile = base_path('routes/api.php');
                        if (file_exists($apiFile)) {
                            require $apiFile;
                        }
                        foreach ($enabledModules as $moduleName) {
                            if ($moduleName !== 'Tenant') {
                                continue;
                            }
                            $path = base_path("Modules/{$moduleName}/routes/api.php");
                            if (file_exists($path)) {
                                require $path;
                            }
                        }
                    });
                });
            }

            // Tenant domains only: web + Securite, Document, etc. Not Tenant module.
            // InitializeTenancyByDomain resolves the tenant from the request domain and bootstraps tenancy.
            // tenant.domain blocks central domains. tenant.not_locked blocks locked tenants.
            Route::middleware([
                \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
                'tenant.domain',
            ])->group(function () use ($enabledModules) {
                Route::middleware('web')->group(base_path('routes/web.php'));
                Route::middleware(['api', 'tenant.not_locked'])->prefix('api')->name('api.')->group(function () use ($enabledModules) {
                    $apiFile = base_path('routes/api.php');
                    if (file_exists($apiFile)) {
                        require $apiFile;
                    }
                    foreach ($enabledModules as $moduleName) {
                        if ($moduleName === 'Tenant') {
                            continue;
                        }
                        $path = base_path("Modules/{$moduleName}/routes/api.php");
                        if (file_exists($path)) {
                            require $path;
                        }
                    }
                });
            });
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'tenant.domain' => \App\Http\Middleware\EnsureTenantDomain::class,
            'tenant.not_locked' => \App\Http\Middleware\EnsureTenantNotLocked::class,
        ]);
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return null;
            }
            return route('auth.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $getAppMessage = function (string $code) {
            $path = base_path('app_code_responses.json');
            if (!File::exists($path)) {
                \Log::error('app_code_responses.json not found at: ' . $path);
                return 'An error occurred';
            }
            $content = File::get($path);
            $codes = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::error('Invalid JSON in app_code_responses.json: ' . json_last_error_msg());
                return 'An error occurred';
            }
            return $codes[$code] ?? 'An error occurred';
        };

        if (class_exists(\Modules\Users\Exceptions\UserNotFoundException::class)) {
            $exceptions->render(function (\Modules\Users\Exceptions\UserNotFoundException $e, Request $request) {
                if ($request->is('api/*') || $request->expectsJson()) {
                    return response()->json([
                        'message' => $e->getMessage(),
                        'error' => 'user_not_found',
                    ], $e->getCode() ?: 400);
                }
            });
        }

        $exceptions->render(function (AuthenticationException $e, Request $request) use ($getAppMessage) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'app_code' => 'FUIP_401',
                    'message' => $getAppMessage('FUIP_401') ?? 'Vous n\'êtes pas authentifié.',
                    'errors' => ['message' => $e->getMessage()],
                ], 401);
            }
        });

        $exceptions->render(function (UnauthorizedHttpException $e, Request $request) use ($getAppMessage) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'app_code' => 'FUIP_401',
                    'message' => $getAppMessage('FUIP_401') ?? 'Vous n\'êtes pas authentifié.',
                    'errors' => ['message' => $e->getMessage()],
                ], 401);
            }
        });

        $exceptions->render(function (JWTException $e, Request $request) use ($getAppMessage) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'app_code' => 'FUIP_401',
                    'message' => $getAppMessage('FUIP_401') ?? 'Authentification requise.',
                    'errors' => ['message' => 'Token invalide ou expiré: ' . $e->getMessage()],
                ], 401);
            }
        });

        $exceptions->render(function (\InvalidArgumentException $e, Request $request) use ($getAppMessage) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'app_code' => 'FUIP_400',
                    'message' => $getAppMessage('FUIP_400') ?? 'Erreur de validation.',
                    'errors' => ['message' => $e->getMessage()],
                ], 400);
            }
        });

        $exceptions->render(function (AuthorizationException $e, Request $request) use ($getAppMessage) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'app_code' => 'FUIP_403',
                    'message' => $getAppMessage('FUIP_403') ?? "Vous n'êtes pas autorisé à effectuer cette action.",
                    'errors' => ['message' => $e->getMessage()],
                ], 403);
            }
        });

        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) use ($getAppMessage) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'app_code' => 'FUIP_403',
                    'message' => $getAppMessage('FUIP_403') ?? "Vous n'êtes pas autorisé à effectuer cette action.",
                    'errors' => ['message' => $e->getMessage()],
                ], 403);
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) use ($getAppMessage) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'app_code' => 'FUIP_404',
                    'message' => $getAppMessage('FUIP_404'),
                    'errors' => ['message' => $e->getMessage()],
                ], 404);
            }
        });

        $exceptions->render(function (ValidationException $e, Request $request) use ($getAppMessage) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'app_code' => 'FUIP_422',
                    'message' => $getAppMessage('FUIP_422'),
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        $exceptions->render(function (\Throwable $e, Request $request) use ($getAppMessage) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'app_code' => 'FUIP_500',
                    'message' => $getAppMessage('FUIP_500'),
                    'errors' => [
                        'message' => $e->getMessage(),
                        'exception_class' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ],
                ], 500);
            }
        });
    })->create();
