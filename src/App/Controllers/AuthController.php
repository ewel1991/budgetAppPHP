<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ValidatorService, UserService};

class AuthController
{
  public function __construct(
    private TemplateEngine $view,
    private ValidatorService $validatorService,
    private UserService $userService
  ) {}

  public function registerView()
  {
    echo $this->view->render("register.php");
  }

  public function register()
  {
    $this->validatorService->validateRegister($_POST);

    $this->userService->isEmailTaken($_POST['email']);

    $this->userService->create($_POST);

    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
      );
    }
    session_destroy();


    redirectTo('/');
  }

  public function loginView()
  {
    echo $this->view->render("login.php");
  }

  public function login()
  {
    $this->validatorService->validateLogin($_POST);

    $this->userService->login($_POST);

    redirectTo('/transaction');
  }

  public function logout()
  {
    $this->userService->logout();

    redirectTo('/');
  }

  public function updateName()
  {
    $newName = trim($_POST['name']);
    $userId = $_SESSION['user']['id'];

    if (empty($newName)) {

      redirectTo('/settings');
    }

    $this->userService->updateName($userId, $newName);

    // Zaktualizuj sesję
    $_SESSION['user']['username'] = $newName;

    redirectTo('/settings');
  }


  public function createUserSettingsView()
  {

    $userId = $_SESSION['user']['id'];
    $user = $this->userService->getUserById($userId);
    echo $this->view->render("transactions/settings_user.php", [
      'currentUser' => $user
    ]);
  }

  public function updateEmail()
  {
    $newEmail = trim($_POST['email']);
    $userId = $_SESSION['user']['id'];

    if (empty($newEmail)) {
      redirectTo('/settings');
    }


    $this->userService->isEmailTaken($newEmail);


    $this->userService->updateEmail($userId, $newEmail);
    $_SESSION['user']['email'] = $newEmail;

    redirectTo('/settings');
  }

  public function updatePassword()
  {
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if (empty($password) || $password !== $passwordConfirm) {

      redirectTo('/settings');
    }

    $userId = $_SESSION['user']['id'];
    $this->userService->updatePassword($userId, $password);

    redirectTo('/settings');
  }
}
