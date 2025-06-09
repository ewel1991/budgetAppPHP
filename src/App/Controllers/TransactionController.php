<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\{
  TemplateEngine,
};

use App\Services\{
  ValidatorService,
  TransactionService
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

    echo $this->view->render("transactions/income.php");
  }

  public function createIncome()
  {

    $this->validatorService->validateTransaction($_POST);
    $this->transactionService->createIncome($_POST);
    redirectTo('/balance');
  }



  public function createViewExpense()
  {
    echo $this->view->render("transactions/expense.php");
  }

  public function createViewBalance()
  {
    $userId = $_SESSION['user'];
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


  public function createViewSettings()
  {
    echo $this->view->render("transactions/settings.php");
  }




  public function createExpense()
  {
    $this->validatorService->validateTransaction($_POST);
    $this->transactionService->createExpense($_POST);
    redirectTo('/balance');
  }
}
