<?php

interface ProductsRepositoryInterface
{
    public function getAll();
    public function getOne(string $id);
    public function store(array $body);
    public function update(array $body);
}
