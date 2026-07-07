<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblTsalesproductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_tsalesproducts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->biginteger('tbl_tSalesId')->nullable();
            $table->biginteger('tbl_productsId')->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->string('units')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            // Common Fields
            $table->enum('deleted', ['Yes', 'No'])->default('No');
            $table->biginteger('createdBy')->nullable();
            $table->datetime('createdDate')->nullable();
            $table->biginteger('lastUpdatedBy')->nullable();
            $table->datetime('lastUpdatedDate')->nullable();
            $table->biginteger('deletedBy')->nullable();
            $table->datetime('deletedDate')->nullable();
            $table->timestamps();
            // Not Common Field
            $table->decimal('totalAmount', 10, 2)->nullable();
            $table->string('discount')->nullable();
            $table->decimal('grandTotal', 10, 2)->nullable();
            $table->biginteger('tbl_wareHouseId')->nullable();
            $table->enum('status', ['Running', 'Adjusted'])->default('Running');
            $table->decimal('soldQuantity', 10, 2)->nullable();
            $table->decimal('returnedQuantity', 10, 2)->nullable();
            $table->datetime('dbInsertDate')->nullable();
            $table->decimal('saleAmount', 10, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->biginteger('tbl_discount_offer_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_tsalesproducts');
    }
}
