<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblWarehouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_warehouse', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('wareHouseName');
            $table->string('wareHouseAddress')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->enum('deleted', ['Yes', 'No'])->default('No');
            $table->datetime('createdDate')->nullable();
            $table->biginteger('createdBy')->nullable();
            $table->datetime('lastUpdatedDate')->nullable();
            $table->biginteger('lastUpdatedBy')->nullable();
            $table->biginteger('deletedBy')->nullable();
            $table->datetime('deletedDate')->nullable();
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
        Schema::dropIfExists('tbl_warehouse');
    }
}
