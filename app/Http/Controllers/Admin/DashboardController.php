<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Crm\Party;
use App\Models\inventory\DamageProduct;
use App\Models\inventory\Product;
use App\Models\inventory\SaleOrder;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('Employee')) {
            return view('admin.includes.employee_dashboard');
        }


        $supplier = Party::where('deleted', '=', 'No')->where('status', '=', 'Active')->where('party_type', '=', 'Supplier')->count();
        $customer = Party::where('deleted', '=', 'No')->where('status', '=', 'Active')->where('party_type', '=', 'Customer')->count();
        $walkin = Party::where('deleted', '=', 'No')->where('status', '=', 'Active')->where('party_type', '=', 'Walkin_Customer')->count();

        $product = Product::where('deleted', '=', 'No')->where('status', '=', 'Active')->where('type', '!=', 'service')->count();
        $service = Product::where('deleted', '=', 'No')->where('status', '=', 'Active')->where('type', '=', 'service')->count();
        $damages = DamageProduct::where('deleted', '=', 'No')->where('status', '=', 'Active')->count();

        $pending = SaleOrder::where('deleted', '=', 'No')->where('status', '=', 'Active')->where('order_status', '=', 'Pending')->count();
        $servicing = SaleOrder::where('deleted', '=', 'No')->where('status', '=', 'Active')->where('order_status', '=', 'Servicing')->count();
        $cancelled = SaleOrder::where('deleted', '=', 'No')->where('status', '=', 'Active')->where('order_status', '=', 'Cancelled')->count();
        $delivered = SaleOrder::where('deleted', '=', 'No')->where('status', '=', 'Active')->where('order_status', '=', 'Delivered')->count();
        $ready = SaleOrder::where('deleted', '=', 'No')->where('status', '=', 'Active')->where('order_status', '=', 'ReadyToDeliverd')->count();
        $completed = SaleOrder::where('deleted', '=', 'No')->where('status', '=', 'Active')->where('order_status', '=', 'Completed')->count();

        return view('admin.includes.dashboard', [
            'supplier' => $supplier,
            'customer' => $customer,
            'walkin' => $walkin,
            'product' => $product,
            'service' => $service,
            'damages' => $damages,
            'pending' => $pending, 'servicing' => $servicing, 'cancelled' => $cancelled,
            'delivered' => $delivered, 'ready' => $ready, 'completed' => $completed,
        ]);

        // 29-11-2022
        /*
        $damages = DamageProduct::where('deleted', '=', 'No')->where('status', '=', 'Active')->count();
        $partyCounts = DB::table('parties')
            ->where('deleted', '=', 'No')
            ->where('status', '=', 'Active')
            ->select(
                DB::raw('sum(party_type = "Supplier") as supplier_count'),
                DB::raw('sum(party_type = "Customer") as customer_count'),
                DB::raw('sum(party_type = "Walkin_Customer") as walkin_customer_count')
            )
            ->first();

        $productCounts = DB::table('products')
            ->where('deleted', '=', 'No')
            ->where('status', '=', 'Active')
            ->select(
                DB::raw('sum(type != "service") as product_count'),
                DB::raw('sum(type = "service") as service_count')
            )
            ->first();

        $saleOrderCounts = DB::table('sale_orders')
            ->where('deleted', '=', 'No')
            ->where('status', '=', 'Active')
            ->select(
                DB::raw('sum(order_status = "Pending") as pending_count'),
                DB::raw('sum(order_status = "Servicing") as servicing_count'),
                DB::raw('sum(order_status = "Cancelled") as cancelled_count'),
                DB::raw('sum(order_status = "Delivered") as delivered_count'),
                DB::raw('sum(order_status = "ReadyToDeliverd") as ready_to_deliverd_count'),
                DB::raw('sum(order_status = "Completed") as completed_count')
            )
            ->first();

        //dd(($saleOrderCounts));

        return view('admin.includes.dashboard', [
            'partyCounts' => $partyCounts,
            'productCounts' => $productCounts,
            'damages' => $damages,
            'saleOrderCounts' => $saleOrderCounts
        ]);*/
        // End 29-11-2022
    }
}
