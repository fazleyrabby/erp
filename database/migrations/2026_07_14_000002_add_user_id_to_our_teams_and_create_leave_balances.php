<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('our_teams', function (Blueprint $table) {
            if (!Schema::hasColumn('our_teams', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
        });

        Schema::create('employee_leave_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('leave_type');
            $table->integer('year');
            $table->decimal('total_days', 8, 2)->default(0);
            $table->decimal('used_days', 8, 2)->default(0);
            $table->timestamps();

            $table->unique(['employee_id', 'leave_type', 'year']);
        });
    }

    public function down(): void
    {
        Schema::table('our_teams', function (Blueprint $table) {
            if (Schema::hasColumn('our_teams', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });

        Schema::dropIfExists('employee_leave_balances');
    }
};
