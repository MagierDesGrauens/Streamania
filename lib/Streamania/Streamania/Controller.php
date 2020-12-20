<?php

namespace Streamania;

use \Streamania\Model;

/**
 * Klasse Controller
 */
class Controller {
    /**
     * @var Model
     */
    protected $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}
