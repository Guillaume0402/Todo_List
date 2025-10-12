<?php

namespace App\Models;

/**
 * Modèle pour les utilisateurs
 */

class UserModel extends BaseModel
{
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

    public function getById(int $id): array|false
    {
        return $this->fetch(
            'SELECT * FROM users WHERE id = :id',
            [':id' => $id]
        );
    }

    public function getByEmail(string $email): array|false
    {
        return $this->fetch(
            'SELECT * FROM users WHERE email = :email',
            [':email' => $email]
        );
    }

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
