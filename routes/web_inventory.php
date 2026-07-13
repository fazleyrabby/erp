<?php

use App\Http\Controllers\Admin\Inventory\InventoryReportController;
use App\Http\Controllers\Admin\Inventory\InvoiceController;
use App\Http\Controllers\Admin\Inventory\ProductController;
use App\Http\Controllers\Admin\Inventory\PurchaseController;
use App\Http\Controllers\Admin\Inventory\PurchaseReturnController;
use App\Http\Controllers\Admin\Inventory\SaleController;
use App\Http\Controllers\Admin\Inventory\SaleReturnController;
use App\Http\Controllers\Admin\Inventory\SaleServiceController;
use App\Http\Controllers\Admin\Inventory\TransportController;
use App\Http\Controllers\Admin\Inventory\VoucherController;
use App\Http\Controllers\Admin\Setup\BrandController;
use App\Http\Controllers\Admin\Setup\CategoryController;
use App\Http\Controllers\Admin\Setup\UnitController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {

    // Category Routes
    Route::name('categories.')->prefix('categories')->group(function () {
        Route::get('/view', [CategoryController::class, 'index'])->name('view');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::post('/update', [CategoryController::class, 'update'])->name('update');
        Route::post('/delete', [CategoryController::class, 'delete'])->name('delete');
    });

    // Brand Routes
    Route::name('brands.')->prefix('brands')->group(function () {
        Route::get('/view', [BrandController::class, 'index'])->name('view');
        Route::post('/store', [BrandController::class, 'store'])->name('store');
        Route::get('/edit', [BrandController::class, 'edit'])->name('edit');
        Route::post('/update', [BrandController::class, 'update'])->name('update');
        Route::post('/delete', [BrandController::class, 'delete'])->name('delete');
    });

    Route::post('brands/categoryWiseView', [BrandController::class, 'categoryWiseBrands'])->name('categoryWiseBrands');
    // Unit Routes

    Route::name('units.')->prefix('units')->group(function () {
        Route::get('/view', [UnitController::class, 'index'])->name('view');
        Route::post('/store', [UnitController::class, 'store'])->name('store');
        Route::get('/edit', [UnitController::class, 'edit'])->name('edit');
        Route::post('/update', [UnitController::class, 'update'])->name('update');
        Route::post('/delete', [UnitController::class, 'delete'])->name('delete');
    });

    // WareHouse Routes
    Route::name('warehouse.')->prefix('warehouse')->group(function () {
        Route::get('/view', [CategoryController::class, 'warehouse'])->name('view');
        Route::post('/store', [CategoryController::class, 'storeWarehouse'])->name('store');
        Route::get('/edit', [CategoryController::class, 'editWarehouse'])->name('edit');
        Route::post('/update', [CategoryController::class, 'updateWarehouse'])->name('update');
        Route::get('/delete', [CategoryController::class, 'deleteWarehouse'])->name('delete');
        Route::get('/transfer', [CategoryController::class, 'warehouseTransferView'])->name('transfer.view');
        Route::post('/stock', [CategoryController::class, 'warehousewiseProductStock'])->name('stock');
        Route::post('/listfromProduct', [CategoryController::class, 'productwiseWarehouse'])->name('list.product');
    });

    Route::prefix('voucher')->group(function () {

        Route::get('/{type}', [VoucherController::class, 'index']);
        Route::get('/add', [VoucherController::class, 'add'])->name('voucher.add');
        Route::post('/store', [VoucherController::class, 'store']);
        Route::post('/delete', [VoucherController::class, 'voucherDelete'])->name('voucher.delete');
        Route::get('/view/load/workOrder', [VoucherController::class, 'loadWorkOrder'])->name('loadWorkOrder');
        Route::get('/view/load/party', [VoucherController::class, 'loadParties'])->name('loadParties');
        Route::get('/view/load/Due', [VoucherController::class, 'loadPartyDue'])->name('loadPartyDue');
        Route::get('/invoice/{id}', [VoucherController::class, 'createPDF']);
        Route::get('supplier/get/project', [VoucherController::class, 'getSupplierDue'])->name('getSupplierDue');
        // EMI payment Voucher
        Route::get('payment/emi', [VoucherController::class, 'addEmiVoucher']);
        Route::get('payment/paidEmi', [VoucherController::class, 'emiPaymentView']);
        Route::get('payment/addEmiVoucher', [VoucherController::class, 'addEmiVoucher']);
        Route::get('/sale/getEmiInvoice', [VoucherController::class, 'getEmiInvoice']);
        Route::get('/sale/fetchEMI', [VoucherController::class, 'fetchEMI']);
        Route::post('/emiPaymentStore', [VoucherController::class, 'emiPaymentStore']);
        Route::post('/sale/payEmiStore', [VoucherController::class, 'payEmiStore']);
        Route::get('work/order/get/project', [WorkOrderController::class, 'getProjects'])->name('getProjects');
        Route::get('work/order/get/edit/project', [WorkOrderController::class, 'getEditProjects'])->name('getEditProjects');

    });

    // warehouseTransfer Routes
    Route::prefix('warehouseTransfer')->group(function () {
        Route::post('/Store', [CategoryController::class, 'warehouseTransferStore'])->name('warehouseTransfer.store');
        Route::post('/delete', [CategoryController::class, 'deleteWarehouseTransfer'])->name('warehouseTransfer.delete');
    });

    // Transport Routes
    Route::name('transport.')->prefix('transport')->group(function () {
        Route::get('/', [TransportController::class, 'index'])->name('view');

        Route::post('/store', [TransportController::class, 'store'])->name('store');
        Route::get('/edit', [TransportController::class, 'edit'])->name('edit');
        Route::post('/update', [TransportController::class, 'udpate'])->name('update');
        Route::get('/delete', [TransportController::class, 'delete'])->name('delete');
    });

    // /-------- Start Inventory Module Web page links ---------------///
    // Products Routes
    Route::prefix('products')->group(function () {
        Route::name('products.')->group(function () {
            Route::get('/view', [ProductController::class, 'index'])->name('view');
            Route::post('/store', [ProductController::class, 'store'])->name('store');
            Route::post('/service/store', [ProductController::class, 'servicestore'])->name('servicestore');
            Route::get('/edit', [ProductController::class, 'edit'])->name('edit');
            Route::post('/update', [ProductController::class, 'update'])->name('update');
            Route::post('/delete', [ProductController::class, 'delete'])->name('delete');
            Route::get('/getSerialNums', [ProductController::class, 'getSerialNums'])->name('getSerialNums');
        });
        Route::post('/brandAndCategoryWise', [ProductController::class, 'brandAndCategoryWise'])->name('brandAndCategoryWise');
        Route::post('/findCurrentStock', [ProductController::class, 'findCurrentStock'])->name('findCurrentStock');
        Route::get('/viewAdvanceSearchProducts/{page}', [ProductController::class, 'getAdvanceSearchProducts'])->name('viewAdvanceSearchProducts');
        Route::get('/warehouseWiseStock', [ProductController::class, 'warehouseWiseStock'])->name('warehouseWiseStock');
        Route::get('/editOpenStock', [ProductController::class, 'editOpenStock'])->name('editOpenStock');
        Route::post('/updateProductOpenStock', [ProductController::class, 'updateProductOpenStock'])->name('updateProductOpenStock');
        Route::post('/deleteSpec', [ProductController::class, 'deleteSpec'])->name('deleteSpec');
    });

    // Purchase Routes
    Route::prefix('purchase_orders')->name('purchase_orders.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\Inventory\PurchaseOrderController::class, 'index'])->name('index');
        Route::get('/add', [\App\Http\Controllers\Admin\Inventory\PurchaseOrderController::class, 'add'])->name('add');
        Route::post('/store', [\App\Http\Controllers\Admin\Inventory\PurchaseOrderController::class, 'store'])->name('store');
        Route::get('/approve/{id}', [\App\Http\Controllers\Admin\Inventory\PurchaseOrderController::class, 'approve'])->name('approve');
        Route::get('/view/{id}', [\App\Http\Controllers\Admin\Inventory\PurchaseOrderController::class, 'view'])->name('view');
        Route::get('/convert/{id}', [\App\Http\Controllers\Admin\Inventory\PurchaseOrderController::class, 'convertToPurchase'])->name('convert');
    });

    Route::prefix('purchase')->group(function () {
        Route::name('purchase.')->group(function () {
            Route::get('/', [PurchaseController::class, 'index'])->name('index');
            Route::post('/supplierDue', [PurchaseController::class, 'supplierDue'])->name('supplierDue');
            Route::get('/add', [PurchaseController::class, 'add'])->name('add');
            Route::post('/addProduct', [PurchaseController::class, 'addToCart'])->name('addToCart');
            Route::get('/fetchCart', [PurchaseController::class, 'fetchCart'])->name('fetchCart');
            Route::post('/clearCart', [PurchaseController::class, 'clearCart'])->name('clearCart');
            Route::post('/removeProduct', [PurchaseController::class, 'removeProduct'])->name('removeProduct');
            Route::post('/updateCart', [PurchaseController::class, 'updateCart'])->name('updateCart');
            Route::post('/checkOutCart', [PurchaseController::class, 'checkOutCart'])->name('checkOutCart');
            Route::post('/delete', [PurchaseController::class, 'delete'])->name('delete');
            Route::get('/invoice/{id}', [PurchaseController::class, 'createPDF'])->name('invoice');
            // Serilaize Product
            Route::post('/showSerializTable', [PurchaseController::class, 'showSerializTable'])->name('showSerializTable');
        });
        // purchase return//
        Route::get('/purchase-return/{id}', [PurchaseReturnController::class, 'purchaseReturn']);
        Route::get('purchase-return-list', [PurchaseReturnController::class, 'purchaseReturnList'])->name('purchase.return.list');
        Route::get('/purchase-return/{id}', [PurchaseReturnController::class, 'purchaseReturn']);
        Route::post('/savePurchaseReturn', [PurchaseReturnController::class, 'storePurchaseReturn'])->name('purchase.savePurchaseReturn');
        Route::get('/return/invoice/{id}', [PurchaseReturnController::class, 'createPDF']);
        Route::post('/deletePurchaseReturn', [PurchaseReturnController::class, 'deletePurchaseReturn'])->name('deletePurchaseReturn');
    });

    // Sale Routes
    Route::name('sale.')->prefix('sale')->group(function () {
        Route::get('/viewSales/{type}', [SaleController::class, 'viewSales'])->name('sales');
        Route::get('/add/{type}', [SaleController::class, 'add'])->name('add');
        Route::get('/view/{type}', [SaleController::class, 'getSale'])->name('viewSale');
        Route::get('/emi', [SaleController::class, 'EMI'])->name('emi');
        Route::post('/supplierDue', [SaleController::class, 'supplierDue'])->name('supplierDue');
        Route::post('/addProduct', [SaleController::class, 'addToCart'])->name('addToCart');
        Route::get('/fetchCart', [SaleController::class, 'fetchCart'])->name('fetchCart');
        Route::post('/clearCart', [SaleController::class, 'clearCart'])->name('clearCart');
        Route::post('/removeProduct', [SaleController::class, 'removeProduct'])->name('removeProduct');
        Route::post('/updateCart', [SaleController::class, 'updateCart'])->name('updateCart');
        Route::post('/checkOutCart', [SaleController::class, 'checkOutCart'])->name('checkOutCart');
        Route::post('/delete', [SaleController::class, 'delete'])->name('delete');
        // Sale return//
        Route::get('/sale-return/{id}', [SaleReturnController::class, 'saleReturn'])->name('sale-return');
        Route::get('/sale-returnList/{type}', [SaleReturnController::class, 'saleReturnList'])->name('return.list');
        Route::get('/saleReturnView/{type}', [SaleReturnController::class, 'saleReturnView'])->name('saleReturnView');
        Route::post('/saveSaleReturn', [SaleReturnController::class, 'saveSaleReturn'])->name('saveSaleReturn');
        Route::post('/deleteSaleReturn', [SaleReturnController::class, 'deleteSaleReturn'])->name('deleteSaleReturn');

        Route::get('/invoice/{id}', [SaleController::class, 'createPDF'])->name('invoice');
        Route::get('/tsInvoice/{id}', [SaleController::class, 'tsCreatePDF'])->name('tsInvoice');
        Route::get('return/invoice/{id}', [SaleReturnController::class, 'createPDF'])->name('return.invoice');
        // Temporary Sale Adjustment
        Route::get('/temporarySaleAdjustment', [SaleReturnController::class, 'temporarySaleAdjustment'])->name('temporarySaleAdjustment');
        Route::get('/getTemporarySale', [SaleReturnController::class, 'getTemporarySale'])->name('getTemporarySale');
        Route::post('/saveTSAdjustment', [SaleReturnController::class, 'saveTSAdjustment'])->name('saveTSAdjustment');
        // Serilaize Product
        Route::post('/showSerializTable', [SaleController::class, 'showSerializTable'])->name('showSerializTable');
    });

    // Service Sale Centre Product Routes
    Route::name('sale.service.')->prefix('sale')->group(function () {
        Route::get('service/view', [SaleServiceController::class, 'viewSaleOrders'])->name('SaleOrders');
        Route::get('service/change/customer', [SaleServiceController::class, 'changeCustomer'])->name('changeCustomer');

        Route::get('service/add', [SaleServiceController::class, 'add'])->name('add');
        Route::post('service/addProduct', [SaleServiceController::class, 'addToCart'])->name('addToCart');
        Route::get('service/fetchCart', [SaleServiceController::class, 'fetchCart'])->name('fetchCart');
        Route::post('service/removeProduct', [SaleServiceController::class, 'removeProduct'])->name('removeProduct');
        Route::post('service/updateCart', [SaleServiceController::class, 'updateCart'])->name('updateCart');
        Route::post('service/clearCart', [SaleServiceController::class, 'clearCart'])->name('clearCart');
        Route::post('service/checkOutCart', [SaleServiceController::class, 'checkOutCart'])->name('checkOutCart');
        Route::get('service/check/minimum/Price', [SaleServiceController::class, 'checkMinimumPrice'])->name('checkMinimumPrice');
        // Edit SaleOrders (Service Centre)
        Route::get('service/edit/{id}', [SaleServiceController::class, 'editSaleOrder'])->name('edit.editSaleOrder');
        Route::post('service/edit/addProduct', [SaleServiceController::class, 'addToOrderEditCart'])->name('edit.addToCart');
        Route::get('service/fetchOrderEditCart', [SaleServiceController::class, 'fetchOrderEditCart'])->name('edit.fetchCart');
        Route::post('service/removeOrderEditProduct', [SaleServiceController::class, 'removeOrderEditProduct'])->name('edit.removeProduct');
        Route::post('service/updateOrderEditCart', [SaleServiceController::class, 'updateOrderEditCart'])->name('edit.updateCart');
        Route::post('service/clearOrderEditCart', [SaleServiceController::class, 'clearOrderEditCart'])->name('edit.clearCart');
        Route::post('service/updatOrderSale', [SaleServiceController::class, 'updatOrderSale'])->name('edit.updatOrderSale');
        // Order Feedbacks
        Route::get('service/getOrderFeedbacks', [SaleServiceController::class, 'getOrderFeedbacks'])->name('getOrderFeedbacks');
        Route::post('service/addOrderFeedback', [SaleServiceController::class, 'addOrderFeedback'])->name('addOrderFeedback');
        Route::get('service/removeOrderFeedback', [SaleServiceController::class, 'removeOrderFeedback'])->name('removeOrderFeedback');
        // End Edit SaleOrders (Service Centre)
        Route::post('service/statusComplete', [SaleServiceController::class, 'statusComplete'])->name('statusComplete');
        Route::get('service/createOrderToSale/{id}', [SaleServiceController::class, 'createOrderToWalkinSale'])->name('createOrderToWalkinSale');
        Route::post('service/completeOrderCheckOutCart', [SaleServiceController::class, 'completeOrderCheckOutCart'])->name('completeOrderCheckOutCart');
        Route::get('service/orderInvoice/{id}', [SaleServiceController::class, 'orderInvoice'])->name('orderInvoice');
        Route::get('service/completeInvoice/{id}', [SaleServiceController::class, 'completeInvoice'])->name('completeInvoice');
        Route::get('service/sale/invoice/{id}', [SaleServiceController::class, 'createPDF'])->name('serviceSaleInvoice');
    });

    // Quotation Routes
    Route::name('quotations.')->prefix('quotations')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\Inventory\QuotationController::class, 'index'])->name('index');
        Route::get('/add', [\App\Http\Controllers\Admin\Inventory\QuotationController::class, 'add'])->name('add');
        Route::post('/store', [\App\Http\Controllers\Admin\Inventory\QuotationController::class, 'store'])->name('store');
        Route::get('/convert/{id}', [\App\Http\Controllers\Admin\Inventory\QuotationController::class, 'convertToSaleOrder'])->name('convert');
        Route::get('/pdf/{id}', [\App\Http\Controllers\Admin\Inventory\QuotationController::class, 'createPDF'])->name('pdf');
    });

    // Invoice Routes
    Route::name('invoices.')->prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/add', [InvoiceController::class, 'add'])->name('add');
        Route::post('/store', [InvoiceController::class, 'store'])->name('store');
        Route::get('/show/{id}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [InvoiceController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [InvoiceController::class, 'update'])->name('update');
        Route::post('/delete', [InvoiceController::class, 'delete'])->name('delete');
        Route::post('/status/{id}', [InvoiceController::class, 'updateStatus'])->name('status');
        Route::post('/payment/{id}', [InvoiceController::class, 'addPayment'])->name('payment');
        Route::get('/pdf/{id}', [InvoiceController::class, 'createPDF'])->name('pdf');
        Route::get('/convert-from-sale/{id}', [InvoiceController::class, 'convertFromSale'])->name('convert-from-sale');
        Route::get('/convert-from-purchase/{id}', [InvoiceController::class, 'convertFromPurchase'])->name('convert-from-purchase');
    });

    // Damage Routes
    Route::prefix('damage')->group(function () {
        Route::name('damage.')->group(function () {
            Route::get('/', [ProductController::class, 'damageIndex'])->name('view');
            Route::post('/store', [ProductController::class, 'damageStore'])->name('store');
            Route::post('/delete', [ProductController::class, 'damageDelete'])->name('delete');
        });
        Route::post('/getWarehouseByProductID', [ProductController::class, 'getWarehouseByProductID'])->name('getWarehouseByProductID');
        Route::post('/getStockByProductWarehouse', [ProductController::class, 'getStockByProductWarehouse'])->name('getStockByProductWarehouse');
        Route::get('/invoice/{id}', [ProductController::class, 'createPDF']);
    });

    // /-------- End Inventory Module Web page links ---------------///

    Route::prefix('report')->group(function () {
        Route::get('/product-ledger', [InventoryReportController::class,  'productReport']);

        Route::get('/warehouseWiseStock/{ids}', [InventoryReportController::class, 'warehouseWiseStock']);

        Route::get('/product-report/{id}/{from}/{to}', [InventoryReportController::class, 'generateProductReport']);
    });

});
