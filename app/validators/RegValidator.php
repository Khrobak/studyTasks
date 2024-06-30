<?php

namespace app\validators;

require_once 'app/validators/AValidator.php';

class RegValidator extends AValidator
{
  protected function validatePassword()
  {
    if ($this->data['password'] !== $this->data['password_confirmed']) {
      $_SESSION['errors'][] = "При написании пароля возникла ошибка. Убедитесь, что введенные пароли совпадают.";
    }
    return $this;
  }
  
  public function validate(): bool
  {
    $this->validatePassword()
      ->validateEmail();
    if (isset($_SESSION['errors'])) return false;
    return true;
  }

}