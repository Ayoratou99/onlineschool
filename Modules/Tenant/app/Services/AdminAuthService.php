<?php

namespace Modules\Tenant\Services;

use Illuminate\Support\Facades\Auth;
use Modules\Tenant\Models\Admin;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AdminAuthService
{
    public function login(array $credentials): string
    {
        if (!$token = Auth::guard('admin')->attempt($credentials)) {
            throw new UnauthorizedHttpException('JWT', 'Invalid credentials');
        }

        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();
        if ($admin->state === 'BLOCKED') {
            Auth::guard('admin')->logout();
            throw new AccessDeniedHttpException('Account blocked');
        }

        return $token;
    }

    public function refresh(): string
    {
        $token = Auth::guard('admin')->refresh();
        Auth::guard('admin')->setToken($token)->authenticate();
        $admin = Auth::guard('admin')->user();
        if ($admin->state === 'BLOCKED') {
            throw new AccessDeniedHttpException('Account blocked');
        }
        return $token;
    }

    public function logout(): void
    {
        Auth::guard('admin')->logout();
    }
}
