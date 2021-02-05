<?php

use \Streamania\Model;

class MoviesModel extends Model
{
    public $movies;

    public function IndexView()
    {
        return ['movies/index.html.twig', ['movies' => $this->movies]];
    }
}
