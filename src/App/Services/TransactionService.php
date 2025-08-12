<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class TransactionService
{
  public function __construct(private Database $db) {}

  public function createIncome(array $formData)
  {

    $formattedDate = "{$formData['date']} 00:00:00";


    $categoryId = $this->db->query(
      "SELECT id FROM income_category_assigned_to_users
         WHERE name = :name AND user_id = :user_id",
      [
        'name' => $formData['category'],
        'user_id' => $_SESSION['user']['id']
      ]
    )->find();

    if (!$categoryId) {
      throw new \Exception("Nie znaleziono kategorii przychodu.");
    }

    $this->db->query(
      "INSERT INTO incomes(user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
         VALUES(:user_id, :category_id, :amount, :date_of_income, :comment)",
      [
        'user_id' => $_SESSION['user']['id'],
        'category_id' => $categoryId['id'],
        'amount' => $formData['amount'],
        'date_of_income' => $formattedDate,
        'comment' => $formData['comment']
      ]
    );
  }


  public function getAllUserIncomes(): array
  {
    return $this->db->query(
      "SELECT incomes.*, ic.name AS category_name
         FROM incomes
         JOIN income_category_assigned_to_users ic
           ON ic.id = incomes.income_category_assigned_to_user_id
         WHERE incomes.user_id = :user_id
         ORDER BY date_of_income DESC",
      ['user_id' => $_SESSION['user']['id']]
    )->findAll();
  }

  public function createExpense(array $formData): void
  {
    $formattedDate = "{$formData['date']} 00:00:00";

    $categoryId = $this->db->query(
      "SELECT id FROM expense_category_assigned_to_users
       WHERE name = :name AND user_id = :user_id",
      [
        'name' => $formData['category'],
        'user_id' => $_SESSION['user']['id']
      ]
    )->find();

    if (!$categoryId) {
      throw new \Exception("Nie znaleziono kategorii wydatku.");
    }

    $paymentMethodId = $this->db->query(
      "SELECT id FROM payment_methods_assigned_to_users
       WHERE name = :name AND user_id = :user_id",
      [
        'name' => $formData['payment'],  // <-- zmiana tutaj
        'user_id' => $_SESSION['user']['id']
      ]
    )->find();

    if (!$paymentMethodId) {
      throw new \Exception("Nie znaleziono metody płatności.");
    }

    $this->db->query(
      "INSERT INTO expenses(user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)
       VALUES(:user_id, :category_id, :payment_method_id, :amount, :date_of_expense, :comment)",
      [
        'user_id' => $_SESSION['user']['id'],
        'category_id' => $categoryId['id'],
        'payment_method_id' => $paymentMethodId['id'],
        'amount' => $formData['amount'],
        'date_of_expense' => $formattedDate,
        'comment' => $formData['comment']
      ]
    );
  }

  public function getIncomesByUserAndDate(int $userId, string $startDate, string $endDate): array
  {
    $sql = "SELECT ic.name AS category, SUM(i.amount) AS total
            FROM incomes i
            JOIN income_category_assigned_to_users ic ON i.income_category_assigned_to_user_id = ic.id
            WHERE i.user_id = :userId AND date_of_income BETWEEN :startDate AND :endDate
            GROUP BY ic.name
            ORDER BY total DESC";

    return $this->db->query($sql, [
      'userId' => $userId,
      'startDate' => $startDate,
      'endDate' => $endDate
    ])->findAll();
  }

  public function getExpensesByUserAndDate(int $userId, string $startDate, string $endDate): array
  {
    $sql = "SELECT ec.name AS category, SUM(e.amount) AS total
            FROM expenses e
            JOIN expense_category_assigned_to_users ec
              ON e.expense_category_assigned_to_user_id = ec.id
            WHERE e.user_id = :userId
              AND e.date_of_expense BETWEEN :startDate AND :endDate
            GROUP BY ec.name
            ORDER BY total DESC";

    return $this->db->query($sql, [
      'userId' => $userId,
      'startDate' => $startDate,
      'endDate' => $endDate
    ])->findAll();
  }

  public function addIncomeCategory(int $userId, string $category): void
  {
    $this->db->query(
      "INSERT INTO income_category_assigned_to_users (user_id, name)
         VALUES (:user_id, :name)",
      [
        'user_id' => $userId,
        'name' => $category
      ]
    );
  }

  public function getUserIncomeCategories(int $userId): array
  {
    return $this->db->query(
      "SELECT name FROM income_category_assigned_to_users
                WHERE user_id = :user_id",
      ['user_id' => $userId]
    )->findAll();
  }

  public function deleteIncomeCategory(int $userId, string $categoryName): void
  {
    $this->db->query(
      "DELETE FROM income_category_assigned_to_users
            WHERE user_id = :user_id AND name = :name",
      [
        'user_id' => $userId,
        'name' => $categoryName
      ]
    );
  }

  public function addExpenseCategory(int $userId, string $category): void
  {
    $this->db->query(
      "INSERT INTO expense_category_assigned_to_users (user_id, name)
         VALUES (:user_id, :name)",
      [
        'user_id' => $userId,
        'name' => $category
      ]
    );
  }

  public function deleteExpenseCategory(int $userId, string $category): void
  {
    $this->db->query(
      "DELETE FROM expense_category_assigned_to_users
         WHERE user_id = :user_id AND name = :name",
      [
        'user_id' => $userId,
        'name' => $category
      ]
    );
  }

  public function getUserExpenseCategories(int $userId): array
  {
    return $this->db->query(
      "SELECT name, category_limit FROM expense_category_assigned_to_users
         WHERE user_id = :user_id",
      ['user_id' => $userId]
    )->findAll();
  }


  public function getUserPaymentMethods(int $userId): array
  {
    return $this->db->query(
      "SELECT id, name FROM payment_methods_assigned_to_users WHERE user_id = :user_id",
      ['user_id' => $userId]
    )->findAll();
  }

  public function addPaymentMethod(int $userId, string $name): void
  {
    $this->db->query(
      "INSERT INTO payment_methods_assigned_to_users (user_id, name) VALUES (:user_id, :name)",
      ['user_id' => $userId, 'name' => $name]
    );
  }

  public function deletePaymentMethod(int $userId, int $id): void
  {
    $this->db->query(
      "DELETE FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND id = :id",
      ['user_id' => $userId, 'id' => $id]
    );
  }

  public function setExpenseCategoryLimit(int $userId, string $categoryName, float $limit): void
  {
    $this->db->query(
      "UPDATE expense_category_assigned_to_users
          SET category_limit = :limit
          WHERE user_id = :user_id AND name = :name",
      [
        'user_id' => $userId,
        'name' => $categoryName,
        'limit' => $limit
      ]
    );
  }


  public function getExpenseCategoryLimit(int $userId, string $categoryName): ?float
  {
    $result = $this->db->query(
      "SELECT category_limit
         FROM expense_category_assigned_to_users
         WHERE user_id = :user_id
           AND name = :category_name
         LIMIT 1",
      [
        'user_id' => $userId,
        'category_name' => $categoryName
      ]
    )->find();

    return $result ? (float)$result['category_limit'] : null;
  }

  public function getCategoryLimitStatus(
    int $userId,
    string $categoryName,
    ?string $startDate = null,
    ?string $endDate = null,
    float $amount = 0.0  // nowy parametr, domyślnie 0
  ): array {
    $categoryLimit = $this->getExpenseCategoryLimit($userId, $categoryName);

    if ($categoryLimit === null || $categoryLimit == 0) {
      return [
        'limitSet' => false,
        'limit' => null,
        'spent' => 0,
        'remaining' => null
      ];
    }

    if ($startDate === null || $endDate === null) {
      $startDate = date('Y-m-01');
      $endDate = date('Y-m-t');
    }

    $spentRow = $this->db->query(
      "SELECT SUM(amount) AS total_spent
         FROM expenses
         WHERE user_id = :user_id
           AND expense_category_assigned_to_user_id = (
               SELECT id
               FROM expense_category_assigned_to_users
               WHERE user_id = :user_id
                 AND name = :category_name
               LIMIT 1
           )
           AND date_of_expense BETWEEN :start_date AND :end_date",
      [
        'user_id' => $userId,
        'category_name' => $categoryName,
        'start_date' => $startDate,
        'end_date' => $endDate
      ]
    )->find();

    $spent = $spentRow && $spentRow['total_spent'] !== null
      ? (float)$spentRow['total_spent']
      : 0.0;

    // Dodajemy wpisaną kwotę do wydatków
    $spentWithAmount = $spent + $amount;

    return [
      'limitSet' => true,
      'limit' => $categoryLimit,
      'spent' => $spentWithAmount,
      'remaining' => $categoryLimit - $spentWithAmount
    ];
  }
}
