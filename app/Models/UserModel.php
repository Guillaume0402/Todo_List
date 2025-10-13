<?php

namespace App\Models;

/**
 * Modèle pour les utilisateurs
 */

class UserModel extends BaseModel
{
    /**
     * Vérifie les identifiants de connexion
     * Compare le mot de passe avec le hash stocké en BDD
     */
    // app/Models/UserModel.php (exemple)
    public function verifyLogin(string $email, string $password): array|false
{
    // 1) Récup de l'utilisateur
    $user = $this->fetch(
        'SELECT * FROM users WHERE email = :email LIMIT 1',
        [':email' => $email]
    );

    // 2) Vérif du password
    if (!$user || !password_verify($password, $user['password'])) {
        return false;
    }

    // 3) Rehash si nécessaire (si PASSWORD_DEFAULT a évolué)
    if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $this->execute(
            'UPDATE users SET password = :hash WHERE id = :id',
            [':hash' => $newHash, ':id' => (int)$user['id']]
        );
        $user['password'] = $newHash; // garder à jour la valeur en mémoire
    }

    return $user;
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
     * Sauvegarde un nouvel utilisateur en base de données
     */
    public function save(array $data): bool
    {
        $stmt = $this->execute(
            'INSERT INTO users (email, password, display_name, created_at) 
             VALUES (:email, :password, :display_name, NOW())',
            [
                ':email' => $data['email'],
                ':password' => $data['password'],
                ':display_name' => $data['display_name'] ?? null,
            ]
        );
        return $stmt !== false;
    }

    /**
     * Met à jour les informations d'un utilisateur
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->execute(
            'UPDATE users SET email = :email WHERE id = :id',
            [
                ':email' => $data['email'],
                ':id' => $id
            ]
        );
        return $stmt !== false;
    }
}
