<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseProductReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_product_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->biginteger('purchase_product_id')->nullable();
            $table->biginteger('purchase_id')->nullable();
            $table->biginteger('purchase_return_id')->nullable();
            $table->biginteger('product_id');
            $table->biginteger('warehouse_id');
            $table->biginteger('return_qty')->nullable();
            $table->biginteger('remaining_qty')->nullable();
            $table->biginteger('unit_price')->nullable();
            $table->biginteger('total_price')->nullable();
            // Common Fields
            $table->enum('status',['Active','Inactive'])->default('Active');
            $table->enum('deleted',['Yes','No'])->default('No');
            $table->biginteger('created_by')->nullable();
            $table->datetime('created_date')->nullable();
            $table->biginteger('updated_by')->nullable();
            $table->datetime('updated_date')->nullable();
            $table->biginteger('deleted_by')->nullable();
            $table->datetime('deleted_date')->nullable();
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
        Schema::dropIfExists('purchase_product_returns');
    }
}
