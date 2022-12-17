<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblSerializeProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_serialize_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->biginteger('tbl_productsId');
            $table->biginteger('warehouse_id');
            $table->biginteger('purchase_id')->nullable();
            $table->string('serial_no',20);
            $table->biginteger('quantity')->nullable();
            $table->biginteger('used_quantity')->default(0);
            $table->enum('is_sold',['ON','OFF'])->default('ON');
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
        Schema::dropIfExists('tbl_serialize_products');
    }
}
