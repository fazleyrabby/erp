<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('logo')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->text('report_header')->nullable();
            $table->text('report_footer')->nullable();
            $table->string('watermark')->nullable();
            $table->string('month_year')->nullable();
            $table->string('terms_conditions')->nullable();
            $table->string('default_party')->nullable();
            $table->string('currency')->nullable();
            $table->enum('manage_stock_to_sale', ['Yes', 'No'])->default('Yes');
            $table->enum('barcode_exists', ['Yes', 'No'])->nullable();
            $table->enum('deleted', ['Yes', 'No'])->nullable();
            $table->biginteger('created_by')->nullable();
            $table->datetime('created_date')->nullable();
            $table->biginteger('updated_by')->nullable();
            $table->datetime('updated_date')->nullable();
            $table->biginteger('deleted_by')->nullable();
            $table->datetime('deleted_date')->nullable();
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
        Schema::dropIfExists('company_settings');
    }
}
