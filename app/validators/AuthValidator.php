<?php
namespace app\validators;
use core\Model;


require_once ('app/validators/AValidator.php');


class AuthValidator extends AValidator
{
  public function validate(): bool
  {
    $this->validateEmail();
    if (isset($_SESSION['errors'])) return false;
    return true;
  }

}