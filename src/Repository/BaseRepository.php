<?php

namespace App\Repository;

use App\Database\Database;
use PDO;

abstract class BaseRepository
{

    protected PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
}
