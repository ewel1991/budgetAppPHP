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

    error_log("createIncome called");
    var_dump($_POST);
    exit;


    try {
      $this->validatorService->validateTransaction($_POST);
      $this->transactionService->createIncome($_POST);
      header('Location: /income');
      exit;
    } catch (\Framework\Exceptions\ValidationException $e) {
      $_SESSION['errors'] = $e->errors;
      $_SESSION['old'] = $_POST;
      header('Location: /income');
      exit;
    }
  }





  public function createViewExpense()
  {
    echo $this->view->render("transactions/expense.php");
  }

  public function createViewBalance()
  {
    echo $this->view->render("transactions/balance.php");
  }

  public function createViewSettings()
  {
    echo $this->view->render("transactions/settings.php");
  }




  public function createExpense()
  {
    $this->validatorService->validateTransaction($_POST);

    redirectTo('/expense');
  }
}
