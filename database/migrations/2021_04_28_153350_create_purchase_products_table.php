<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->biginteger('purchase_id');
            $table->biginteger('product_id');
            $table->biginteger('warehouse_id');
            $table->biginteger('unit_id');
            $table->decimal('unit_price', 12, 2);
            $table->integer('quantity');
            $table->biginteger('lot_no');
            $table->decimal('subtotal', 12, 2);
            $table->date('expired_date')->nullable();
            $table->biginteger('sell_quantity')->default(0);

            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->enum('sell_status', ['On', 'Off'])->default('On');
            $table->enum('deleted', ['Yes', 'No'])->default('No');
            $table->biginteger('created_by')->nullable();
            $table->datetime('created_date')->nullable();
            $table->biginteger('updated_by')->nullable();
            $table->datetime('updated_date')->nullable();
            $table->biginteger('deleted_by')->nullable();
            $table->date('deleted_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_products');
    }
}
