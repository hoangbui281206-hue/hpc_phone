<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

abstract class BaseModel
{
    /**
     * Kết nối cơ sở dữ liệu
     */
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Lấy kết nối database
     */
    public function getConnection(): PDO
    {
        return $this->db;
    }
}