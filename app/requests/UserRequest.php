<?php

namespace requests;
require_once 'app/core/Request.php';

use core\Request;

class UserRequest extends Request
{

    public static function getPreparedData(array $data, $key): array
    {
        $data = self::createHtmlSpecialChars($data);
        self::checkEmpty($data);
        if (array_key_exists($key, $data)) {
            unset($data[$key]);
        }
        if (array_key_exists('password', $data)) {
            $data['password'] = self::codePassword($data['password']);
        }
        if (array_key_exists('password_confirmed', $data)) {
            $data['password_confirmed'] = self::codePassword($data['password_confirmed']);
        }

        return $data;
    }

    protected static function codePassword($data)
    {
        return md5($data);
    }

    protected static function createHtmlSpecialChars(array $data): array
    {
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(trim($value));
        }
        return $data;
    }

    protected static function checkEmpty($data)
    {
        $check = 0;
        foreach ($data as $item) {
            if (empty($item) or $item == '') {
                $check = $check + 1;
            }
        }
        if ($check != 0) {
            $_SESSION['errors'][] = 'Все поля должны быть заполнены';
        }
    }
}