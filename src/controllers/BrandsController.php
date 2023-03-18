<?php

require(__DIR__ . '/../models/repositories/BrandsRepository.php');

class BrandsController
{
    protected $instance;
    protected $model;

    public function __construct()
    {
        $this->model = new BrandsRepository();
    }

    public function index()
    {
        return $this->model->getAll();
    }
    public function show($id)
    {
        return $this->model->getOne($id);
    }
    protected function app()
    {
        if (!$this->instance) {
            $this->instance = new BrandsController();
            return $this->instance;
        }

        return $this->instance;
    }
};
