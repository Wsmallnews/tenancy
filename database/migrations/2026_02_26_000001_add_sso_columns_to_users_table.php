<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * T004: Add source_platform and source_user_id columns for cross-platform SSO login.
     */
    public function up(): void
    {
        Schema::whenTableDoesntHaveColumn(
            'users',
            'source_platform',
            function (Blueprint $table) {
                $table->string('source_platform', 32)->nullable()->after('user_type')
                    ->comment('用户来源平台，NULL表示本平台注册用户');
                $table->string('source_user_id', 64)->nullable()->after('source_platform')
                    ->comment('来源平台的用户ID');
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
            'source_platform',
            function (Blueprint $table) {
                $table->dropColumn(['source_platform', 'source_user_id']);
            }
        );
    }
};
