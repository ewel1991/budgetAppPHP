<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class UsernameRule implements RuleInterface
{
  public function validate(array $data, string $field, array $params): bool
  {
    if (!isset($data[$field])) {
      return false;
    }

    $username = trim($data[$field]);

    // Sprawdza, czy tylko litery (łącznie z polskimi znakami) i długość 3-20
    return preg_match('/^[A-Za-zĄąĆćĘęŁłŃńÓóŚśŹźŻż]{3,20}$/u', $username) === 1;
  }

  public function getMessage(array $data, string $field, array $params): string
  {
    return 'Imię może zawierać tylko litery (bez cyfr i znaków specjalnych) od 3 do 20 znaków';
  }
}
