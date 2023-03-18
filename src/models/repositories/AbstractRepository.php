<?php

require(__DIR__ . '/../../modules/orm/ORM.php');

class AbstractRepository
{
    private $orm;
    public function __construct()
    {
        $this->orm = new ORM;
    }

    public function getAll()
    {
        return $this->orm->getAll();
    }
    public function getOne(string $id)
    {
        return $this->orm->getOne($id);
    }
    public function store(array $body)
    {
        return $this->orm->store($body);
    }
    public function update(array $body)
    {
        return $this->orm->update($body);
    }

    public function getRepository()
    {
        return $this->orm;
    }
}
