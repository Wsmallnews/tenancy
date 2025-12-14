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
        Schema::create('appraise_applies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable()->comment('团队ID');
            $table->unsignedBigInteger('appraise_id')->default(0)->comment('评价');
            $table->unsignedBigInteger('user_id')->default(0)->comment('用户');

            $table->string('name')->nullable()->comment('申请人');
            $table->string('phone')->nullable()->comment('联系方式');
            $table->string('company_name')->nullable()->comment('用种单位');

            $table->string('status')->nullable()->comment('状态');
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
        Schema::dropIfExists('appraise_applies');
    }
};
