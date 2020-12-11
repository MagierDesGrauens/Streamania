<?php

use \Streamania\Controller;

class IndexController extends Controller
{
    public function IndexAction()
    {
        $this->model->username = 'Jerome';
    }
}
