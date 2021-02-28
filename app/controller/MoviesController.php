<?php

use \Streamania\Controller;
use \Streamania\Database;

class MoviesController extends Controller
{
    public function IndexAction()
    {
        $this->model->movies = Database::fetch('SELECT * FROM videos WHERE type="movie"');
    }
}
