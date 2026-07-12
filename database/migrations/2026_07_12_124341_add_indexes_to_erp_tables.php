<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tbl_acc_expenses', function (Blueprint $table) {
            $table->index('tbl_crm_vendor_id');
            $table->index('deleted');
            $table->index('status');
        });

        Schema::table('tbl_acc_bills', function (Blueprint $table) {
            $table->index('tbl_crm_vendor_id');
            $table->index('deleted');
            $table->index('status');
        });

        Schema::table('tbl_acc_transactions', function (Blueprint $table) {
            $table->index('tbl_coa_from_id');
            $table->index('tbl_coa_to_id');
            $table->index('deleted');
            $table->index('status');
        });

        Schema::table('parties', function (Blueprint $table) {
            $table->index('party_type');
            $table->index('deleted');
            $table->index('status');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('type');
            $table->index('deleted');
            $table->index('status');
        });

        Schema::table('sale_orders', function (Blueprint $table) {
            $table->index('order_status');
            $table->index('deleted');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::table('tbl_acc_expenses', function (Blueprint $table) {
            $table->dropIndex(['tbl_crm_vendor_id']);
            $table->dropIndex(['deleted']);
            $table->dropIndex(['status']);
        });

        Schema::table('tbl_acc_bills', function (Blueprint $table) {
            $table->dropIndex(['tbl_crm_vendor_id']);
            $table->dropIndex(['deleted']);
            $table->dropIndex(['status']);
        });

        Schema::table('tbl_acc_transactions', function (Blueprint $table) {
            $table->dropIndex(['tbl_coa_from_id']);
            $table->dropIndex(['tbl_coa_to_id']);
            $table->dropIndex(['deleted']);
            $table->dropIndex(['status']);
        });

        Schema::table('parties', function (Blueprint $table) {
            $table->dropIndex(['party_type']);
            $table->dropIndex(['deleted']);
            $table->dropIndex(['status']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['deleted']);
            $table->dropIndex(['status']);
        });

        Schema::table('sale_orders', function (Blueprint $table) {
            $table->dropIndex(['order_status']);
            $table->dropIndex(['deleted']);
            $table->dropIndex(['status']);
        });
    }
};
