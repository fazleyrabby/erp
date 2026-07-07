<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblProductspecificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_productspecification', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->biginteger('tbl_productsId');
            $table->string('specificationName')->nullable();
            $table->string('specificationValue')->nullable();
            // Common Fields
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->enum('deleted', ['Yes', 'No'])->default('No');
            $table->biginteger('lastInsertedBy')->nullable();
            $table->datetime('insertDate')->nullable();
            $table->biginteger('lastUpdatedBy')->nullable();
            $table->datetime('lastupdatedDate')->nullable();
            $table->biginteger('deletedBy')->nullable();
            $table->datetime('deletedDate')->nullable();
            $table->datetime('dbInsertDate')->nullable();
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
        Schema::dropIfExists('tbl_productspecification');
    }
}
