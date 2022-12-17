<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblWarehouseTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_warehouse_transfer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('transferDate');
            $table->biginteger('tbl_current_warehouse_id')->nullable();
            $table->biginteger('tbl_products_id')->nullable();
            $table->biginteger('current_stock')->nullable();
            $table->biginteger('tbl_transfer_warehouse_id')->nullable();
            $table->biginteger('transfer_stock')->nullable();
            $table->biginteger('entryBy')->nullable();
            $table->datetime('entryDate')->nullable();
            $table->enum('deleted', ['Yes', 'No'])->default('No');
            $table->biginteger('deletedBy')->nullable();
            $table->datetime('deletedDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_warehouse_transfer');
    }
}
