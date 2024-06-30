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
        var_dump(session_id());
//выделяем уникальный идентификатор сессии
        $id = session_id();

        if ($id!=="") {
            //текущее время
            $CurrentTime = time();
            //через какое время сессии удаляются
            $LastTime = time() - 60;
            //файл, в котором храним идентификаторы и время
            $base = "session.txt";

            $file = file($base);
            $k = 0;
            $ResFile = [];
            for ($i = 0; $i < sizeof($file); $i++) {
                $line = explode("|", $file[$i]);
                if ($line[1] > $LastTime) {
                    $ResFile[$k] = $file[$i];
                    $k++;
                }
            }
            $is_sid_in_file=0;

            for ($i = 0; $i<sizeof($ResFile); $i++) {
                $line = explode("|", $ResFile[$i]);
                if ($line[0]==$id) {
                    $line[1] = trim($CurrentTime)."\n";
                    $is_sid_in_file = 1;
                }
                $line = implode("|", $line); $ResFile[$i] = $line;
            }

            $fp = fopen($base, "w");
            for ($i = 0; $i<sizeof($ResFile); $i++) { fputs($fp, $ResFile[$i]); }
            fclose($fp);

            if (!$is_sid_in_file) {
                $fp = fopen($base, "a-");
                $line = $id."|".$CurrentTime."\n";
                fputs($fp, $line);
                fclose($fp);
            }
        }
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