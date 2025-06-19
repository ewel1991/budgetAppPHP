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

    $userId = $this->db->id();

    session_regenerate_id();

    $_SESSION['userId'] = $userId;

    // Skopiuj domyślne kategorie wydatków
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
    $defaultIncomeCategories = $this->db->query("SELECT name FROM income_category_default")->findAll();
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
    $defaultPaymentMethods = $this->db->query("SELECT name FROM payment_methods_default")->findAll();
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

    $_SESSION['user'] = [
      'id' => $user['id'],
      'username' => $user['username'],
      'email' => $user['email']
    ];
  }


  public function logout()
  {
    unset($_SESSION['user']);
    session_regenerate_id();
  }

  public function updateName(int $userId, string $newName): void
  {
    $this->db->query(
      "UPDATE users SET username = :username WHERE id = :id",
      [
        'username' => $newName,
        'id' => $userId
      ]
    );

    if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $userId) {
      $_SESSION['user']['username'] = $newName;
    }
  }

  public function getUserById(int $userId): array
  {
    $user = $this->db->query(
      "SELECT id, username, email FROM users WHERE id = :id",
      ['id' => $userId]
    )->find();

    if (!$user) {
      throw new \RuntimeException("Nie znaleziono użytkownika o ID: $userId");
    }

    return $user;
  }

  public function updateEmail(int $userId, string $newEmail): void
  {

    $emailCount = $this->db->query(
      "SELECT COUNT(*) FROM users WHERE email = :email AND id != :id",
      ['email' => $newEmail, 'id' => $userId]
    )->count();

    if ($emailCount > 0) {
      throw new ValidationException(['email' => ['Email już użyty']]);
    }

    $this->db->query(
      "UPDATE users SET email = :email WHERE id = :id",
      [
        'email' => $newEmail,
        'id' => $userId
      ]
    );
  }

  public function updatePassword(int $userId, string $newPassword): void
  {
    $hashed = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);

    $this->db->query(
      "UPDATE users SET password = :password WHERE id = :id",
      [
        'password' => $hashed,
        'id' => $userId
      ]
    );
  }
}
