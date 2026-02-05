<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

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
            'variantTypes'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $product->update($request->only([
            'sku',
            'name',
            'description',
            'provider_id',
        ]));

        if ($request->has('type')) { // Validacion extra
            abort(422, 'Product type cannot be changed');
        }

        // Categorías
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        // Variant types con precio y costo.
        if ($request->has('variant_types')) {
            $syncData = [];

            foreach ($request->variant_types as $variant) {
                $syncData[$variant['id']] = [
                    'price' => $variant['price'],
                    'cost'  => $variant['cost'],
                ];
            }

            $product->variantTypes()->sync($syncData);
        }

        return $product->load(['categories', 'variantTypes']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $product);

        $product->restore();

        return response()->json([
            'message' => 'Product restored successfully'
        ]);
    }
}
