<?php

use \Streamania\Controller;
use \Streamania\Database;
use \Streamania\User;

class RoomController extends Controller
{
    public function IndexAction()
    {
        $roomId = $_GET['id'] ?? '';
        $roomDB = [];

        $this->model->status = 0;
        $this->model->loggedIn = -1;
        $this->model->roomId = -1;
        $this->model->rooms = [];

        if (!empty($roomId)) {
            $roomDB = Database::fetchSingle('SELECT rooms_id, video_src FROM rooms WHERE rooms_id = ?', [$roomId]);

            if (!empty($roomDB)) {
                $this->model->roomId = $roomDB['rooms_id'];
                $this->model->roomVideoSrc = $roomDB['video_src'];
                $this->model->status = 1;
            } else {
                $this->model->status = -1;
            }
        } else {
            $roomDB = Database::fetch(
                'SELECT rooms.rooms_id,
                        rooms.name,
                        users.username
                FROM rooms LEFT JOIN rooms_users USING(rooms_id) LEFT JOIN users USING(users_id)'
            );

            foreach ($roomDB as $room) {
                if (empty($this->model->rooms[$room['rooms_id']]['name'])) {
                    $this->model->rooms[$room['rooms_id']]['name'] = $room['name'];
                }

                $this->model->rooms[$room['rooms_id']]['users'][] = $room['username'];
            }
        }

        if (User::isLoggedIn()) {
            $this->model->loggedIn = User::STATE_LOGGED_IN;
        }
    }

    public function CreateAction() {
        $name = $_POST['name'] ?? '';
        $roomId = 0;

        if (!empty($name)) {
            $roomDB = Database::fetchSingle('SELECT name FROM rooms WHERE name = ?', [$name]);

            if (!empty($roomDB)) {
                $this->model->status = -2;
            } else {
                $roomId = Database::fetchSingle(
                    'INSERT INTO rooms (name, video_src, video_state) VALUES (?, "JdgcCdXHb9U", "stopped")',
                    [$name]
                );

                $this->model->roomId = $roomId['lastInsertId'];
                $this->model->status = 1;
            }
        } else {
            $this->model->status = -1;
        }
    }
}
