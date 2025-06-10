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
        Schema::create('preserves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->default(0)->comment('团队ID');
            $table->unsignedBigInteger('appraise_id')->default(0)->comment('评价');

            $table->string('preserve_no')->nullable()->comment('保存编号');
            $table->string('resource_no')->nullable()->comment('种质资源编号');
            $table->string('germplasm_name')->nullable()->comment('种质中文名');
            $table->string('germplasm_az_name')->nullable()->comment('种质拉丁学名');
            $table->string('preserve_position')->nullable()->comment('保存位置');

            $table->string('status')->nullable()->comment('保存状态');
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
        Schema::dropIfExists('preserves');
    }
};
