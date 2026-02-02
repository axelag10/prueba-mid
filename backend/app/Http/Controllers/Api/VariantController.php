<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Variant;
use App\Http\Requests\StoreVariantRequest;
use App\Http\Requests\UpdateVariantRequest;

class VariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Variant::class);
        $query = Variant::with('variantTypes');

        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhereHas('variantTypes', function ($t) use ($request) {
                  $t->where('name', 'like', "%{$request->search}%");
              });
        });

        return $query->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVariantRequest $request)
    {
        $this->authorize('create', Variant::class);
        
        $variant = Variant::create([
            'name' => $request->name,
        ]);

        foreach ($request->types as $type) {
            $variant->variantTypes()->create([
                'name' => $type
            ]);
        }

        return $variant->load('variantTypes');
    }

    /**
     * Display the specified resource.
     */
    public function show(Variant $variant)
    {
        $this->authorize('view', $variant);
        return $variant->load('variantTypes');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVariantRequest $request, Variant $variant)
    {
        $this->authorize('update', $variant);
        $variant->update($request->validated());
        return $variant;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Variant $variant)
    {
        $this->authorize('delete', $variant);
        // $variant->variantTypes()->delete();
        $variant->delete();

        return response()->json([
            'message' => 'Variant deleted successfully'
        ]);
    }

    public function restore($id)
    {
        $variant = Variant::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $variant);
        $variant->restore();

        return response()->json([
            'message' => 'Variant restored successfully'
        ]);
    }
}
