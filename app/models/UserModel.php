<?php

/**
 * Modèle pour les utilisateurs
 */

class UserModel extends BaseModel
{
    /**
     * Vérifie les identifiants de connexion
     */
    public function verifyLogin(string $email, string $password): array|false
    {
        $user = $this->fetch(
            'SELECT * FROM users WHERE email = :email',
            [':email' => $email]
        );

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Récupère un utilisateur par son ID
     */
    public function getById(int $id): array|false
    {
        return $this->fetch(
            'SELECT * FROM users WHERE id = :id',
            [':id' => $id]
        );
    }

    /**
     * Récupère un utilisateur par son email
     */
    public function getByEmail(string $email): array|false
    {
        return $this->fetch(
            'SELECT * FROM users WHERE email = :email',
            [':email' => $email]
        );
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function create(string $email, string $password, ?string $displayName = null): int
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $this->execute(
            'INSERT INTO users (email, password, display_name, created_at) VALUES (:email, :password, :display_name, NOW())',
            [
                ':email' => $email,
                ':password' => $hashedPassword,
                ':display_name' => $displayName
            ]
        );

        return $this->getLastInsertId();
    }

    /**
     * Met à jour les informations d'un utilisateur
     */
    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            $fields[] = "{$key} = :{$key}";
            $params[":{$key}"] = $value;
        }

        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $this->execute($sql, $params);

        return $stmt->rowCount() > 0;
    }
}
