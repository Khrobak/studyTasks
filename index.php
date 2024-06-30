<?php
require_once 'app/core/Model.php';
require_once 'app/core/Controller.php';
require_once 'app/core/View.php';
require_once 'app/core/Router.php';
use core\Router;
session_start();
$router = Router::activate();
$router->start();
