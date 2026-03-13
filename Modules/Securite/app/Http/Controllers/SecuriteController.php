<?php

namespace Modules\Securite\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Securite\Services\UserService;

class SecuriteController extends Controller
{
    public function __construct(UserService $service)
    {
        parent::__construct($service);
    }
}
