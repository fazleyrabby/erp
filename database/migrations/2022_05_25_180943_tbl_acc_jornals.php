<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblAccJornals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_acc_jornals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_date')->nullable();
            $table->biginteger('transaction_id')->nullable();
            $table->biginteger('tbl_acc_voucher_entry_id')->nullable();
            $table->string('reference')->nullable();
            $table->string('attachment')->nullable();
            $table->string('internal_information')->nullable();
            $table->biginteger('account_id')->nullable();
            $table->string('particulars')->nullable();
            $table->decimal('voucher_amount')->nullable();

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
        Schema::dropIfExists('tbl_acc_jornals');
    }
}
