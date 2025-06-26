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
        Schema::create('navigations', function (Blueprint $table) {
            $table->comment('导航');
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('team_id')->default(0)->comment('团队ID');
            $table->nestedSet();        // Nested Set fields for hierarchical structure
            $table->string('name')->nullable()->comment('名称');
            $table->string('description')->nullable()->comment('描述');
            $table->string('type')->nullable()->comment('类型');
            $table->string('slug')->nullable()->comment('slug');
            $table->json('options')->nullable()->comment('选项');
            $table->string('status', 20)->comment('状态');
            $table->timestamps();
            $table->index('team_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navigations');
    }
};
