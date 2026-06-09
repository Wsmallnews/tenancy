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
        Schema::create('phenotype_identifies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable()->comment('团队ID');
            $table->unsignedBigInteger('appraise_id')->nullable()->comment('评价ID');
            $table->unsignedBigInteger('category_id')->default(0)->comment('分类ID');
            $table->string('name')->nullable()->comment('鉴定名称');
            $table->text('description')->nullable()->comment('鉴定说明');
            $table->json('options')->nullable()->comment('自定义字段值');
            $table->string('status')->nullable()->comment('状态');
            $table->unsignedInteger('order_column')->nullable()->index()->comment('排序');
            $table->timestamps();
            $table->softDeletes();
            $table->index('team_id');
            $table->index('appraise_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phenotype_identifies');
    }
};
