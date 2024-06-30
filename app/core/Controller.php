<?php

namespace core;

use core\Request;

abstract class Controller
{
    protected $view;
    protected $request;

    public function __construct()
    {
        $this->view = View::getView();
    }

}