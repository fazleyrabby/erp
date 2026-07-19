<?php

namespace App\Models\inventory;

use App\Models\inventory\Brand;
use App\Models\inventory\Category;
use App\Models\inventory\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'products';

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');

    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function serializeProducts()
    {
        return $this->hasMany(SerializeProduct::class, 'tbl_productsId');
    }

    public function getCategoryNameAttribute()
    {
        return $this->category->name ?? '';
    }

    public function getBrandNameAttribute()
    {
        return $this->brand->name ?? '';
    }

    public function getUnitNameAttribute()
    {
        return $this->unit->name ?? '';
    }
}
