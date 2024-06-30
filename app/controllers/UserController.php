<?php

namespace controllers;
require_once 'app/models/User.php';
require_once 'app/requests/UserRequest.php';
require_once 'app/validators/AuthValidator.php';
require_once 'app/validators/EditValidator.php';
require_once 'app/validators/RegValidator.php';

use app\validators\AuthValidator;
use app\validators\EditValidator;
use app\validators\RegValidator;
use core\Controller;
use models\User;
use requests\UserRequest;

class UserController extends Controller
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new User();
        $this->request = UserRequest::class;
    }

    public function index()
    {
        return $this->view->generate('index.twig');
    }

    public function create()
    {
        return $this->view->generate('create.twig');
    }

    public function store($data)
    {
        $data = $this->request::getPreparedData($data, 'reg');
        $validator = new RegValidator($data);
        if (!$validator->validate()) {
            echo json_encode(array('errors' => $_SESSION['errors']));
            unset($_SESSION['errors']);
        } else {
            if ($this->model->store([$data['name'], $data['email'], $data['password']])) {
                echo json_encode(array('status' => 'ok'));
            } else {
                echo json_encode(array('errors' => ['Ошибка при сохранении в БД']));
            }
        }

    }

    public function edit()
    {
        return $this->view->generate('edit.twig');
    }

    public function update($data)
    {
        $data = $this->request::getPreparedData($data, 'edit');
        $validator = new EditValidator($data);
        if (!$validator->validate()) {
            echo json_encode(array('errors' => $_SESSION['errors']));
            unset($_SESSION['errors']);
        } else {
            if ($this->model->update($newData = [$data['name'], $data['email'], $data['password']], $oldData = [$_SESSION['email'], $_SESSION['password']])) {
                $_SESSION['name'] = $data['name'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['password'] = $data['password'];
                echo json_encode(array('status' => 'ok'));
            } else {
                echo json_encode(array('errors' => $_SESSION['errors']));
                unset($_SESSION['errors']);
            }
        }
    }

    public function auth()
    {
        return $this->view->generate('auth.twig');
    }

    public function check($data)
    {
        $data = $this->request::getPreparedData($data, 'auth');
        $validator = new  AuthValidator($data);
        if (!$validator->validate()) {
            echo json_encode(array('errors' => $_SESSION['errors']));
            unset($_SESSION['errors']);
        } else {
            $user = $this->model->get([$data['email'], $data['password']]);
            if (is_array($user)) {
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['password'] = $user['password'];
                echo json_encode(array('status' => 'ok'));
            } else {
                $_SESSION['errors'][] = $user;
                echo json_encode(array('errors' => $_SESSION['errors']));
                unset($_SESSION['errors']);
            }

        }
    }

    public function exitFromProfile()
    {
        $_SESSION = array();
        header('Location: /');
    }
}