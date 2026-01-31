<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    // Listar SOLO categorías padre con hijos
    public function index(Request $request)
    {
        $query = Category::whereNull('parent_id')
            ->with('children');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        return $query->paginate(10);
    }

    // Crear categoría
    public function store(StoreCategoryRequest $request)
    {
        $this->authorize('create', Category::class); //Policy

        return Category::create($request->validated());
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }

    public function show(Category $category)
    {
        return $category->load('children');
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return response()->json([
            'message' => 'Category restored successfully'
        ]);
    }

}
