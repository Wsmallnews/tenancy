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
        Schema::create('posts', function (Blueprint $table) {
            $table->comment('资讯');
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('team_id')->default(0)->comment('团队ID');
            $table->string('type')->nullable()->comment('类型');
            $table->string('title')->nullable()->comment('标题');
            $table->string('description')->nullable()->comment('描述');
            $table->json('options')->nullable()->comment('选项');

            $table->string('status')->nullable()->comment('状态');
            $table->unsignedInteger('order_column')->nullable()->index()->comment('排序');
            $table->timestamps();
            $table->softDeletes();
            $table->index('team_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
