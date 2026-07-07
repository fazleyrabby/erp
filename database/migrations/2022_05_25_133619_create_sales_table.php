<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->biginteger('customer_id');
            $table->string('sale_no');
            $table->datetime('date');
            $table->string('description')->nullable();
            $table->decimal('total_amount', 12, 2);
            $table->decimal('discount', 12, 2)->nullable();
            $table->biginteger('carrying_cost')->nullable();
            $table->decimal('vat', 10, 2)->nullable();
            $table->decimal('ait', 10, 2)->nullable();
            $table->decimal('grand_total', 12, 2);
            $table->decimal('previous_due', 12, 2);
            $table->decimal('total_with_due', 12, 2);
            $table->decimal('current_payment', 12, 2)->default(0.00);
            $table->decimal('current_balance', 12, 2);
            $table->enum('sales_type', ['walkin_sale', 'FS', 'party_sale']);
            // Common Fields
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->enum('deleted', ['Yes', 'No'])->default('No');
            $table->biginteger('created_by')->nullable();
            $table->datetime('created_date')->nullable();
            $table->biginteger('updated_by')->nullable();
            $table->datetime('updated_date')->nullable();
            $table->biginteger('deleted_by')->nullable();
            $table->datetime('deleted_date')->nullable();
            $table->timestamps();
            // End Common Fields
            $table->enum('emi_status', ['Yes', 'No'])->default('No');
            $table->decimal('total_price', 10, 2)->nullable();
            $table->decimal('dues_amount', 10, 2)->nullable();
            $table->decimal('current_dues', 10, 2)->nullable();
            $table->biginteger('no_of_tenure')->nullable();
            $table->date('start_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
