<?php

namespace App\Core;

use PDO;
use PDOException;
use Exception;

class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    \DatabaseConfig::getDsn(),
                    \DatabaseConfig::USER,
                    \DatabaseConfig::PASS,
                    \DatabaseConfig::getPdoOptions()
                );
            } catch (PDOException $e) {
                throw new Exception('Erreur de connexion à la base de données : ' . $e->getMessage());
            }
        }
        return self::$instance;
    }

    public function __clone()
    {
        throw new Exception('Le clonage de Database n\'est pas autorisé');
    }
    public function __wakeup()
    {
        throw new Exception('La désérialisation de Database n\'est pas autorisée');
    }
}
