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
        Schema::create('project_manages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable()->comment('团队ID');

            $table->string('project_no')->nullable()->comment('项目编号');
            $table->string('name')->nullable()->comment('项目名称');
            $table->string('type')->nullable()->comment('项目类型');
            $table->string('subject')->nullable()->comment('所属学科');
            $table->string('initiation_company')->nullable()->comment('立项单位');
            $table->string('level')->nullable()->comment('项目级别');
            $table->string('manager_name')->nullable()->comment('负责人');
            $table->string('attend_name')->nullable()->comment('参与人');
            $table->string('start_at')->nullable()->comment('开始时间');
            $table->string('end_at')->nullable()->comment('结束时间');
            $table->string('budget')->nullable()->comment('总预算');

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
        Schema::dropIfExists('project_manages');
    }
};
