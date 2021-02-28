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
        $username = $_POST['username'] ?? '';
        $mail = $_POST['mail'] ?? '';
        $pass = $_POST['password'] ?? '';

        if (User::isLoggedIn()) {
            $this->model->status = User::STATE_LOGGED_IN;
        } else {
            if (!empty($mail) || !empty($pass) || !empty($username)) {
                if (empty($mail) || empty($pass) || empty($username)) {
                    $this->model->status = User::STATE_REGISTER_EMPTY;
                } else {
                    User::fetchByMail($mail);

                    if (!User::exists()) {
                        User::fetchByName($username);

                        if (!User::exists()) {
                            User::register($username, $mail, $pass);

                            $this->model->status = User::STATE_REGISTER_SUCCESS;
                        } else {
                            $this->model->status = User::STATE_REGISTER_EXISTS_NAME;
                        }
                    } else {
                        $this->model->status = User::STATE_REGISTER_EXISTS_MAIL;
                    }
                }
            }
        }
    }

    public function LogoutAction()
    {
        
    }

    public function FriendslistAction()
    {
        
    }
}
