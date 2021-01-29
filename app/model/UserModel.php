<?php

use \Streamania\Model;

class UserModel extends Model
{
    public function __construct()
    {
    }

    public function LoginView()
    {
        return ['user/login.html.twig', []];
    }

    public function RegisterView()
    {
        return ['user/register.html.twig', []];
    }

    public function LogoutView()
    {
        return ['user/logout.html.twig', []];
    }

    public function FriendslistView()
    {
        return ['user/friendslist.html.twig', []];
    }
}
