<?php

/**
 * Modèle pour les catégories
 */

class CategoryModel extends BaseModel
{
    /**
     * Récupère toutes les catégories
     */
    public function getAll(): array
    {
        return $this->fetchAll('SELECT * FROM categories');
    }
}
