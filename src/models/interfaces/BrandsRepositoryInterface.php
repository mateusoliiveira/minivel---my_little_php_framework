<?php

interface BrandsRepositoryInterface
{
    public function all();
    public function find(string $id);
    public function store(array $body);
    public function update(array $body);
    public function destroy(string $id);
}
