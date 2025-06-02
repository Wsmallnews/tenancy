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
            'avatar_url',
            function (Blueprint $table) {
                $table->string('avatar_url')->nullable()->after('name')->comment('头像');
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
            'avatar_url',
            function (Blueprint $table) {
                $table->dropColumn('avatar_url');
            }
        );
    }
};
