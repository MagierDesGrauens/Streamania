<?php

use \Streamania\Model;

class SeriesModel extends Model
{
    public $series;

    public function IndexView()
    {
        return ['series/index.html.twig', ['series' => $this->series]];
    }
}
