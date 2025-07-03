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
        Schema::create('patents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable()->comment('团队ID');
            $table->unsignedBigInteger('patent_type_id')->default(0)->comment('专利类型');
            $table->string('name')->nullable()->comment('名称');
            $table->string('patent_apply_no')->nullable()->comment('专利申请号');
            $table->string('patent_no')->nullable()->comment('专利号');
            $table->date('applied_at')->nullable()->comment('申请日期');
            $table->date('authd_at')->nullable()->comment('授权日期');
            $table->string('status')->nullable()->comment('专利状态');
            $table->string('author_name')->nullable()->comment('发明人/作者');
            $table->string('description')->nullable()->comment('摘要');
            $table->string('remark')->nullable()->comment('备注');
            $table->unsignedInteger('order_column')->nullable()->index()->comment('排序');
            $table->timestamps();
            $table->softDeletes();
            $table->index('team_id');
            $table->index('patent_type_id');
        });

        Schema::create('patent_types', function (Blueprint $table) {
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
        Schema::dropIfExists('patents');

        Schema::dropIfExists('patent_types');
    }
};
