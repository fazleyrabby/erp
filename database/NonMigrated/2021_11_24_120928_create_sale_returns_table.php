<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('sale_return_no')->nullable();
            $table->string('sale_no')->nullable();
            $table->datetime('sale_return_date')->nullable();
            $table->datetime('sale_date')->nullable();
            $table->biginteger('sale_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->biginteger('discount')->nullable();
            $table->biginteger('grand_total')->nullable();
            $table->enum('status',['Active','Inactive'])->default('Active');
            $table->enum('deleted',['Yes','No'])->nullable();
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
        Schema::dropIfExists('sale_returns');
    }
}
