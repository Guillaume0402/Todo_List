<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOStatement;

/**
 * Modèle de base avec méthodes communes
 */

abstract class BaseModel
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    protected function execute(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $type = PDO::PARAM_STR;
            if (is_int($value)) $type = PDO::PARAM_INT;
            elseif (is_bool($value)) $type = PDO::PARAM_BOOL;
            elseif (is_null($value)) $type = PDO::PARAM_NULL;
            $stmt->bindValue($key, $value, $type);
        }
        $stmt->execute();
        return $stmt;
    }

    protected function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->execute($sql, $params);
        return $stmt->fetchAll();
    }

    protected function fetch(string $sql, array $params = []): array|false
    {
        $stmt = $this->execute($sql, $params);
        return $stmt->fetch();
    }

    protected function getLastInsertId(): int
    {
        return (int)$this->db->lastInsertId();
    }
}
