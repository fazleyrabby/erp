<?php

namespace Database\Seeders;

use App\Models\inventory\Brand;
use App\Models\inventory\Category;
use App\Models\inventory\Product;
use App\Models\inventory\Unit;
use App\Models\inventory\Warehouse;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Electronics 1', 'Metaials'];
        $brands = ['Walton', 'Jamuna'];
        $units = ['pc', 'bundle'];
        $warehouses = ['Agrabad 1', 'GEC Circle 2'];
        foreach ($categories as $key => $value) {
            Category::create([
                'name' => $categories[$key],
                'created_by' => 1,
                'created_date' => date('Y-m-d H:i:s'),
            ]);
            Brand::create([
                'name' => $brands[$key],
                'created_by' => 1,
                'created_date' => date('Y-m-d H:i:s'),
            ]);
            Unit::create([
                'name' => $units[$key],
                'created_by' => 1,
                'created_date' => date('Y-m-d H:i:s'),
            ]);
            Warehouse::create([
                'wareHouseName' => $warehouses[$key],
                'createdBy' => 1,
                'createdDate' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
