<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('member_id');
            $table->string('member_name')->unique();
            $table->string('member_desingnation')->nullable();
            $table->bigInteger('mobile_number')->nullable();
            $table->bigInteger('working_hour')->nullable();
            $table->longtext('address')->nullable();
            $table->string('member_education')->nullable();
            $table->longtext('description')->nullable();
            $table->string('member_image')->nullable();
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
