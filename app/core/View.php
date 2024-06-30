<?php

namespace core;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once 'vendor/autoload.php';

class View
{
    private $loader;
    private $twig;
    private static $view;

    private function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader('app/views');
        $this->twig = new \Twig\Environment($this->loader, array(
            'cache' => false,
            'auto_reload' => true,
        ));
        $this->twig->addGlobal('session', $_SESSION);
        $this->twig->addGlobal('size', sizeof(file('session.txt')));
    }

    public static function getView()
    {
        if (!self::$view) self::$view = new View();
        return self::$view;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function generate($template_view, $data = null)
    {
        if (is_array($data)) {
            echo $this->twig->render($template_view, $data);
        } else {
            echo $this->twig->render($template_view);
        }
    }


}