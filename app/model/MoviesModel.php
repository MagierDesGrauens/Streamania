<?php

use \Streamania\Model;

class MoviesModel extends Model
{
    public function __construct()
    {
    }

    public function IndexView()
    {
        return ['movies/index.html.twig', []];
    }
}
