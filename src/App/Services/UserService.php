<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;

class UserService
{
  public function __construct(private Database $db) {}


  public function isEmailTaken(string $email)
  {

    $emailCount = $this->db->query(
      "SELECT COUNT(*) FROM users WHERE email = :email",
      [
        'email' => $email
      ]
    )->count();

    if ($emailCount > 0) {
      throw new ValidationException(['email' => ['Email już użyty']]);
    }
  }

  public function create(array $formData)
  {


    $password = password_hash($formData['password'], PASSWORD_BCRYPT, ['cost' => 12]);


    $this->db->query(
      "INSERT INTO users(username, email, password)
      VALUES (:username, :email, :password)",
      [
        'username' => $formData['username'],
        'email' => $formData['email'],
        'password' => $password,
      ]
    );

    session_regenerate_id();

    $userId = $_SESSION['user'] = $this->db->id();

    $_SESSION['user'] = $userId;

    // Skopiuj domyślne kategorie wydatków do przypisanych użytkownikowi
    $defaultCategories = $this->db->query("SELECT name FROM expense_category_default")->findAll();

    foreach ($defaultCategories as $category) {
      $this->db->query(
        "INSERT INTO expense_category_assigned_to_users (user_id, name)
             VALUES (:user_id, :name)",
        [
          'user_id' => $userId,
          'name' => $category['name']
        ]
      );
    }


    // Skopiuj domyślne kategorie przychodów
    $defaultIncomeCategories = $this->db
      ->query("SELECT name FROM income_category_default")
      ->findAll();

    foreach ($defaultIncomeCategories as $category) {
      $this->db->query(
        "INSERT INTO income_category_assigned_to_users (user_id, name)
             VALUES (:user_id, :name)",
        [
          'user_id' => $userId,
          'name' => $category['name']
        ]
      );
    }

    // Skopiuj domyślne metody płatności
    $defaultPaymentMethods = $this->db
      ->query("SELECT name FROM payment_methods_default")
      ->findAll();

    foreach ($defaultPaymentMethods as $method) {
      $this->db->query(
        "INSERT INTO payment_methods_assigned_to_users (user_id, name)
             VALUES (:user_id, :name)",
        [
          'user_id' => $userId,
          'name' => $method['name']
        ]
      );
    }
  }


  public function login(array $formData)
  {
    $user = $this->db->query("SELECT * FROM users WHERE email = :email", [
      'email' => $formData['email']
    ])->find();


    $passwordMatch = password_verify(
      $formData['password'],
      $user['password'] ?? ''
    );

    if (!$user || !$passwordMatch) {
      throw new ValidationException(['password' => ['Invalid credentials']]);
    }

    session_regenerate_id();

    $_SESSION['user'] = $user['id'];
  }

  public function logout()
  {
    unset($_SESSION['user']);
    session_regenerate_id();
  }
}
