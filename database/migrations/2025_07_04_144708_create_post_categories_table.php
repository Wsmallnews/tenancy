<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('post_categories', function (Blueprint $table) {
            $table->comment('资讯分类');
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable()->comment('团队ID');
            $table->nestedSet();        // Nested Set fields for hierarchical structure
            $table->string('name')->nullable()->comment('名称');
            $table->string('remark')->nullable()->comment('备注');
            $table->json('options')->nullable()->comment('选项');
            $table->string('status')->nullable()->comment('状态');
            $table->timestamps();
        });

        Schema::create('post_post_category', function (Blueprint $table) {
            $table->foreignId('post_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->primary(['post_category_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_categories');
        Schema::dropIfExists('post_post_category');
    }
};
