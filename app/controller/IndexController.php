<?php

use \Streamania\Controller;
use \Streamania\User;

class IndexController extends Controller
{
    public function IndexAction()
    {
        $this->model->username = User::getUsername();
    }
}
