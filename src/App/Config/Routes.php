<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;
use App\Controllers\{
  HomeController,
  AuthController,
  TransactionController,
  SettingsController
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


  $app->get('/settings', [SettingsController::class, 'createViewSettings'])->add(AuthRequiredMiddleware::class);
  $app->get('/settings/user', [AuthController::class, 'createUserSettingsView'])->add(AuthRequiredMiddleware::class);
  $app->get('/settings/transactions', [TransactionController::class, 'createTransactionSettingsView'])->add(AuthRequiredMiddleware::class);


  $app->post('/settings/update-name', [AuthController::class, 'updateName'])->add(AuthRequiredMiddleware::class);
  $app->post('/income-categories/add', [TransactionController::class, 'addIncomeCategory'])->add(AuthRequiredMiddleware::class);
  $app->post('/expense-categories/add', [TransactionController::class, 'addExpenseCategory'])->add(AuthRequiredMiddleware::class);
  $app->post('/payment-categories/add', [TransactionController::class, 'addPaymentMethod'])->add(AuthRequiredMiddleware::class);
  $app->post('/income-categories/delete', [TransactionController::class, 'deleteIncomeCategory'])->add(AuthRequiredMiddleware::class);
  $app->post('/expense-categories/delete', [TransactionController::class, 'deleteExpenseCategory'])->add(AuthRequiredMiddleware::class);
  $app->post('/payment-categories/delete', [TransactionController::class, 'deletePaymentMethod'])->add(AuthRequiredMiddleware::class);
  $app->post('/expense-categories/set-limit', [TransactionController::class, 'setExpenseCategoryLimit'])->add(AuthRequiredMiddleware::class);

  $app->post('/settings/update-email', [AuthController::class, 'updateEmail'])->add(AuthRequiredMiddleware::class);
  $app->post('/settings/update-password', [AuthController::class, 'updatePassword'])->add(AuthRequiredMiddleware::class);
}
