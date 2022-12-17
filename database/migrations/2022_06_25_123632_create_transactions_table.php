<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_acc_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->biginteger('tbl_coa_from_id')->nullable();
            $table->biginteger('tbl_coa_to_id')->nullable();
            $table->decimal('from_amount_bofore_transaction',10,2)->nullable();
            $table->decimal('to_amount_bofore_transaction',10,2)->nullable();
            $table->biginteger('transaction_id')->nullable();
            $table->decimal('amount',10,2)->nullable();
            $table->date('transaction_date')->nullable();
            $table->longtext('remarks')->nullable();

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
        Schema::dropIfExists('tbl_acc_transactions');
    }
}
