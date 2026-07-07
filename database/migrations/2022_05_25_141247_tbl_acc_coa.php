<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblAccCoa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_acc_coas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->biginteger('code')->nullable();
            $table->biginteger('our_code')->nullable();
            $table->biginteger('limit_from')->nullable();
            $table->biginteger('limit_to')->nullable();
            $table->biginteger('parent_id')->nullable();
            $table->enum('unused', ['Yes', 'No'])->default('No');

            $table->enum('status', ['Active', 'Inactive'])->nullable();
            $table->enum('deleted', ['Yes', 'No'])->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('last_updated_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->dateTime('deleted_date')->format('d/m/Y')->nullable();

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
        Schema::dropIfExists('tbl_acc_coas');
    }
}
