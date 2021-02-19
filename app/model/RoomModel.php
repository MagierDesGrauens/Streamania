<?php

use \Streamania\Model;
use \Streamania\User;

class RoomModel extends Model
{
    public $roomId;
    public $loggedIn;

    public function IndexView()
    {
        if ($this->loggedIn !== User::STATE_LOGGED_IN) {
            header('location: ' . WEB_BASE . '?site=user&action=login');
            die();
        }

        return ['room/index.html.twig', ['roomId' => $this->roomId]];
    }
}
