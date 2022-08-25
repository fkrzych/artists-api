<?php

class Database
{
    public function __construct(private $dbHost, private $dbName, private $dbUser, private $dbPass)
    {
    }

    public function getConnection(): PDO
    {
        $dsn = 'mysql:host='.$this->dbHost.';dbname='.$this->dbName;

        return new PDO($dsn, $this->dbUser, $this->dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}