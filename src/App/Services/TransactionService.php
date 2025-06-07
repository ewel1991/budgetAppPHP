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
}
