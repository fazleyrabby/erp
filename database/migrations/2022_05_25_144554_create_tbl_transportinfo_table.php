<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblTransportinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_transportinfo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transportName');
            $table->string('contactPerson')->nullable();
            $table->string('contactNo')->nullable();
            $table->text('address')->nullable();
            $table->string('email')->nullable();
            $table->text('remarks')->nullable();
            // Common Fields
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->enum('deleted', ['Yes', 'No'])->default('No');
            $table->biginteger('createdBy')->nullable();
            $table->datetime('createdDate')->nullable();
            $table->biginteger('deletedBy')->nullable();
            $table->datetime('deletedDate')->nullable();
            $table->biginteger('lastUpdatedBy')->nullable();
            $table->datetime('lastUpdatedDate')->nullable();
            $table->timestamps();
            // Not Common Field
            $table->string('transport_name_bangla')->nullable();
            $table->string('contact_person_bangla')->nullable();
            $table->string('contact_number_bangla')->nullable();
            $table->string('address_bangla')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_transportinfo');
    }
}
