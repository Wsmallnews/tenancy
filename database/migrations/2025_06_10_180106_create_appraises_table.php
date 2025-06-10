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
        Schema::create('appraises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->default(0)->comment('团队ID');
            $table->unsignedBigInteger('category_id')->default(0)->comment('分类');
            $table->string('resource_no')->nullable()->comment('种质资源编号');
            $table->string('germplasm_no')->nullable()->comment('种质库编号');
            $table->string('original_no')->nullable()->comment('原始编号');
            $table->string('gather_no')->nullable()->comment('采集号');
            $table->string('name')->nullable()->comment('中文名');
            $table->string('en_name')->nullable()->comment('英文名');
            $table->string('az_name')->nullable()->comment('拉丁学名');

            $table->string('subject_name')->nullable()->comment('科名');
            $table->string('genus_name')->nullable()->comment('属名');
            $table->string('species_name')->nullable()->comment('种名');

            $table->string('country_name')->nullable()->comment('国家');
            $table->string('province_name')->nullable()->comment('省');
            $table->string('city_name')->nullable()->comment('市');
            $table->string('address')->nullable()->comment('地址');
            
            $table->integer('altitude')->nullable()->comment('海拔');
            $table->string('longitude')->nullable()->comment('经度');
            $table->string('latitude')->nullable()->comment('纬度');

            $table->string('source_country_name')->nullable()->comment('来源国');
            $table->string('source_province_name')->nullable()->comment('来源省');
            $table->string('source_city_name')->nullable()->comment('来源市');
            $table->string('source_address')->nullable()->comment('来源地');
            
            $table->string('save_company')->nullable()->comment('保存单位');
            $table->string('save_company_no')->nullable()->comment('保存单位编号');
            $table->string('pedigree')->nullable()->comment('系谱');
            $table->string('breeding_company')->nullable()->comment('选育单位');
            $table->date('cultivationd_at')->nullable()->comment('育成年份');
            $table->string('breeding_method')->nullable()->comment('选育方法');

            $table->json('options')->nullable()->comment('选项');
            $table->string('status')->nullable()->comment('评价状态');
            $table->unsignedInteger('order_column')->nullable()->index()->comment('排序');
            $table->timestamps();
            $table->softDeletes();
            $table->index('team_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appraises');
    }
};
