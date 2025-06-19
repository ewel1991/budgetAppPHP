<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;

class SettingsController
{
  public function __construct(
    private TemplateEngine $view
  ) {}

  public function createViewSettings(): void
  {

    if (!isset($_SESSION['user'])) {

      header('Location: /login');
      exit;
    }

    echo $this->view->render("transactions/settings.php", [
      'user' => $_SESSION['user']
    ]);
  }
}
