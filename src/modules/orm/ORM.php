<?php

require(__DIR__ . '/../db/db.php');
require(__DIR__ . '/../../utils/uuid.php');
interface ORMInterface
{
    public function all();
    public function find(string $id);
    public function store(array $data);
    public function update(array $data);
    public function destroy(string $id);
}

class ORM implements ORMInterface
{
    private PDO $db;
    protected string $table;
    protected array $fields;

    public function __construct($table, $fields)
    {
        $this->table = $table;
        $this->fields = $fields;
        $preconnect = new DB();
        $this->db = $preconnect->getDB();
    }

    public function all()
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(string $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE id=:id");
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function store(array $body)
    {
        $count = count($this->fields);
        $extract = [];

        for ($i = 0; $i < $count; $i++) {
            $extract = [
                ...$extract,
                $this->fields[$i],
            ];
        }

        $timestamp = $this->timestamp();
        $stmt = $this->db->prepare("
        INSERT INTO $this->table 
            (id, "
            . $this->format_fields('names') . " 
            created_at, 
            updated_at) 
        VALUES 
            (:id, "
            . $this->format_fields('values') . "
             :created_at, 
             :updated_at)");

        $stmt->bindValue(':id', $this->uuid());
        for ($i = 0; $i < $count; $i++) {
            $stmt->bindParam(":$extract[$i]", $body[$extract[$i]]);
        }
        $stmt->bindParam(':created_at', $timestamp);
        $stmt->bindParam(':updated_at', $timestamp);
        return $stmt->execute();
    }

    public function update(array $body)
    {
        $timestamp = $this->timestamp();
        $stmt = $this->db->prepare("UPDATE $this->table SET name = :name, picture = :picture, updated_at = :updated_at WHERE id = :id");
        $stmt->bindParam(':id', $body['id']);
        $stmt->bindParam(':name', $body['name']);
        $stmt->bindParam(':picture', $body['picture']);
        $stmt->bindParam(':updated_at', $timestamp);
        return $stmt->execute();
    }

    public function destroy(string $id)
    {
        $stmt = $this->db->prepare("DELETE FROM $this->table WHERE id=:id");
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function format_fields(string $mode)
    {

        $count = count($this->fields);
        $extract = [];
        for ($i = 0; $i < $count; $i++) {
            $extract = [
                ...$extract,
                $this->fields[$i],
            ];
        }
        if ($mode === 'values') {
            $pattern = "";
            for ($i = 0; $i < $count; $i++) {
                $pattern .= ":$extract[$i], ";
                if ($i == $count) {
                    $pattern .= " ";
                }
            }
            return $pattern;
        } else {
            $pattern = "";
            for ($i = 0; $i < $count; $i++) {
                $pattern .= "$extract[$i], ";
                if ($i != $count) {
                    $pattern .= " ";
                }
            }
            return $pattern;
        }
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
