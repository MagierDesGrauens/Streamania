<?php

use \Streamania\Model;

class IndexModel extends Model
{
    public $username;

    public function __construct()
    {
        $this->username = '';
    }

    public function IndexView()
    {
        return [
            'index/index.html.twig',
            [
                'username' => $this->username
            ]
        ];
    }
}
