<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Stock extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'variant_type_id',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variantType()
    {
        return $this->belongsTo(VariantType::class);
    }

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
