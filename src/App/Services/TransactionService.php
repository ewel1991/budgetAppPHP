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
        'user_id' => $_SESSION['user']
      ]
    )->find();

    if (!$categoryId) {
      throw new \Exception("Nie znaleziono kategorii przychodu.");
    }

    $this->db->query(
      "INSERT INTO incomes(user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
         VALUES(:user_id, :category_id, :amount, :date_of_income, :comment)",
      [
        'user_id' => $_SESSION['user'],
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
      ['user_id' => $_SESSION['user']]
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
        'user_id' => $_SESSION['user']
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
        'user_id' => $_SESSION['user']
      ]
    )->find();

    if (!$paymentMethodId) {
      throw new \Exception("Nie znaleziono metody płatności.");
    }

    $this->db->query(
      "INSERT INTO expenses(user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)
       VALUES(:user_id, :category_id, :payment_method_id, :amount, :date_of_expense, :comment)",
      [
        'user_id' => $_SESSION['user'],
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
}
