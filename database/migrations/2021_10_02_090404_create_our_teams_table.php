<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOurTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('our_teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('member_name')->unique();
            $table->string('member_desingnation')->nullable();
            $table->bigInteger('priority')->nullable();
            $table->bigInteger('mobile_number')->nullable();
            $table->bigInteger('working_hour')->nullable();
            $table->bigInteger('current_grade')->nullable();
            $table->bigInteger('current_step')->nullable();
            $table->bigInteger('group_id')->nullable();
            $table->bigInteger('sheet_id')->nullable();
            $table->bigInteger('account_no')->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->decimal('laundry', 12, 2)->nullable();
            $table->decimal('phone_bill', 12, 2)->nullable();
            $table->decimal('ta_da', 12, 2)->nullable();
            $table->longtext('address')->nullable();
            $table->string('member_image')->nullable();
            $table->string('job_location')->nullable();
            $table->string('salary_type')->nullable();
            $table->string('is_employee')->nullable();
            $table->string('member_education')->nullable();
            $table->longtext('description')->nullable();
            $table->text('social_links')->nullable();
            $table->longtext('short_note')->nullable();
            $table->date('joining_date')->format('d/m/Y')->nullable();
            $table->date('job_left_date')->format('d/m/Y')->nullable();

            $table->integer('created_by')->nullable();
            $table->bigInteger('last_updated_by')->nullable();
            $table->enum('deleted', ['YES', 'NO']);
            $table->bigInteger('deleted_by')->nullable();
            $table->date('deleted_date')->format('d/m/Y')->nullable();

            $table->string('referred_by')->nullable();
            $table->string('status')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
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
        Schema::dropIfExists('our_teams');
    }
}
