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
        Schema::table('appraises', function (Blueprint $table) {
            $table->string('nhgrc_pending_id')->nullable()->comment('NHGRC 待审核ID');
            $table->string('nhgrc_external_ref')->nullable()->comment('NHGRC 外部引用ID');
        });

        Schema::table('sn_posts', function (Blueprint $table) {
            $table->string('nhgrc_pending_id')->nullable()->comment('NHGRC 待审核ID');
            $table->string('nhgrc_external_ref')->nullable()->comment('NHGRC 外部引用ID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appraises', function (Blueprint $table) {
            $table->dropColumn('nhgrc_pending_id');
            $table->dropColumn('nhgrc_external_ref');
        });

        Schema::table('sn_posts', function (Blueprint $table) {
            $table->dropColumn('nhgrc_pending_id');
            $table->dropColumn('nhgrc_external_ref');
        });
    }
};