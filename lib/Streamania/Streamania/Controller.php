<?php

namespace Streamania;

use \Streamania\Model;

class Controller {
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}
