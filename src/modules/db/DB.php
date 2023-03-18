<?php
require(__DIR__ . '/./conn.php');

class DB
{
    private $config;

    public function __construct()
    {
        $this->config = DBConfiguration::get();
    }

    public function connect(): PDO
    {
        $dsn = "pgsql:host={$this->config['host']};dbname={$this->config['dbname']}";
        $pdo = new PDO($dsn, $this->config['username'], $this->config['password']);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }

    public function getDB(): PDO
    {
        return $this->connect();
    }
}
