<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleProductReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_product_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->biginteger('sale_product_id')->nullable();
            $table->biginteger('product_id')->nullable();
            $table->biginteger('sale_return_id')->nullable();
            $table->biginteger('warehouse_id');
            $table->biginteger('return_qty')->nullable();
            $table->biginteger('remaining_qty')->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
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
            $table->enum('sales_type', ['walkin_sale', 'TS', 'FS', 'party_sale']); // Not Common Field
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_product_returns');
    }
}
