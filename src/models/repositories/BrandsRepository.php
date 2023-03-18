<?php

require(__DIR__ . '/AbstractRepository.php');
require(__DIR__ . '/../interfaces/BrandsRepositoryInterface.php');

/**
 * Summary of ProductRepository
 */
class BrandsRepository extends AbstractRepository implements BrandsRepositoryInterface
{
    protected $table = 'brands';
    protected $fields = [
        'name',
        'picture'
    ];
    public function __construct()
    {
        parent::__construct(new ORM($this->table, $this->fields));
    }
}
