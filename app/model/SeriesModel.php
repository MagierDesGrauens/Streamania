<?php

use \Streamania\Model;

class SeriesModel extends Model
{
    public function __construct()
    {
    }

    public function IndexView()
    {
        return ['series/index.html.twig', []];
    }
}
