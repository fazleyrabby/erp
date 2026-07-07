<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->biginteger('party_id')->nullable();
            $table->biginteger('purchase_id')->nullable();
            $table->biginteger('order_sale_id')->nullable();
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->biginteger('entryBy');
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->string('payment_method');
            $table->string('chequeNo')->nullable();
            $table->date('paymentDate');
            $table->date('chequeIssueDate')->nullable();
            $table->string('accountNo')->nullable();
            $table->enum('type', ['Payment Received', 'Payment', 'Payable', 'Party Payable', 'Payment Adjustment', 'Adjustment', 'Discount']);
            $table->text('remarks')->nullable();
            $table->biginteger('tbl_bankInfoId')->nullable();
            $table->biginteger('lastUpdatedBy')->nullable();
            $table->enum('voucherType', ['Local Purchase', 'Foreign Purchase', 'WalkinSale', 'PartySale', 'FS', 'TS', 'PurchaseReturn', 'SalesReturn']);
            $table->biginteger('sales_id')->nullable();
            $table->biginteger('purchase_return_id')->nullable();
            $table->biginteger('sales_return_id')->nullable();
            $table->biginteger('expense_id')->nullable();
            $table->biginteger('tbl_repairing_center_id')->nullable();
            $table->enum('customerType', ['WalkingCustomer', 'Party'])->default('Party');
            $table->string('voucherNo');
            $table->string('chequeBank')->nullable();
            $table->datetime('dbInsertDate')->nullable();
            // Common Fields
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->enum('deleted', ['Yes', 'No'])->default('No');
            $table->biginteger('created_by')->nullable();
            $table->datetime('created_date')->nullable();
            $table->biginteger('updated_by')->nullable();
            $table->datetime('updated_date')->nullable();
            $table->biginteger('deleted_by')->nullable();
            $table->datetime('deleted_date')->nullable();
            $table->timestamps();
            // End Common Fields
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_vouchers');
    }
}
