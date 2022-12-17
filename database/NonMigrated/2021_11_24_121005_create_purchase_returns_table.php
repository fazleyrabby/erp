<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('purchase_return_no')->nullable();
            //$table->string('sale_no')->nullable();
            $table->datetime('purchase_return_date')->nullable();
            $table->datetime('purchase_date')->nullable();
            $table->string('memo_no')->nullable();
            $table->biginteger('purchase_id')->nullable();
            $table->string('supplier_id')->nullable();
            $table->biginteger('discount')->nullable();
            $table->biginteger('grand_total')->nullable();
            $table->enum('status',['Active','Inactive'])->default('Active');
            $table->enum('deleted',['Yes','No'])->default('No');
            //$table->enum('deleted',['Yes','No'])->nullable();
            $table->biginteger('created_by')->nullable();
            $table->biginteger('updated_by')->nullable();
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
        Schema::dropIfExists('purchase_returns');
    }
}
