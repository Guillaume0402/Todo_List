<?php

namespace App\Models;

class CategoryModel extends BaseModel
{
    /**
     * Récupère toutes les catégories disponibles
     */
    public function getAll(): array
    {
        return $this->fetchAll('SELECT * FROM categories');
    }
}
