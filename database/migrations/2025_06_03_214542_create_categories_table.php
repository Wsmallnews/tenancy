<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->comment('分类');
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('team_id')->default(0)->comment('团队ID');
            $table->nestedSet();        // Nested Set fields for hierarchical structure
            $table->string('name')->nullable()->comment('名称');
            $table->string('remark')->nullable()->comment('备注');
            $table->json('options')->nullable()->comment('选项');
            $table->enum('status', ['normal', 'hidden'])->default('normal')->comment('状态:normal=正常,hidden=隐藏');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
