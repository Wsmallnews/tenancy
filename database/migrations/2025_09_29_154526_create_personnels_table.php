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
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable()->comment('团队ID');

            $table->string('name')->nullable()->comment('姓名');
            $table->string('qualification')->nullable()->comment('学历');
            $table->string('professional_title')->nullable()->comment('职称');
            $table->string('research_focus')->nullable()->comment('研究方向');
            $table->string('research_result')->nullable()->comment('研究成果');
            $table->string('intro')->nullable()->comment('个人简介');

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
        Schema::dropIfExists('personnels');
    }
};
