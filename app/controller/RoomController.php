<?php

use \Streamania\Controller;
use \Streamania\Database;
use \Streamania\User;

class RoomController extends Controller
{
    public function IndexAction()
    {
        $roomId = intval($_GET['id'] ?? '');
        $roomDB = Database::fetchSingle('SELECT rooms_id FROM rooms WHERE rooms_id = ?', [$roomId]);
        $this->model->roomId = -1;
        $this->model->loggedIn = -1;

        if (!empty($roomDB)) {
            $this->model->roomId = $roomDB['rooms_id'];
        }

        if (User::isLoggedIn()) {
            $this->model->loggedIn = User::STATE_LOGGED_IN;
        }
    }
}
