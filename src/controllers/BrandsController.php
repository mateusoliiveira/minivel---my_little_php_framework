<?php

require(__DIR__ . '/../models/repositories/BrandsRepository.php');
require(__dir__ . '/../validations/validator.php');
class BrandsController
{
    private $repository;
    private $validator;

    public function __construct(BrandsRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->validator = new Validator([
            'email' => '[length>20][@]',
            'name' => '[length>5][length<15]',
        ]);
    }
    public function store($body)
    {
        if ($this->validator->validate($body)) {
            echo json_decode('Data is valid!');
            return;
        } else {
            $errs = [];
            echo json_decode('Data is invalid!');
            foreach ($this->validator->getErrors() as $field => $errors) {
                // echo $field . ': ' . implode(', ', $errors) . '<br>';
                $errs[$field] = $errors;
            }
            echo json_encode($errs);
            return;
        }
        //return $this->repository->store($body);
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
