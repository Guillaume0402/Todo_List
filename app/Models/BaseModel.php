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

    /**
     * Initialise la connexion à la base de données
     * Chaque modèle hérite de cette base commune
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Exécute une requête SQL avec des paramètres sécurisés
     * Les paramètres sont automatiquement typés (string, int, bool, null)
     */
    protected function execute(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        // Bind chaque paramètre avec le bon type PDO
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

    /**
     * Récupère plusieurs lignes de résultats
     * Retourne un tableau d'enregistrements (array of arrays)
     */
    protected function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->execute($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Récupère une seule ligne de résultat
     * Retourne un array ou false si aucun résultat
     */
    protected function fetch(string $sql, array $params = []): array|false
    {
        $stmt = $this->execute($sql, $params);
        return $stmt->fetch();
    }

    /**
     * Récupère l'ID du dernier enregistrement inséré
     * Utile après un INSERT pour récupérer l'ID auto-incrémenté
     */
    protected function getLastInsertId(): int
    {
        return (int)$this->db->lastInsertId();
    }
}
