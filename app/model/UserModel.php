<?php

use \Streamania\Model;
use \Streamania\User;

class UserModel extends Model
{
    public $status = 0;

    public function __construct()
    {
    }

    public function LoginView()
    {
        if (
            $this->status === User::STATE_ALREADY_LOGGED_IN
            || $this->status === User::STATE_LOGGED_IN
        ) {
            header('Location: ' . WEB_BASE);
            die();
        }

        return ['user/login.html.twig', ['status' => $this->status]];
    }

    public function RegisterView()
    {
        return ['user/register.html.twig', []];
    }

    public function LogoutView()
    {
        User::logout();

        header('Location: ' . WEB_BASE . '?site=user&action=login');
    }

    public function FriendslistView()
    {
        return ['user/friendslist.html.twig', []];
    }
}
