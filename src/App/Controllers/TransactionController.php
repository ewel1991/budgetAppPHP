<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\{
  TemplateEngine,
};

use App\Services\{
  ValidatorService,
  TransactionService,
  UserService
};


class TransactionController
{
  public function __construct(
    private TemplateEngine $view,
    private ValidatorService $validatorService,
    private TransactionService $transactionService,
  ) {}

  public function createView()
  {

    echo $this->view->render("transactions/menu.php");
  }

  public function createViewIncome()
  {

    $userId = $_SESSION['user']['id'];
    $incomeCategories = $this->transactionService->getUserIncomeCategories($userId);

    echo $this->view->render("transactions/income.php", [
      'incomeCategories' => $incomeCategories
    ]);
  }

  public function createIncome()
  {

    $this->validatorService->validateTransaction($_POST);
    $this->transactionService->createIncome($_POST);
    redirectTo('/balance');
  }



  public function createViewExpense()
  {
    $userId = $_SESSION['user']['id'];

    $expenseCategories = $this->transactionService->getUserExpenseCategories($userId);
    $paymentMethods = $this->transactionService->getUserPaymentMethods($userId);

    echo $this->view->render("transactions/expense.php", [
      'expenseCategories' => $expenseCategories,
      'paymentMethods' => $paymentMethods,
      'oldFormData' => $_SESSION['old'] ?? [],
      'errors' => $_SESSION['errors'] ?? [],
    ]);
  }

  public function createViewBalance()
  {
    $userId = $_SESSION['user']['id'];
    $dateRange = $_GET['dateRange'] ?? 'current';

    switch ($dateRange) {
      case 'previous':
        $startDate = date('Y-m-01', strtotime('first day of last month'));
        $endDate = date('Y-m-t', strtotime('last day of last month'));
        break;
      case 'year':
        $startDate = date('Y-01-01');
        $endDate = date('Y-12-31');
        break;
      case 'custom':
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-t');
        break;
      case 'current':
      default:
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        break;
    }

    $incomes = $this->transactionService->getIncomesByUserAndDate($userId, $startDate, $endDate);
    $expenses = $this->transactionService->getExpensesByUserAndDate($userId, $startDate, $endDate);

    $totalIncome = array_sum(array_column($incomes, 'total'));
    $totalExpense = array_sum(array_column($expenses, 'total'));
    $balance = $totalIncome - $totalExpense;

    echo $this->view->render("transactions/balance.php", [
      'incomes' => $incomes,
      'expenses' => $expenses,
      'totalIncome' => $totalIncome,
      'totalExpense' => $totalExpense,
      'balance' => $balance,
      'dateRange' => $dateRange,
      'startDate' => $startDate,
      'endDate' => $endDate
    ]);
  }


  public function createTransactionSettingsView()
  {

    $userId = $_SESSION['user']['id'];
    $incomeCategories = $this->transactionService->getUserIncomeCategories($userId);
    $expenseCategories = $this->transactionService->getUserExpenseCategories($userId);
    $paymentMethods = $this->transactionService->getUserPaymentMethods($userId);



    echo $this->view->render("transactions/settings_transactions.php", [
      'incomeCategories' => $incomeCategories,
      'expenseCategories' => $expenseCategories,
      'paymentMethods' => $paymentMethods
    ]);
  }




  public function createExpense()
  {
    $this->validatorService->validateTransaction($_POST);
    $this->transactionService->createExpense($_POST);
    redirectTo('/balance');
  }

  public function addIncomeCategory()
  {
    $userId = $_SESSION['user']['id'];
    $categoryName = trim($_POST['income_category'] ?? '');

    if ($categoryName === '') {

      redirectTo('/income');
    }

    $this->transactionService->addIncomeCategory($userId, $categoryName);
    redirectTo('/income');
  }


  public function deleteIncomeCategory(): void
  {
    $userId = $_SESSION['user']['id'];
    $formData = $_POST;

    if (isset($formData['category'])) {
      $this->transactionService->deleteIncomeCategory($userId, $formData['category']);
    }

    redirectTo('/income');
  }

  public function addExpenseCategory()
  {
    $userId = $_SESSION['user']['id'];
    $categoryName = trim($_POST['expense_category'] ?? '');

    if ($categoryName === '') {
      redirectTo('/expense');
    }

    $this->transactionService->addExpenseCategory($userId, $categoryName);
    redirectTo('/expense');
  }

  public function deleteExpenseCategory()
  {
    $userId = $_SESSION['user']['id'];
    $formData = $_POST;

    if (!empty($formData['category'])) {
      $this->transactionService->deleteExpenseCategory($userId, $formData['category']);
    }

    redirectTo('/expense');
  }

  public function addPaymentMethod()
  {
    $userId = $_SESSION['user']['id'];
    $methodName = trim($_POST['payment_method'] ?? '');

    if ($methodName === '') {
      redirectTo('/expense');
    }

    $this->transactionService->addPaymentMethod($userId, $methodName);
    redirectTo('/expense');
  }

  public function deletePaymentMethod()
  {
    $userId = $_SESSION['user']['id'];
    $methodId = $_POST['payment_method_id'] ?? '';

    if ($methodId !== '') {
      $this->transactionService->deletePaymentMethod($userId, (int)$methodId);
    }

    redirectTo('/expense');
  }
}
