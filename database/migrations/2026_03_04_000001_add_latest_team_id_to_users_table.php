<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add latest_team_id column to track user's most recently accessed team.
     */
    public function up(): void
    {
        Schema::whenTableDoesntHaveColumn(
            'users',
            'latest_team_id',
            function (Blueprint $table) {
                $table->unsignedBigInteger('latest_team_id')->nullable()->after('source_user_id')
                    ->comment('用户最近访问的团队ID');
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
            'latest_team_id',
            function (Blueprint $table) {
                $table->dropColumn('latest_team_id');
            }
        );
    }
};
