<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->biginteger('customer_id');
            $table->string('sale_no');
            $table->string('date', 20)->nullable();
            $table->string('description')->nullable(); // defectReported
            $table->decimal('total_amount', 12, 2);
            $table->decimal('discount', 12, 2)->nullable();
            $table->biginteger('carrying_cost')->nullable();
            $table->decimal('vat', 10, 2)->nullable();
            $table->decimal('ait', 10, 2)->nullable();
            $table->decimal('grand_total', 12, 2)->nullable();
            $table->decimal('advance_payment', 12, 2)->default(0.00);
            $table->decimal('previous_due', 12, 2);
            $table->decimal('total_with_due', 12, 2);
            $table->decimal('current_payment', 12, 2)->default(0.00);
            $table->decimal('current_balance', 12, 2);
            $table->enum('sales_type', ['walkin_sale', 'FS', 'party_sale']);
            // New Fields
            $table->string('work_approval_date', 20)->nullable();
            $table->string('expected_delivery_date', 20)->nullable();
            $table->string('manufacturing_si_no')->nullable();
            $table->string('accessories_recieved')->nullable();
            $table->string('other_accessories')->nullable();
            $table->biginteger('quantity')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('item')->nullable();
            // Common Fields
            $table->enum('order_status', ['Pending', 'Servicing', 'Cancelled', 'Delivered', 'ReadyToDeliverd', 'Completed'])->default('Pending');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->enum('sale_status', ['Incomplete', 'Completed'])->default('Incomplete');
            $table->enum('deleted', ['Yes', 'No'])->default('No');
            $table->biginteger('created_by')->nullable();
            $table->datetime('created_date')->nullable();
            $table->biginteger('updated_by')->nullable();
            $table->datetime('updated_date')->nullable();
            $table->biginteger('deleted_by')->nullable();
            $table->datetime('deleted_date')->nullable();
            $table->timestamps();
            // End Common Fields
            $table->decimal('total_price', 10, 2)->nullable();
            $table->decimal('dues_amount', 10, 2)->nullable();
            $table->decimal('current_dues', 10, 2)->nullable();
            $table->date('start_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_orders');
    }
}
