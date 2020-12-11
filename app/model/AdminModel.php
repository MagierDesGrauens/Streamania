<?php

use \Streamania\Model;

class AdminModel extends Model
{
    public function __construct()
    {
    }

    public function IndexView()
    {
        return [
            'admin/index.html.twig',
            [
                'name' => 'Admin'
            ]
        ];
    }

    public function UserlistView()
    {
        return [
            'admin/userlist.html.twig',
            [
                'names' => [
                    'Jerome',
                    'Tobias',
                    '3.'
                ]
            ]
        ];
    }
}
