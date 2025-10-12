<?php

namespace App\Models;

class CategoryModel extends BaseModel
{
    public function getAll(): array
    {
        return $this->fetchAll('SELECT * FROM categories');
    }
}
