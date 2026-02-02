<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\VariantType;
use App\Http\Requests\UpdateVariantTypeRequest;

class VariantTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVariantTypeRequest $request, VariantType $variantType)
    {
        $this->authorize('update', $variantType);

        $variantType->update($request->validated());

        return $variantType;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VariantType $variantType)
    {
        $this->authorize('delete', $variantType);

        $variantType->delete();

        return response()->json([
            'message' => 'Variant type deleted successfully'
        ]);
    }

    public function restore($id)
    {
        $variantType = VariantType::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $variantType);
        $variantType->restore();

        return response()->json([
            'message' => 'Variant type restored successfully'
        ]);
    }
}
