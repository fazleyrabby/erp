<?php

namespace Database\Seeders;

use App\Models\inventory\Brand;
use App\Models\inventory\Category;
use App\Models\inventory\Currentstock;
use App\Models\inventory\Product;
use App\Models\inventory\Unit;
use App\Models\inventory\Warehouse;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Electronics', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Furniture', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Groceries', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Stationery', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Clothing', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Services', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }

        $brands = [
            ['name' => 'Samsung', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Apple', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Sony', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Hatil', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Otobi', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Pran', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(['name' => $brand['name']], $brand);
        }

        $units = [
            ['name' => 'Pcs', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Box', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Kg', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Liter', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Meter', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Bundle', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(['name' => $unit['name']], $unit);
        }

        Warehouse::firstOrCreate(
            ['wareHouseName' => 'Main Warehouse'],
            ['wareHouseAddress' => 'Dhaka', 'status' => 'Active', 'deleted' => 'No', 'createdBy' => 1]
        );
        Warehouse::firstOrCreate(
            ['wareHouseName' => 'Chittagong Warehouse'],
            ['wareHouseAddress' => 'Chittagong', 'status' => 'Active', 'deleted' => 'No', 'createdBy' => 1]
        );

        $catIds = Category::pluck('id', 'name');
        $brandIds = Brand::pluck('id', 'name');
        $unitIds = Unit::pluck('id', 'name');

        $products = [
            ['name' => 'Smart TV 43 Inch', 'code' => 'PROD-001', 'category_id' => $catIds['Electronics'], 'brand_id' => $brandIds['Samsung'], 'unit_id' => $unitIds['Pcs'], 'purchase_price' => 45000, 'sale_price' => 52000, 'opening_stock' => 20, 'type' => 'regular'],
            ['name' => 'iPhone 15 Pro', 'code' => 'PROD-002', 'category_id' => $catIds['Electronics'], 'brand_id' => $brandIds['Apple'], 'unit_id' => $unitIds['Pcs'], 'purchase_price' => 95000, 'sale_price' => 115000, 'opening_stock' => 15, 'type' => 'serialize'],
            ['name' => 'Sony Headphones', 'code' => 'PROD-003', 'category_id' => $catIds['Electronics'], 'brand_id' => $brandIds['Sony'], 'unit_id' => $unitIds['Pcs'], 'purchase_price' => 2500, 'sale_price' => 3500, 'opening_stock' => 50, 'type' => 'regular'],
            ['name' => 'Office Desk', 'code' => 'PROD-004', 'category_id' => $catIds['Furniture'], 'brand_id' => $brandIds['Hatil'], 'unit_id' => $unitIds['Pcs'], 'purchase_price' => 8500, 'sale_price' => 12000, 'opening_stock' => 10, 'type' => 'regular'],
            ['name' => 'Executive Chair', 'code' => 'PROD-005', 'category_id' => $catIds['Furniture'], 'brand_id' => $brandIds['Otobi'], 'unit_id' => $unitIds['Pcs'], 'purchase_price' => 6500, 'sale_price' => 9500, 'opening_stock' => 25, 'type' => 'regular'],
            ['name' => 'Cooking Oil 5L', 'code' => 'PROD-006', 'category_id' => $catIds['Groceries'], 'brand_id' => $brandIds['Pran'], 'unit_id' => $unitIds['Liter'], 'purchase_price' => 800, 'sale_price' => 950, 'opening_stock' => 100, 'type' => 'regular'],
            ['name' => 'Basmati Rice 1Kg', 'code' => 'PROD-007', 'category_id' => $catIds['Groceries'], 'brand_id' => $brandIds['Pran'], 'unit_id' => $unitIds['Kg'], 'purchase_price' => 120, 'sale_price' => 150, 'opening_stock' => 200, 'type' => 'regular'],
            ['name' => 'A4 Paper Box', 'code' => 'PROD-008', 'category_id' => $catIds['Stationery'], 'brand_id' => $brandIds['Samsung'], 'unit_id' => $unitIds['Box'], 'purchase_price' => 1800, 'sale_price' => 2200, 'opening_stock' => 30, 'type' => 'regular'],
            ['name' => 'Printer Ink Cartridge', 'code' => 'PROD-009', 'category_id' => $catIds['Stationery'], 'brand_id' => $brandIds['Sony'], 'unit_id' => $unitIds['Pcs'], 'purchase_price' => 1200, 'sale_price' => 1600, 'opening_stock' => 40, 'type' => 'regular'],
            ['name' => 'T-Shirt (Cotton)', 'code' => 'PROD-010', 'category_id' => $catIds['Clothing'], 'brand_id' => $brandIds['Pran'], 'unit_id' => $unitIds['Pcs'], 'purchase_price' => 350, 'sale_price' => 550, 'opening_stock' => 150, 'type' => 'regular'],
            ['name' => 'Installation Service', 'code' => 'SVC-001', 'category_id' => $catIds['Services'], 'brand_id' => $brandIds['Samsung'], 'unit_id' => $unitIds['Pcs'], 'purchase_price' => 0, 'sale_price' => 2000, 'opening_stock' => 0, 'type' => 'service'],
            ['name' => 'Repair Service', 'code' => 'SVC-002', 'category_id' => $catIds['Services'], 'brand_id' => $brandIds['Apple'], 'unit_id' => $unitIds['Pcs'], 'purchase_price' => 0, 'sale_price' => 1500, 'opening_stock' => 0, 'type' => 'service'],
        ];

        foreach ($products as $prod) {
            Product::firstOrCreate(
                ['name' => $prod['name']],
                array_merge($prod, [
                    'status' => 'Active',
                    'deleted' => 'No',
                    'created_by' => 1,
                    'current_stock' => $prod['opening_stock'],
                ])
            );
        }

        $warehouses = Warehouse::pluck('id');
        $allProducts = Product::all();

        foreach ($allProducts as $product) {
            if ($product->opening_stock > 0) {
                foreach ($warehouses as $whId) {
                    Currentstock::create([
                        'tbl_productsId' => $product->id,
                        'tbl_wareHouseId' => $whId,
                        'currentStock' => ($whId == $warehouses->first()) ? $product->opening_stock : 0,
                        'initialStock' => ($whId == $warehouses->first()) ? $product->opening_stock : 0,
                        'purchaseStock' => ($whId == $warehouses->first()) ? $product->opening_stock : 0,
                        'salesStock' => 0,
                        'purchaseReturnStock' => 0,
                        'salesReturnStock' => 0,
                        'purchaseDelete' => 0,
                        'salesDelete' => 0,
                        'transferFrom' => 0,
                        'transferTo' => 0,
                        'transferFromDelete' => 0,
                        'transferToDelete' => 0,
                    ]);
                }
            }
        }
    }
}
