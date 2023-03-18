<?php

require(__DIR__ . '/ORM.php');

class AbstractRepository
{
    private $db;
    public function __construct()
    {
        $this->db = new ORM;
    }

    public function getAll()
    {
        $pdo = $this->db->connect();
        //$stmt = $pdo->prepare("SELECT * FROM brands WHERE id=:id");
        $stmt = $pdo->prepare("SELECT * FROM brands");
        //$stmt->execute(['id' => $id]);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOne(string $id)
    {
        $pdo = $this->db->connect();
        $stmt = $pdo->prepare("SELECT * FROM brands WHERE id=:id");
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function store(array $body)
    {
        $pdo = $this->db->connect();
        $stmt = $pdo->prepare("INSERT INTO brands (name, email) VALUES (:name, :email)");
        $stmt->bindParam(':name', $body['name']);
        $stmt->bindParam(':picture', $body['picture']);
        return $stmt->execute();
    }

    public function update(array $body)
    {
        $pdo = $this->db->connect();
        $stmt = $pdo->prepare("UPDATE brands SET name = :name, picture = :picture WHERE id = :id");
        $stmt->bindParam(':name', $body['name']);
        $stmt->bindParam(':picture', $body['picture']);
        return $stmt->execute();
    }
}
