<?php
class ORM
{
    private $host;
    private $username;
    private $password;
    private $dbname;

    public function __construct()
    {
        $this->host = 'motty.db.elephantsql.com';
        $this->dbname = 'hadxnnzm';
        $this->username = 'hadxnnzm';
        $this->password = 'Xm9g3_UYYKX4Hg9W6aD6MEFrns7VOeVx';
    }

    public function connect(): PDO
    {
        $dsn = "pgsql:host={$this->host};dbname={$this->dbname}";
        $pdo = new PDO($dsn, $this->username, $this->password);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }

    public function __getConnect(): PDO
    {
        return $this->connect();
    }
    // other methods to interact with the users table
}
