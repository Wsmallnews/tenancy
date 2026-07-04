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
        Schema::table('preserves', function (Blueprint $table) {
            $table->date('putin_at')->nullable()->comment('入库日期');
            $table->unsignedBigInteger('num')->nullable()->comment('初始数量');
            $table->string('weight')->nullable()->comment('初始质量');

            // 种质圃保存=germplasm_nursery;
            // 试管苗保存=test_tube_seedling;
            // 超低温保存=ultra_low_temperature
            // 原生境保存=original_habitat
            $table->string('preserve_type')->nullable()->comment('保存类型');

            // 种质圃保存
            $table->string('germplasm_nursery_habitat_information')->nullable()->comment('生境信息');
            $table->string('germplasm_nursery_disease_pest_information')->nullable()->comment('病虫害信息');

            // 试管苗保存
            $table->string('test_tube_seedling_cultivation_medium_formula')->nullable()->comment('培养基配方');
            $table->string('test_tube_seedling_culture_conditions')->nullable()->comment('培养条件');

            // 超低温保存
            $table->date('ultra_low_temperature_at')->nullable()->comment('储藏日期');
            $table->string('ultra_low_temperature_position')->nullable()->comment('保存位置');
            $table->string('ultra_low_temperature_no')->nullable()->comment('冷冻管编号');
            $table->string('ultra_low_temperature_before_handle_method')->nullable()->comment('前处理方式');
            $table->string('ultra_low_temperature_quick_freeze_method')->nullable()->comment('降温速冻方法');
            $table->string('ultra_low_temperature_defrost_method')->nullable()->comment('解冻方法');
            $table->string('ultra_low_temperature_original_vitality_data')->nullable()->comment('原始活力数据');
            $table->string('ultra_low_temperature_recover_cultivation_medium')->nullable()->comment('恢复培养基');
            $table->string('ultra_low_temperature_recovery_process')->nullable()->comment('复苏程序');

            // 原生境保存
            $table->string('original_habitat_variant_type')->nullable()->comment('变种类型');
            $table->string('original_habitat_address')->nullable()->comment('详细地点');
            $table->string('original_habitat_longitude')->nullable()->comment('经度');
            $table->string('original_habitat_latitude')->nullable()->comment('纬度');
            $table->string('original_habitat_terrain')->nullable()->comment('地形');
            $table->string('original_habitat_slope')->nullable()->comment('坡度');
            $table->string('original_habitat_illuminate')->nullable()->comment('光照');
            $table->string('original_habitat_moisture')->nullable()->comment('水分');
            $table->string('original_habitat_nursery_habitat_type')->nullable()->comment('生境类型');
            $table->string('original_habitat_associated_plants')->nullable()->comment('伴生植物');
            $table->string('original_habitat_population_num')->nullable()->comment('种群数量');
            $table->string('original_habitat_breeding_situation')->nullable()->comment('繁殖情况');
            $table->string('original_habitat_phenological_record')->nullable()->comment('物候记录');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preserves', function (Blueprint $table) {
            $table->dropColumn('putin_at');
            $table->dropColumn('num');
            $table->dropColumn('weight');

            // 种质圃保存=germplasm_nursery;
            // 试管苗保存=test_tube_seedling;
            // 超低温保存=ultra_low_temperature
            // 原生境保存=original_habitat
            $table->dropColumn('preserve_type');

            // 种质圃保存
            $table->dropColumn('germplasm_nursery_habitat_information');
            $table->dropColumn('germplasm_nursery_disease_pest_information');

            // 试管苗保存
            $table->dropColumn('test_tube_seedling_cultivation_medium_formula');
            $table->dropColumn('test_tube_seedling_culture_conditions');

            // 超低温保存
            $table->dropColumn('ultra_low_temperature_at');
            $table->dropColumn('ultra_low_temperature_position');
            $table->dropColumn('ultra_low_temperature_no');
            $table->dropColumn('ultra_low_temperature_before_handle_method');
            $table->dropColumn('ultra_low_temperature_quick_freeze_method');
            $table->dropColumn('ultra_low_temperature_defrost_method');
            $table->dropColumn('ultra_low_temperature_original_vitality_data');
            $table->dropColumn('ultra_low_temperature_recover_cultivation_medium');
            $table->dropColumn('ultra_low_temperature_recovery_process');

            // 原生境保存
            $table->dropColumn('original_habitat_variant_type');
            $table->dropColumn('original_habitat_address');
            $table->dropColumn('original_habitat_longitude');
            $table->dropColumn('original_habitat_latitude');
            $table->dropColumn('original_habitat_terrain');
            $table->dropColumn('original_habitat_slope');
            $table->dropColumn('original_habitat_illuminate');
            $table->dropColumn('original_habitat_moisture');
            $table->dropColumn('original_habitat_nursery_habitat_type');
            $table->dropColumn('original_habitat_associated_plants');
            $table->dropColumn('original_habitat_population_num');
            $table->dropColumn('original_habitat_breeding_situation');
            $table->dropColumn('original_habitat_phenological_record');
        });
    }
};
