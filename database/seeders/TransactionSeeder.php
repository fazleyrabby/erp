<?php

namespace Database\Seeders;

use App\Models\inventory\Invoice;
use App\Models\inventory\InvoiceItem;
use App\Models\inventory\Party;
use App\Models\inventory\Product;
use App\Models\inventory\Purchase;
use App\Models\inventory\Warehouse;
use App\Models\inventory\PurchaseOrder;
use App\Models\inventory\PurchaseOrderProduct;
use App\Models\inventory\PurchaseProduct;
use App\Models\inventory\Quotation;
use App\Models\inventory\QuotationProduct;
use App\Models\inventory\Sale;
use App\Models\inventory\SaleProduct;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $suppliers = Party::where('party_type', 'Supplier')->get();
        $customers = Party::whereIn('party_type', ['Customer', 'Walkin_Customer'])->get();
        $products = Product::where('type', '!=', 'service')->get();
        $serviceProducts = Product::where('type', 'service')->get();
        $user = User::first();
        $warehouse = Warehouse::first();

        if ($suppliers->isEmpty() || $customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        $purchases = [
            ['supplier_id' => $suppliers[0]->id, 'purchase_no' => 'PUR-2024-001', 'total_amount' => 156000, 'grand_total' => 156000, 'previous_due' => 0, 'total_with_due' => 156000, 'current_balance' => 0, 'date' => now()->subDays(30)],
            ['supplier_id' => $suppliers[1]->id, 'purchase_no' => 'PUR-2024-002', 'total_amount' => 95000, 'grand_total' => 95000, 'previous_due' => 0, 'total_with_due' => 95000, 'current_balance' => 0, 'date' => now()->subDays(20)],
            ['supplier_id' => $suppliers[2]->id, 'purchase_no' => 'PUR-2024-003', 'total_amount' => 48000, 'grand_total' => 48000, 'previous_due' => 0, 'total_with_due' => 48000, 'current_balance' => 0, 'date' => now()->subDays(10)],
        ];

        foreach ($purchases as $pData) {
            $purchase = Purchase::firstOrCreate(
                ['purchase_no' => $pData['purchase_no']],
                array_merge($pData, [
                    'status' => 'Active',
                    'deleted' => 'No',
                    'created_by' => $user ? $user->id : 1,
                ])
            );

            if ($purchase->wasRecentlyCreated && $products->count() >= 2) {
                for ($i = 0; $i < 2; $i++) {
                    $qty = rand(5, 15);
                    $price = $products[$i]->purchase_price ?: rand(500, 5000);
                    PurchaseProduct::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $products[$i]->id,
                        'warehouse_id' => $warehouse ? $warehouse->id : 1,
                        'unit_id' => $products[$i]->unit_id,
                        'lot_no' => 0,
                        'unit_price' => $price,
                        'quantity' => $qty,
                        'subtotal' => $price * $qty,
                        'status' => 'Active',
                        'deleted' => 'No',
                        'created_by' => $user ? $user->id : 1,
                    ]);
                }
            }
        }

        $sales = [
            ['customer_id' => $customers[0]->id, 'sale_no' => 'SALE-2024-001', 'total_amount' => 104000, 'grand_total' => 104000, 'previous_due' => 0, 'total_with_due' => 104000, 'current_balance' => 0, 'date' => now()->subDays(25), 'sales_type' => 'walkin_sale'],
            ['customer_id' => $customers[1]->id, 'sale_no' => 'SALE-2024-002', 'total_amount' => 115000, 'grand_total' => 115000, 'previous_due' => 0, 'total_with_due' => 115000, 'current_balance' => 0, 'date' => now()->subDays(15), 'sales_type' => 'walkin_sale'],
            ['customer_id' => $customers[2]->id, 'sale_no' => 'SALE-2024-003', 'total_amount' => 28500, 'grand_total' => 28500, 'previous_due' => 0, 'total_with_due' => 28500, 'current_balance' => 0, 'date' => now()->subDays(5), 'sales_type' => 'walkin_sale'],
        ];

        foreach ($sales as $sData) {
            $sale = Sale::firstOrCreate(
                ['sale_no' => $sData['sale_no']],
                array_merge($sData, [
                    'status' => 'Active',
                    'deleted' => 'No',
                    'created_by' => $user ? $user->id : 1,
                ])
            );

            if ($sale->wasRecentlyCreated && $products->count() >= 2) {
                for ($i = 0; $i < 2; $i++) {
                    $qty = rand(1, 5);
                    $price = $products[$i]->sale_price ?: rand(1000, 10000);
                    SaleProduct::create([
                        'sale_id' => $sale->id,
                        'product_id' => $products[$i]->id,
                        'warehouse_id' => $warehouse ? $warehouse->id : 1,
                        'unit_id' => $products[$i]->unit_id,
                        'lot_no' => 0,
                        'unit_price' => $price,
                        'quantity' => $qty,
                        'subtotal' => $price * $qty,
                        'sale_price' => $price,
                        'status' => 'Active',
                        'deleted' => 'No',
                        'created_by' => $user ? $user->id : 1,
                    ]);
                }
            }
        }

        $po = PurchaseOrder::firstOrCreate(
            ['po_number' => 'PO-2024-001'],
            [
                'supplier_id' => $suppliers[0]->id,
                'date' => now()->subDays(7),
                'total_amount' => 75000,
                'status' => 'Approved',
                'created_by' => $user ? $user->id : 1,
            ]
        );

        if ($po->wasRecentlyCreated && $products->count() >= 2) {
            for ($i = 0; $i < 2; $i++) {
                $qty = rand(10, 20);
                $price = $products[$i]->purchase_price ?: rand(500, 5000);
                PurchaseOrderProduct::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $products[$i]->id,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'total_price' => $price * $qty,
                ]);
            }
        }

        $quotation = Quotation::firstOrCreate(
            ['quotation_no' => 'QTN-2024-001'],
            [
                'customer_id' => $customers[0]->id,
                'date' => now()->subDays(3),
                'total_amount' => 120000,
                'grand_total' => 120000,
                'status' => 'Pending',
                'created_by' => $user ? $user->id : 1,
                'deleted' => 'No',
            ]
        );

        if ($quotation->wasRecentlyCreated && $products->count() >= 2) {
            for ($i = 0; $i < 2; $i++) {
                $qty = rand(2, 5);
                $price = $products[$i]->sale_price ?: rand(1000, 10000);
                QuotationProduct::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $products[$i]->id,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'total_price' => $price * $qty,
                ]);
            }
        }

        $invoice = Invoice::firstOrCreate(
            ['invoice_no' => 'INV-2024-001'],
            [
                'party_id' => $customers[0]->id,
                'invoice_type' => 'Sales',
                'date' => now()->subDays(2),
                'total_amount' => 52000,
                'grand_total' => 52000,
                'paid_amount' => 52000,
                'status' => 'Paid',
                'deleted' => 'No',
                'created_by' => $user ? $user->id : 1,
            ]
        );

        if ($invoice->wasRecentlyCreated && $products->count() >= 1) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $products[0]->id,
                'quantity' => 1,
                'unit_price' => $products[0]->sale_price ?: 52000,
                'total_price' => $products[0]->sale_price ?: 52000,
            ]);
        }
    }
}
