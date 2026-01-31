<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Provider;
use App\Http\Requests\StoreProviderRequest;
use App\Http\Requests\UpdateProviderRequest;


class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Provider::class);

        return Provider::paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProviderRequest $request)
    {
        $this->authorize('create', Provider::class);

        return Provider::create($request->validated());
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
    public function update(UpdateProviderRequest $request, Provider $provider)
    {
        $this->authorize('update', $provider);

        $provider->update($request->validated());

        return $provider;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Provider $provider)
    {
        $this->authorize('delete', $provider);

        $provider->delete();

        return response()->json([
            'message' => 'Provider deleted successfully'
        ]);
    }

    public function restore($id)
    {
        $provider = Provider::onlyTrashed()->findOrFail($id);

        $this->authorize('restore', $provider);

        $provider->restore();

        return response()->json([
            'message' => 'Provider restored successfully'
        ]);
    }
}
