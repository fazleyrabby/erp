<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblAccExpenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_acc_expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->biginteger('tbl_acc_voucher_id')->nullable();
            $table->biginteger('tbl_crm_vendor_id')->nullable();
            $table->string('vendor_name')->nullable();
            $table->longtext('address')->nullable();
            $table->decimal('amount',10,2)->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('transaction_no')->nullable();
            $table->decimal('transaction_charge',10,2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('reference')->nullable();
            $table->string('particulars')->nullable();
            $table->string('attachments')->nullable();
            $table->biginteger('check_no')->nullable();
            $table->biginteger('trnsaction_by')->nullable();

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
        Schema::dropIfExists('tbl_acc_expenses');
    }
}
