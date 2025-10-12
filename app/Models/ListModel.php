<?php

namespace App\Models;

class ListModel extends BaseModel
{
    public function getByUserId(int $userId, ?int $categoryId = null): array
    {
        $sql = <<<SQL
            SELECT l.*, c.name AS category_name, c.icon AS category_icon
            FROM lists l
            LEFT JOIN categories c ON c.id = l.category_id
            WHERE l.user_id = :user_id
        SQL;
        $params = [':user_id' => $userId];

        if ($categoryId !== null) {
            $sql .= ' AND l.category_id = :category_id';
            $params[':category_id'] = $categoryId;
        }

        return $this->fetchAll($sql, $params);
    }

    public function getById(int $id): array|false
    {
        return $this->fetch('SELECT * FROM lists WHERE id = :id', [':id' => $id]);
    }

    public function save(string $title, int $userId, int $categoryId, ?int $id = null): int|bool
    {
        $title = trim($title);
        if ($id) {
            $stmt = $this->execute(
                'UPDATE lists SET title = :title, category_id = :category_id, user_id = :user_id WHERE id = :id',
                [
                    ':title' => $title,
                    ':category_id' => $categoryId,
                    ':user_id' => $userId,
                    ':id' => $id,
                ]
            );
            return $stmt ? $id : false;
        } else {
            $this->execute(
                'INSERT INTO lists (title, category_id, user_id) VALUES (:title, :category_id, :user_id)',
                [
                    ':title' => $title,
                    ':category_id' => $categoryId,
                    ':user_id' => $userId,
                ]
            );
            return $this->getLastInsertId();
        }
    }
}
