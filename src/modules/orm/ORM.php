<?php

require(__DIR__ . '/../db/DB.php');

class ORM
{
    private PDO $db;

    public function __construct()
    {
        $preconnect = new DB();
        $this->db = $preconnect->getDB();
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM brands");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOne(string $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM brands WHERE id=:id");
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function store(array $body)
    {
        $stmt = $this->db->prepare("INSERT INTO brands (name, email) VALUES (:name, :email)");
        $stmt->bindParam(':name', $body['name']);
        $stmt->bindParam(':picture', $body['picture']);
        return $stmt->execute();
    }

    public function update(array $body)
    {
        $stmt = $this->db->prepare("UPDATE brands SET name = :name, picture = :picture WHERE id = :id");
        $stmt->bindParam(':name', $body['name']);
        $stmt->bindParam(':picture', $body['picture']);
        return $stmt->execute();
    }
}
