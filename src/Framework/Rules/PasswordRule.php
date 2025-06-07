<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class PasswordRule implements RuleInterface
{
  public function validate(array $data, string $field, array $params): bool
  {
    $password = $data[$field] ?? '';

    // Hasło: min. 3 znaki, max. 20, co najmniej jedna litera i jedna cyfra
    return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d!@#$%^&*()_+]{3,20}$/', $password) === 1;
  }

  public function getMessage(array $data, string $field, array $params): string
  {
    return "Hasło musi mieć 3-20 znaków, zawierać co najmniej jedną literę i jedną cyfrę";
  }
}
