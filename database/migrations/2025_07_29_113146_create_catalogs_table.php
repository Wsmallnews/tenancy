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
            $table->string('code_type')->nullable()->comment('编码类别');
            $table->string('assemble_no')->nullable()->comment('收集编号');
            $table->string('original_no')->nullable()->comment('原始编号');
            $table->string('assemble_at')->nullable()->comment('收集日期');
            $table->string('resource_method')->nullable()->comment('资源来源方式');
            $table->string('catalog_at')->nullable()->comment('编目时间');


            $table->string('country_code')->nullable()->comment('原产国编号');
            $table->string('country_name')->nullable()->comment('原产国');
            $table->string('province_name')->nullable()->comment('原产省');
            $table->unsignedBigInteger('province_id')->nullable()->comment('原产省ID');
            $table->string('city_name')->nullable()->comment('原产市');
            $table->unsignedBigInteger('city_id')->nullable()->comment('原产市ID');
            $table->string('address')->nullable()->comment('原产地');
            
            $table->string('source_country_code')->nullable()->comment('来源国编号');
            $table->string('source_country_name')->nullable()->comment('来源国');
            $table->string('source_province_name')->nullable()->comment('来源省');
            $table->unsignedBigInteger('source_province_id')->nullable()->comment('来源省ID');
            $table->string('source_city_name')->nullable()->comment('来源市');
            $table->unsignedBigInteger('source_city_id')->nullable()->comment('来源市ID');
            $table->string('source_address')->nullable()->comment('来源地址');
            $table->integer('altitude')->nullable()->comment('海拔');
            $table->string('longitude')->nullable()->comment('经度');
            $table->string('latitude')->nullable()->comment('纬度');


            $table->string('assemble_address')->nullable()->comment('收集地点');
            $table->string('assemble_company')->nullable()->comment('收集单位');
            $table->string('assember')->nullable()->comment('收集者');
            $table->string('assember_phone')->nullable()->comment('收集者手机号');
            $table->string('provider')->nullable()->comment('提供者');
            $table->string('provider_phone')->nullable()->comment('提供者手机号');

            $table->string('temp_save_company')->nullable()->comment('临时保存单位');
            $table->string('original_save_company')->nullable()->comment('原保存单位');
            $table->string('original_save_company_no')->nullable()->comment('原保存单位编号');
            $table->string('inspect_assemble_project')->nullable()->comment('考察收集项目');

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
