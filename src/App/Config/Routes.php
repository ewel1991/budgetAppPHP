<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;
use App\Controllers\{
  HomeController,
  AuthController,
  TransactionController
};

use App\Middleware\{AuthRequiredMiddleware, GuestOnlyMiddleware};

function registerRoutes(App $app)
{
  $app->get('/', [HomeController::class, 'home']);
  $app->get('/register', [AuthController::class, 'registerView'])->add(GuestOnlyMiddleware::class);
  $app->post('/register', [AuthController::class, 'register'])->add(GuestOnlyMiddleware::class);
  $app->get('/login', [AuthController::class, 'loginView']);
  $app->post('/login', [AuthController::class, 'login'])->add(GuestOnlyMiddleware::class);
  $app->get('/logout', [AuthController::class, 'logout'])->add(AuthRequiredMiddleware::class);

  $app->get('/transaction', [TransactionController::class, 'createView'])->add(AuthRequiredMiddleware::class);

  $app->get('/income', [TransactionController::class, 'createViewIncome'])->add(AuthRequiredMiddleware::class);
  $app->post('/income', [TransactionController::class, 'createIncome'])->add(AuthRequiredMiddleware::class);

  $app->get('/expense', [TransactionController::class, 'createViewExpense'])->add(AuthRequiredMiddleware::class);
  $app->post('/expense', [TransactionController::class, 'createExpense'])->add(AuthRequiredMiddleware::class);

  $app->get('/balance', [TransactionController::class, 'createViewBalance'])->add(AuthRequiredMiddleware::class);
  $app->get('/settings', [TransactionController::class, 'createViewSettings'])->add(AuthRequiredMiddleware::class);
}
