<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['categories', 'provider']);

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('sku', 'like', "%{$request->search}%");
        }

        return $query->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $this->authorize('create', Product::class);

        $product = Product::create($request->validated());

        $product->categories()->sync($request->categories);

        if ($product->type === 'variant') {
            foreach ($request->variant_types as $type) {
                $product->variantTypes()->attach($type['id'], [
                    'price' => $type['price'],
                    'cost' => $type['cost'],
                ]);
            }
        }

        return response()->json(
            $product->load(['categories', 'variantTypes']),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $this->authorize('view', $product);

        return $product->load([
            'categories',
            'provider',
            'variants.variantTypes'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
