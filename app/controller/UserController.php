<?php

use \Streamania\Controller;
use \Streamania\Database;
use \Streamania\User;

class UserController extends Controller
{
    public function LoginAction()
    {
        $mail = $_POST['mail'] ?? '';
        $pass = $_POST['password'] ?? '';

        if (User::isLoggedIn()) {
            $this->model->status = User::STATE_ALREADY_LOGGED_IN;
        } else {
            if (!empty($mail) || !empty($pass)) {
                User::fetchByMailPassword($mail, $pass);

                if (User::exists()) {
                    User::login();

                    $this->model->status = User::STATE_LOGGED_IN;
                } else {
                    $this->model->status = User::STATE_LOGIN_FAILED;
                }
            }
        }
    }

    public function RegisterAction()
    {
        
    }

    public function LogoutAction()
    {
        
    }

    public function FriendslistAction()
    {
        
    }
}
