<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;

class AuthRequiredMiddleware implements MiddlewareInterface
{
  public function process(callable $next)
  {
    error_log("AuthRequiredMiddleware: user=" . (isset($_SESSION['user']) ? json_encode($_SESSION['user']) : 'null'));

    if (empty($_SESSION['user'])) {
      error_log("AuthRequiredMiddleware: redirecting to /login");
      redirectTo('/login');
      exit;
    }

    $next();
  }
}
