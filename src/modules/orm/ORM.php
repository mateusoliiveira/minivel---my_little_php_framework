<?php

require(__DIR__ . '/../db/DB.php');

class UUID
{
    public static function v4()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}

class ORM
{
    private PDO $db;

    public function __construct()
    {
        $preconnect = new DB();
        $this->db = $preconnect->getDB();
    }

    public function all()
    {
        $stmt = $this->db->prepare("SELECT * FROM brands");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(string $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM brands WHERE id=:id");
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function store(array $body)
    {
        $timestamp = $this->timestamp();
        $stmt = $this->db->prepare("INSERT INTO brands (id, name, picture, created_at, updated_at) VALUES (:id, :name, :picture, :created_at, :updated_at)");
        $stmt->bindValue(':id', $this->uuid());
        $stmt->bindParam(':name', $body['name']);
        $stmt->bindParam(':picture', $body['picture']);
        $stmt->bindParam(':created_at', $timestamp);
        $stmt->bindParam(':updated_at', $timestamp);
        return $stmt->execute();
    }

    public function update(array $body)
    {
        $timestamp = $this->timestamp();
        $stmt = $this->db->prepare("UPDATE brands SET name = :name, picture = :picture, updated_at = :updated_at WHERE id = :id");
        $stmt->bindParam(':id', $body['id']);
        $stmt->bindParam(':name', $body['name']);
        $stmt->bindParam(':picture', $body['picture']);
        $stmt->bindParam(':updated_at', $timestamp);
        return $stmt->execute();
    }

    public function destroy(string $id)
    {
        $stmt = $this->db->prepare("DELETE FROM brands WHERE id=:id");
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function uuid()
    {
        return UUID::v4();
    }

    public function timestamp()
    {
        return date('Y-m-d H:i:s');
    }
}
