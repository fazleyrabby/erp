<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_loans', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->bigInteger('user_id')->nullable();
            $table->string('amount')->nullable();
            $table->dateTime('month_year')->nullable();
            $table->decimal('tenure')->nullable();
            $table->text('applicable_from')->nullable();
            $table->decimal('adjustment')->nullable();
            $table->longText('cause')->nullable();

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('last_updated_by')->nullable();
            $table->enum('deleted', ['Yes', 'No'])->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->dateTime('deleted_date')->format('d/m/Y')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->nullable();

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
        Schema::dropIfExists('salary_loans');
    }
}
