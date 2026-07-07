<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code');
            $table->string('image')->nullable();
            $table->string('barcode_no')->nullable();
            $table->string('barcode')->nullable();
            $table->biginteger('category_id');
            $table->biginteger('brand_id');
            $table->biginteger('unit_id');
            $table->integer('opening_stock');
            $table->integer('remainder_quantity')->default(0);
            $table->decimal('purchase_price', 12, 2);
            $table->decimal('sale_price', 12, 2);
            $table->decimal('discount', 10, 2)->nullable();
            $table->string('notes')->nullable();
            $table->string('model_no')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->enum('deleted', ['Yes', 'No'])->default('No');
            $table->biginteger('created_by')->nullable();
            $table->datetime('created_date')->nullable();
            $table->biginteger('updated_by')->nullable();
            $table->datetime('updated_date')->nullable();
            $table->integer('purchase_quantity')->nullable();
            $table->biginteger('deleted_by')->nullable();
            $table->datetime('deleted_date')->nullable();
            $table->integer('current_stock')->default(0);
            $table->integer('sale_quantity')->default(0);
            $table->decimal('total_purchase_price', 12, 2)->default(0);
            $table->decimal('total_sale_price', 12, 2)->default(0);
            $table->decimal('remaining_price', 12, 2)->default(0);
            $table->timestamps();
            $table->enum('type', ['regular', 'serialize', 'service'])->default('regular');
            $table->enum('stock_check', ['Yes', 'No'])->nullable();
            $table->integer('items_in_box')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
