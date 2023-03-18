<?php

interface BrandsRepositoryInterface
{
    public function getAll();
    public function getOne(string $id);
    public function store(array $body);
    public function update(array $body);
    public function destroy(string $id);
}
