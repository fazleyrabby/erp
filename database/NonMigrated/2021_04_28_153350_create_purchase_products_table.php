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
            $table->biginteger('purchase_code');
            $table->biginteger('product_id');
            $table->biginteger('unit_id');
            $table->biginteger('unit_price');
            $table->integer('quantity');
            $table->biginteger('lot_no');
            $table->biginteger('subtotal');
            $table->date('expired_date')->nullable();
            $table->biginteger('sell_quantity');
            $table->enum('status',['Active','Inactive'])->default('Active');
            $table->enum('sell_status',['On','Off'])->default('Active')->nullable();
            $table->enum('deleted',['Yes','No'])->nullable();
            $table->datetime('deleted_date')->nullable();
            $table->biginteger('created_by')->nullable();
            $table->datetime('created_date')->nullable();
            $table->biginteger('updated_by')->nullable();
            $table->datetime('updated_date')->nullable();
            $table->biginteger('deleted_by')->nullable();
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
