<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Variant;

class VariantType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'variant_id',
    ];

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_variant_types')
            ->withPivot(['price', 'cost'])
            ->withTimestamps();
    }
}
