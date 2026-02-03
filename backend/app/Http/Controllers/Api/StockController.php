<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;

class StockController extends Controller
{
    public function store(StoreStockRequest $request)
    {
        $product = Product::findOrFail($request->product_id);

        if ($product->type === 'simple' && $request->variant_type_id) {
            abort(422, 'Simple products cannot have variant stock');
        }

        if ($product->type === 'variant' && !$request->variant_type_id) {
            abort(422, 'Variant products require variant_type_id');
        }

        $stock = Stock::create([
            'product_id' => $request->product_id,
            'variant_type_id' => $request->variant_type_id,
            'quantity' => $request->quantity,
        ]);

        StockMovement::create([
            'stock_id' => $stock->id,
            'quantity' => $request->quantity,
            'type' => 'in',
            'reason' => 'initial stock',
            'user_id' => auth()->id(),
        ]);

        return response()->json($stock, 201);
    }

    public function update(UpdateStockRequest $request, Stock $stock)
    {
        $newQuantity = $stock->quantity + $request->quantity;

        if ($newQuantity < 0) {
            abort(422, 'Insufficient stock');
        }

        $stock->update([
            'quantity' => $newQuantity,
        ]);

        StockMovement::create([
            'stock_id' => $stock->id,
            'quantity' => $request->quantity,
            'type' => $request->quantity > 0 ? 'in' : 'out',
            'reason' => $request->reason ?? null,
            'user_id' => auth()->id(),
        ]);

        return response()->json($stock);
    }

    public function movements(Stock $stock)
    {
        // $this->authorize('view', $stock);

        return $stock->movements()
            ->with('user')
            ->latest()
            ->paginate(20);
    }
}
