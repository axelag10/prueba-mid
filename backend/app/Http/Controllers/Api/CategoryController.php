<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    // Listar SOLO categorías padre con hijos
    public function index(Request $request)
    {
        $query = Category::whereNull('parent_id')
            ->with('children')
            ->whereNull('deleted_at');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        return $query->paginate(10);
    }

    // Crear categoría
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        return Category::create($data);
    }
}
