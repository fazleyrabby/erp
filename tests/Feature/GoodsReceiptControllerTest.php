<?php

use App\Models\User;
use App\Models\inventory\Party;
use App\Models\inventory\Product;
use App\Models\inventory\PurchaseOrder;
use App\Models\inventory\PurchaseOrderProduct;
use App\Models\inventory\Warehouse;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Permission;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    Permission::firstOrCreate(['name' => 'purchase.view']);
    $this->user->givePermissionTo(['purchase.view']);
    $this->actingAs($this->user);
    session(['companySettings' => [['name' => 'Test Company', 'currency' => '$', 'barcode_exists' => 'Yes']]]);
});

it('can render the goods receipt index page', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/goods_receipts');
    $response->assertStatus(200);
});

it('can render the goods receipt create page', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/goods_receipts/create');
    $response->assertStatus(200);
});

it('can create a goods receipt from a purchase order', function () {
    $supplier = new Party;
    $supplier->name = 'Test Supplier';
    $supplier->code = 'SUP-' . rand(1000, 9999);
    $supplier->contact = '1234567890';
    $supplier->party_type = 'Supplier';
    $supplier->status = 'Active';
    $supplier->deleted = 'No';
    $supplier->save();

    $product = new Product;
    $product->name = 'Test Product';
    $product->code = 'PROD-' . rand(1000, 9999);
    $product->category_id = 1;
    $product->brand_id = 1;
    $product->unit_id = 1;
    $product->opening_stock = 0;
    $product->purchase_price = 50;
    $product->sale_price = 100;
    $product->type = 'regular';
    $product->status = 'Active';
    $product->deleted = 'No';
    $product->save();

    $warehouse = new Warehouse;
    $warehouse->wareHouseName = 'Test Warehouse';
    $warehouse->wareHouseAddress = 'Test Address';
    $warehouse->save();

    $po = PurchaseOrder::create([
        'po_number' => 'PO-TEST-' . rand(1000, 9999),
        'supplier_id' => $supplier->id,
        'date' => now()->format('Y-m-d'),
        'total_amount' => 100,
        'status' => 'Approved',
        'created_by' => $this->user->id,
    ]);

    $poProduct = PurchaseOrderProduct::create([
        'purchase_order_id' => $po->id,
        'product_id' => $product->id,
        'quantity' => 10,
        'unit_price' => 50,
        'total_price' => 500,
    ]);

    $response = $this->from('/goods_receipts/create')->post('/goods_receipts/store', [
        'purchase_order_id' => $po->id,
        'date' => now()->format('Y-m-d'),
        'products' => [
            $poProduct->id => [
                'purchase_order_product_id' => $poProduct->id,
                'product_id' => $product->id,
                'ordered_quantity' => 10,
                'received_quantity' => 10,
                'unit_price' => 50,
            ],
        ],
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('goods_receipts', [
        'purchase_order_id' => $po->id,
        'status' => 'Received',
    ]);
    $this->assertDatabaseHas('goods_receipt_products', [
        'product_id' => $product->id,
        'received_quantity' => 10,
    ]);
    $product->refresh();
    expect($product->current_stock)->toBe(10);
});
