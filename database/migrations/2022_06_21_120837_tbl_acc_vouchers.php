<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblAccVouchers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_acc_vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->biginteger('vendor_id')->nullable();
            $table->biginteger('voucher_no')->nullable();
            $table->biginteger('type_no')->nullable();
            $table->biginteger('purchase_id')->nullable();
            $table->biginteger('sales_id')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('type')->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('voucher_title')->nullable();
            $table->longtext('particulars')->nullable();
            $table->string('payment_method')->nullable();

            $table->enum('status', ['Active', 'Inactive'])->nullable();
            $table->enum('deleted', ['Yes', 'No'])->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->dateTime('created_date')->nullable();
            $table->bigInteger('last_updated_by')->nullable();
            $table->dateTime('updated_date')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->dateTime('deleted_date')->nullable();

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
        Schema::dropIfExists('tbl_acc_vouchers');
    }
}
