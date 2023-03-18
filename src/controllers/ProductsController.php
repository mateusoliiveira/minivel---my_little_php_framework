<?php

require(__DIR__ . '/../models/repositories/ProductsRepository.php');

class ProductsController
{
    protected $instance;
    protected $model;

    public function __construct()
    {
        $this->model = new ProductsRepository();
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
            $this->instance = new ProductsController();
            return $this->instance;
        }

        return $this->instance;
    }
};
