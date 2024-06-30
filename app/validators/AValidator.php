<?php

namespace app\validators;
abstract class AValidator
{
  protected $data;
  
  public function __construct(array $data)
  {
    $this->data = $data;
  }
  
  abstract public function validate(): bool;

  
  protected function validateEmail()
  {
//    if (!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
//      $_SESSION['errors'][] = "Неправильно записана почта";
//    }
    $pattern = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
    if (preg_match($pattern, $this->data['email']) !== 1) {
      $_SESSION['errors'][] = "Неправильно записана почта";
    }
    return $this;
  }

  
}