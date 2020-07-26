<?php

namespace App\MiddleWare;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use Pecee\SimpleRouter\SimpleRouter;

class AuthMiddleWare implements IMiddleware
{

    /**
     * @param Request $request
     */
    public function handle(Request $request): void
    {
        if (!isset($_SESSION['user'])) {
            $route = $_ENV['DIRECTORY_ROOT'] ?? '';
            SimpleRouter::response()->redirect($route);
        }
    }
}
