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
        Schema::create('catalogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable()->comment('团队ID');
            $table->unsignedBigInteger('appraise_id')->default(0)->comment('评价');

            $table->string('name')->nullable()->comment('作物名称');

            $table->string('assemble_no')->nullable()->comment('收集编号');     // 和 assembles 中的重复
            $table->string('assemble_at')->nullable()->comment('收集日期');
            $table->string('resource_method')->nullable()->comment('资源来源方式');
            $table->string('catalog_at')->nullable()->comment('编目时间');
            $table->string('assemble_address')->nullable()->comment('收集地点');
            $table->string('assemble_company')->nullable()->comment('收集单位');        // 和 assembles 中的重复


            $table->string('status')->nullable()->comment('编目状态');
            $table->unsignedInteger('order_column')->nullable()->index()->comment('排序');
            $table->timestamps();
            $table->softDeletes();
            $table->index('team_id');
            $table->index('appraise_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogs');
    }
};
