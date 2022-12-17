<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    public function category(){
    	return $this->belongsTo(Category::class,'category_id','id');

    }

    public function brand(){
    	return $this->belongsTo(Brand::class,'brand_id','id');
    }

    public function unit(){
    	return $this->belongsTo(Unit::class,'unit_id','id');
    }

    public function serializeProducts()
    {
        return $this->hasMany(SerializeProduct::class, 'tbl_productsId');
    }

}
