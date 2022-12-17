<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_acc_bill_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->biginteger('tbl_acc_vendor_id')->nullable();
            $table->biginteger('tbl_acc_bill_id')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('reference')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('account_status')->nullable();
            $table->decimal('amount',10,2)->nullable();

            $table->enum('status', ['Active', 'Inactive'])->nullable();
            $table->enum('deleted',['Yes', 'No'])->nullable();
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
        Schema::dropIfExists('tbl_acc_bill_payments');
    }
}
