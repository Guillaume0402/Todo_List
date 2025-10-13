<?php

namespace App\Models;

class ItemModel extends BaseModel
{
    /**
     * Récupère tous les éléments d'une liste triés par position puis par ID
     */
    public function getByListId(int $listId): array
    {
        $sql = 'SELECT id, list_id, name, is_done AS status, position, due_date, created_at FROM items WHERE list_id = :list_id ORDER BY (position IS NULL), position, id';
        return $this->fetchAll($sql, [':list_id' => $listId]);
    }

    /**
     * Sauvegarde ou met à jour un élément (création si $id est null, modification sinon)
     */
    public function save(string $name, int $listId, bool $status = false, ?int $id = null): bool|int
    {
        $name = trim($name);
        if ($id) {
            $stmt = $this->execute(
                'UPDATE items SET name = :name, list_id = :list_id, is_done = :is_done WHERE id = :id',
                [
                    ':name' => $name,
                    ':list_id' => $listId,
                    ':is_done' => $status,
                    ':id' => $id,
                ]
            );
            return (bool)$stmt;
        } else {
            $this->execute(
                'INSERT INTO items (name, list_id, is_done) VALUES (:name, :list_id, :is_done)',
                [
                    ':name' => $name,
                    ':list_id' => $listId,
                    ':is_done' => $status,
                ]
            );
            return $this->getLastInsertId();
        }
    }

    /**
     * Supprime un élément par son ID
     */
    public function deleteById(int $id): bool
    {
        $stmt = $this->execute('DELETE FROM items WHERE id = :id', [':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Met à jour le statut (fait/pas fait) d'un élément
     */
    public function updateStatus(int $id, bool $status): bool
    {
        $stmt = $this->execute('UPDATE items SET is_done = :is_done WHERE id = :id', [
            ':id' => $id,
            ':is_done' => $status,
        ]);
        return $stmt->rowCount() > 0;
    }
}
