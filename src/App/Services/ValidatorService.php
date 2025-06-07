<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Validator;
use Framework\Rules\{
  RequiredRule,
  EmailRule,
  MatchRule,
  UsernameRule,
  PasswordRule,
  NumericRule,
  DateFormatRule
};

class ValidatorService
{
  private Validator $validator;

  public function __construct()
  {
    $this->validator = new Validator();
    $this->validator->add('required', new RequiredRule());
    $this->validator->add('email', new EmailRule());
    $this->validator->add('match', new MatchRule());
    $this->validator->add('username', new UsernameRule());
    $this->validator->add('password', new PasswordRule());
    $this->validator->add('numeric', new NumericRule());
    $this->validator->add('dateFormat', new DateFormatRule());
  }

  public function validateRegister(array $formData)
  {
    $this->validator->validate($formData, [
      'username' => ['required', 'username'],
      'email' => ['required', 'email'],
      'password' => ['required', 'password'],
      'confirmPassword' => ['required', 'match:password']

    ]);
  }

  public function validateLogin(array $formData)
  {
    $this->validator->validate($formData, [
      'email' => ['required', 'email'],
      'password' => ['required']
    ]);
  }


  public function validateTransaction(array $formData)
  {

    $this->validator->validate($formData, [
      'amount' => ['required', 'numeric'],
      'date' => ['required', 'dateFormat:Y-m-d']
    ]);
  }
}
