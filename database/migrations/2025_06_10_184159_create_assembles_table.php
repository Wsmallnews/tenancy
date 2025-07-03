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
        Schema::create('assembles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable()->comment('团队ID');
            $table->unsignedBigInteger('appraise_id')->default(0)->comment('评价');

            $table->string('name')->nullable()->comment('收集人');
            $table->string('company')->nullable()->comment('收集单位');
            $table->string('assemble_no')->nullable()->comment('收集编号');

            $table->string('subject_no')->nullable()->comment('所属课题编号');
            $table->string('sub_subject_no')->nullable()->comment('所属子课题编号');
            
            $table->string('longitude')->nullable()->comment('经度');
            $table->string('latitude')->nullable()->comment('纬度');
            $table->string('country_code')->nullable()->comment('国家编号');
            $table->string('country_name')->nullable()->comment('国家');
            $table->string('province_name')->nullable()->comment('省');
            $table->unsignedBigInteger('province_id')->nullable()->comment('省ID');
            $table->string('city_name')->nullable()->comment('市');
            $table->unsignedBigInteger('city_id')->nullable()->comment('市ID');
            $table->string('address')->nullable()->comment('地址');

            $table->string('status')->nullable()->comment('收集状态');
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
        Schema::dropIfExists('assembles');
    }
};
