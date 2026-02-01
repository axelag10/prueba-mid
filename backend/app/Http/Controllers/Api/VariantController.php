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
    public function index()
    {
        $this->authorize('viewAny', Variant::class);
        return Variant::paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVariantRequest $request)
    {
        $this->authorize('create', Variant::class);
        return Variant::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Variant $variant)
    {
        $this->authorize('view', $variant);
        return $variant;
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
