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
        Schema::create('accurate_identifies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable()->comment('团队ID');
            $table->unsignedBigInteger('appraise_id')->default(0)->comment('评价');

            $table->string('gene_identify_method')->nullable()->comment('基因型鉴定方法');
            $table->string('method_params')->nullable()->comment('方法参数');
            $table->string('sequencing_platform')->nullable()->comment('测序平台');
            $table->string('sequencing_technology')->nullable()->comment('测序技术');
            $table->bigInteger('f_reads_length')->nullable()->comment('F端reads读长');
            $table->string('entity_data_one')->nullable()->comment('实体数据1 MD5');
            $table->bigInteger('r_reads_length')->nullable()->comment('R端reads读长');
            $table->string('entity_data_two')->nullable()->comment('实体数据2 MD5');
            $table->string('reference_sequence')->nullable()->comment('参考序列 MD5');
            $table->string('sample_no')->nullable()->comment('样本编号');
            $table->string('identify_name')->nullable()->comment('鉴定人');
            $table->string('identify_at')->nullable()->comment('鉴定时间');
            $table->string('identify_conclusion')->nullable()->comment('鉴定结论');

            $table->string('status')->nullable()->comment('鉴定状态');
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
        Schema::dropIfExists('accurate_identifies');
    }
};
