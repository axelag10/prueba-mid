<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\VariantType;

class Variant extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function variantTypes()
    {
        return $this->hasMany(VariantType::class);
    }
}
