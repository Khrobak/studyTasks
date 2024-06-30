<?php

namespace core;
require_once 'app/controllers/UserController.php';

use controllers\UserController;

class Router
{
    private $controller;
    private static $router;

    private function __construct()
    {
        $this->controller = new UserController();
    }

    public static function activate()
    {
        if (!self::$router) self::$router = new Router();
        return self::$router;
    }

    public function start()
    {
        $url = $_SERVER["REQUEST_URI"];
        $method = $_SERVER["REQUEST_METHOD"];
        if ($url == "/" && $method == "GET") {
            $this->controller->index();
        } elseif ($url == "/" && $method == "POST") {
            $this->controller->store($_POST);
        } elseif ($url == "/create" && $method == "GET") {
            $this->controller->create();
        } elseif ($url == "/edit" && $method == "GET") {
            $this->controller->edit();
        } elseif ($url == "/update" && $method == "POST") {
            $this->controller->update($_POST);
        } elseif ($url == "/auth" && $method == "GET") {
            $this->controller->auth();
        } elseif ($url == "/auth" && $method == "POST") {
            $this->controller->check($_POST);
        } elseif ($url == "/exit" && $method == "GET") {
            $this->controller->exitFromProfile();
        }
    }

}
