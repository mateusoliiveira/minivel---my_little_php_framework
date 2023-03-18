<?php

require(__DIR__ . '/../../modules/orm/orm.php');

abstract class AbstractRepository
{
    private $orm;

    public function __construct(ORMInterface $orm)
    {
        $this->orm = $orm;
    }

    public function all()
    {
        return $this->orm->all();
    }
    public function find(string $id)
    {
        return $this->orm->find($id);
    }
    public function store(array $body)
    {
        return $this->orm->store($body);
    }
    public function update(array $body)
    {
        return $this->orm->update($body);
    }
    public function destroy(string $id)
    {
        return $this->orm->destroy($id);
    }

    public function getModel()
    {
        return $this;
    }
}
