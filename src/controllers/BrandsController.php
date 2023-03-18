<?php

require(__DIR__ . '/../models/repositories/BrandsRepository.php');

class BrandsController
{
    private $repository;

    public function __construct(BrandsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function store($body)
    {
        return $this->repository->store($body);
    }
    public function update($body)
    {
        return $this->repository->update($body);
    }
    public function index()
    {
        return $this->repository->all();
    }
    public function show($id)
    {
        return $this->repository->find($id);
    }
    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }
};
