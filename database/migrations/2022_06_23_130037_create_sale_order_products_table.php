<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_order_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->biginteger('tbl_sale_orders_id');
            $table->biginteger('product_id');
            $table->biginteger('warehouse_id');
            $table->biginteger('unit_id');
            $table->biginteger('unit_price');
            $table->integer('quantity');
            $table->biginteger('lot_no');
            $table->decimal('subtotal', 12, 2);
            // Common Fields
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->enum('deleted', ['Yes', 'No'])->default('No');
            $table->biginteger('created_by')->nullable();
            $table->datetime('created_date')->nullable();
            $table->biginteger('updated_by')->nullable();
            $table->datetime('updated_date')->nullable();
            $table->biginteger('deleted_by')->nullable();
            $table->date('deleted_date')->nullable();
            $table->timestamps();
            // Not Common Field
            $table->enum('sell_status', ['On', 'Off'])->default('On');
            $table->decimal('unit_discount', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_order_products');
    }
}
