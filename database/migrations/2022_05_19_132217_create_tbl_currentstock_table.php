<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCurrentstockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_currentstock', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->biginteger('tbl_productsId');
            $table->biginteger('tbl_wareHouseId');
            $table->float('currentStock', 8, 2);
            $table->float('purchaseStock', 8, 2)->default(0);
            $table->float('salesStock', 8, 2)->default(0);
            $table->decimal('purchaseReturnStock', 8,2)->default(0);
            $table->decimal('salesReturnStock', 8,2)->default(0);
            $table->float('initialStock', 8, 2)->default(0);
            $table->biginteger('purchaseDelete')->default(0);
            $table->biginteger('salesDelete')->default(0);
            $table->biginteger('transferFrom')->default(0);
            $table->biginteger('transferTo')->default(0);
            $table->biginteger('transferFromDelete')->default(0);
            $table->biginteger('transferToDelete')->default(0);
            $table->datetime('lastUpdatedDate')->nullable();
            $table->biginteger('lastUpdatedBy')->nullable();
            $table->enum('deleted', ['Yes', 'No'])->default('No');
            $table->biginteger('entryBy')->nullable();
            $table->datetime('entryDate')->nullable();
            $table->decimal('purchaseReturnDelete', 8,2)->default(0);
            $table->decimal('salesReturnDelete', 8,2)->default(0);
            $table->biginteger('damageProducts')->default(0);
            $table->biginteger('damageDelete')->default(0);;
            $table->biginteger('deletedBy')->nullable();
            $table->biginteger('tbl_transfer_warehouse_id')->nullable();
            $table->biginteger('transfer_stock')->nullable();
            $table->datetime('deletedDate')->nullable();
            $table->string('dbInsertDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_currentstock');
    }
}
