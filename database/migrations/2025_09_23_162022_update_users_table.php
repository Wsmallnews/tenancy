<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::whenTableDoesntHaveColumn(
            'users',
            'user_type',
            function (Blueprint $table) {
                $table->string('user_type')->nullable()->after('avatar_url')->comment('用户类型');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::whenTableHasColumn(
            'users',
            'user_type',
            function (Blueprint $table) {
                $table->dropColumn('user_type');
            }
        );
    }
};
