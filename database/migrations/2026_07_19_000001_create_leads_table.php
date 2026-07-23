<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('designation')->nullable();
            $table->string('source')->default('Website');
            $table->string('lead_status')->default('New');
            $table->decimal('potential_value', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->text('address')->nullable();
            $table->string('website')->nullable();
            $table->string('social_link')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->bigInteger('assigned_to')->nullable();
            $table->bigInteger('converted_to_party_id')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->enum('deleted', ['Yes', 'No'])->default('No');
            $table->bigInteger('created_by')->nullable();
            $table->datetime('created_date')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->datetime('updated_date')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->datetime('deleted_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leads');
    }
};
