<?php

namespace App\Http\Controllers;

use App\DAO\CategoryDAO;
use App\DTO\CategoryDTO;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryDAO;

    public function __construct(CategoryDAO $categoryDAO)
    {
        $this->categoryDAO = $categoryDAO;
    }

    public function index()
    {
        return response()->json($this->categoryDAO->getAll());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:categories|max:255'
        ]);

        $dto = new CategoryDTO($validatedData['name']);

        $category = $this->categoryDAO->create($dto->toArray());

        return response()->json([
            'message' => 'Category Created Successfully!',
            'data' => $category
        ], 201);
    }

    public function destroy($id)
    {
        $category = $this->categoryDAO->findById($id);
        if (!$category) return response()->json(['message' => 'Not found'], 404);

        $this->categoryDAO->delete($category);
        return response()->json(['message' => 'Category deleted successfully']);
    }

    public function show($id)
    {
        $category = $this->categoryDAO->findById($id);
        if (!$category) return response()->json(['message' => 'Category not found'], 404);

        return response()->json([
            'status' => 'success',
            'data' => $category,

        ], 200);
    }


    public function update(Request $request, $id)
    {
        $category = $this->categoryDAO->findById($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found!'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id
        ]);


        $dto = new CategoryDTO($validatedData['name']);


        $updatedCategory = $this->categoryDAO->update($category, $dto->toArray());

        $category->refresh();

        return response()->json([
            'message' => 'Category updated successfully!',
            'data' => $updatedCategory
        ], 200);
    }
}
