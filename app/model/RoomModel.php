<?php

use \Streamania\Model;
use \Streamania\User;

class RoomModel extends Model
{
    public $rooms;
    public $status;
    public $roomId;
    public $roomVideoSrc;
    public $loggedIn;

    public function IndexView()
    {
        $createStatus = $_GET['createStatus'] ?? 0;
        $roomId = $_GET['id'] ?? -1;

        if ($this->loggedIn !== User::STATE_LOGGED_IN) {
            header('location: ' . WEB_BASE . '?site=user&action=login');
            die();
        }

        if ($this->status === 1) {
            return ['room/room.html.twig', [
                'roomId' => $this->roomId,
                'roomVideoSrc' => $this->roomVideoSrc
            ]];
        } else {
            return ['room/index.html.twig', [
                'status' => $this->status,
                'rooms' => $this->rooms,
                'createStatus' => $createStatus
            ]];
        }
    }

    public function CreateView() {
        if ($this->status !== 1) {
            header('location: ' . WEB_BASE . '?site=room&createStatus=' . $this->status);
        } else {
            header('location: ' . WEB_BASE . '?site=room&id=' . $this->roomId);
        }

        die();
    }
}
