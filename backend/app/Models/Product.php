<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'cost',
        'type',
        'provider_id'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function variantTypes()
    {
        return $this->belongsToMany(VariantType::class, 'product_variant_types')
            ->withPivot(['price', 'cost'])
            ->withTimestamps();
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
