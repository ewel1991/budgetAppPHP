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


use Gemini;

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

    $advisorMessage = $this->getAdvisorMessage(
      $totalIncome,
      $totalExpense,
      $balance,
      $incomes,
      $expenses
    );

    echo $this->view->render("transactions/balance.php", [
      'incomes' => $incomes,
      'expenses' => $expenses,
      'totalIncome' => $totalIncome,
      'totalExpense' => $totalExpense,
      'balance' => $balance,
      'dateRange' => $dateRange,
      'startDate' => $startDate,
      'endDate' => $endDate,
      'advisorMessage' => $advisorMessage
    ]);
  }

  private function getAdvisorMessage(
    float $totalIncome,
    float $totalExpense,
    float $balance,
    array $incomes,
    array $expenses
  ): string {
    try {
      $client = Gemini::client($_ENV['GEMINI_API_KEY'] ?? 'TWÓJ_KLUCZ_API_TUTAJ');

      $model = $client->generativeModel('gemini-2.5-flash', [
        'generationConfig' => [
          'temperature' => 0.7,
          'maxOutputTokens' => 2048
        ]
      ]);

      // Przygotowanie czytelnego opisu kategorii
      $incomeSummary = implode(", ", array_map(fn($i) => "{$i['category']}: {$i['total']} zł", $incomes));
      $expenseSummary = implode(", ", array_map(fn($e) => "{$e['category']}: {$e['total']} zł", $expenses));

      $prompt = sprintf(
        "Jesteś doradcą finansowym. Na podstawie danych użytkownika:
            Przychody: %.2f zł (%s),
            Wydatki: %.2f zł (%s),
            Bilans: %.2f zł.
            Przeanalizuj strukturę wydatków i przychodów, wskaż mocne i słabe strony oraz zaproponuj jedną praktyczną poradę.
            Odpowiedź w języku polskim, maksymalnie 3 zdania.",
        $totalIncome,
        $incomeSummary,
        $totalExpense,
        $expenseSummary,
        $balance
      );

      $response = $model->generateContent($prompt);

      return method_exists($response, 'text')
        ? trim($response->text())
        : "Brak odpowiedzi od doradcy finansowego.";
    } catch (\Exception $e) {
      return "Błąd API Gemini: " . $e->getMessage();
    }
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


  public function setExpenseCategoryLimit()
  {
    $userId = $_SESSION['user']['id'];
    $categoryName = trim($_POST['category'] ?? '');
    $limit = isset($_POST['limit']) ? (float)$_POST['limit'] : null;

    if ($categoryName === '' || $limit === null || $limit < 0) {
      redirectTo('/settings/transactions');
    }

    $this->transactionService->setExpenseCategoryLimit($userId, $categoryName, $limit);
    redirectTo('/settings/transactions');
  }

  public function getExpenseCategoryLimitAjax(): void
  {
    $userId = $_SESSION['user']['id'];
    $categoryName = $_GET['category'] ?? '';
    $date = $_GET['date'] ?? null;
    $amount = isset($_GET['amount']) ? (float)$_GET['amount'] : 0.0;

    if ($categoryName === '') {
      http_response_code(400);
      echo json_encode(['error' => 'Category parameter is required']);
      return;
    }

    if ($date !== null) {
      $startDate = date('Y-m-01', strtotime($date));
      $endDate = date('Y-m-t', strtotime($date));
    } else {
      $startDate = null;
      $endDate = null;
    }


    $limitStatus = $this->transactionService->getCategoryLimitStatus($userId, $categoryName, $startDate, $endDate, $amount);

    header('Content-Type: application/json');
    echo json_encode($limitStatus);
  }
}
