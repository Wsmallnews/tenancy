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
        Schema::create('awards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->default(0)->comment('团队ID');
            $table->unsignedBigInteger('award_type_id')->default(0)->comment('奖项类型');
            $table->string('name')->nullable()->comment('名称');
            $table->string('award_agency')->nullable()->comment('授奖机构');
            $table->date('award_at')->nullable()->comment('获奖日期');
            $table->string('level')->nullable()->comment('级别');
            $table->string('award_name')->nullable()->comment('获奖人/团队');
            $table->string('remark')->nullable()->comment('备注');
            $table->unsignedInteger('order_column')->nullable()->index()->comment('排序');
            $table->string('status')->nullable()->comment('状态');
            $table->timestamps();
            $table->softDeletes();
            $table->index('team_id');
            $table->index('award_type_id');
        });

        Schema::create('award_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->default(0)->comment('团队ID');
            $table->string('name')->nullable()->comment('类型名称');
            $table->unsignedInteger('order_column')->nullable()->index()->comment('排序');
            $table->string('status')->nullable()->comment('状态');
            $table->timestamps();
            $table->index('team_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('awards');

        Schema::dropIfExists('award_types');
    }
};
