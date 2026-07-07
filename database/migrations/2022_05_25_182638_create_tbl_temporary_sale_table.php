<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblTemporarySaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_temporary_sale', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tsNo')->nullable();
            $table->date('tSalesDate');
            $table->biginteger('tbl_customerId');
            $table->string('paymentType')->nullable();
            $table->text('inv_remarks')->nullable();
            $table->text('remarks')->nullable();
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
            $table->biginteger('tbl_userId')->nullable();
            $table->string('requisitionNo')->nullable();
            $table->biginteger('tbl_wareHouseId')->nullable();
            $table->enum('status', ['Running', 'Adjusted'])->default('Running');
            $table->string('referenceInfo')->nullable();
            $table->datetime('dbInsertDate')->nullable();
            $table->biginteger('print_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_temporary_sale');
    }
}
