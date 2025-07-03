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
        Schema::create('theses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable()->comment('团队ID');
            $table->unsignedBigInteger('thesis_type_id')->default(0)->comment('论文类型');
            $table->string('title')->nullable()->comment('标题');
            $table->string('author_name')->nullable()->comment('作者');
            $table->string('company_name')->nullable()->comment('所属单位');
            $table->string('description')->nullable()->comment('摘要');
            $table->string('journal')->nullable()->comment('发布期刊');
            $table->string('issue_number')->nullable()->comment('卷期号');
            $table->date('published_at')->nullable()->comment('出版日期');
            $table->string('remark')->nullable()->comment('备注');
            $table->unsignedInteger('order_column')->nullable()->index()->comment('排序');
            $table->string('status')->nullable()->comment('状态');
            $table->timestamps();
            $table->softDeletes();
            $table->index('team_id');
            $table->index('thesis_type_id');
        });

        Schema::create('thesis_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable()->comment('团队ID');
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
        Schema::dropIfExists('theses');

        Schema::dropIfExists('thesis_types');
    }
};
