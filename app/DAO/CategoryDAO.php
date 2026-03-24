<?php

namespace App\DAO;

use App\Models\Category;

class CategoryDAO
{
    public function getAll()
    {
        return Category::all();
    }
    public function findById($id)
    {
        return Category::find($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data)
    {
        $category->update($data);
        return $category->fresh();
    }

    public function delete(Category $category)
    {
        return $category->delete();
    }
}
