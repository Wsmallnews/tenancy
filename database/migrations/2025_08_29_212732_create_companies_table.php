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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable()->comment('团队ID');

            $table->string('name')->nullable()->comment('名称');
            $table->string('code')->nullable()->comment('编号');
            $table->string('contact')->nullable()->comment('联系人');
            $table->string('contact_phone')->nullable()->comment('联系人手机号');
            $table->string('email')->nullable()->comment('邮箱');

            $table->string('province_name')->nullable()->comment('省');
            $table->unsignedBigInteger('province_id')->nullable()->comment('省ID');
            $table->string('city_name')->nullable()->comment('市');
            $table->unsignedBigInteger('city_id')->nullable()->comment('市ID');
            $table->string('district_name')->nullable()->comment('区县');
            $table->unsignedBigInteger('district_id')->nullable()->comment('区县ID');
            $table->string('address')->nullable()->comment('地址');

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
        Schema::dropIfExists('companies');
    }
};
