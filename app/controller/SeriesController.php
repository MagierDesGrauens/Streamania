<?php

use \Streamania\Controller;
use \Streamania\Database;

class SeriesController extends Controller
{
    public function IndexAction()
    {
        $this->model->series = Database::fetch('SELECT * FROM videos WHERE type="series"');
    }
}
