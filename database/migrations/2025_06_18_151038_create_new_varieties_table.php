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
        Schema::create('new_varieties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable()->comment('团队ID');
            $table->unsignedBigInteger('appraise_id')->default(0)->comment('评价');

            $table->string('variety_no')->nullable()->comment('品种权号');
            $table->string('name')->nullable()->comment('品种权人');
            $table->date('variety_at')->nullable()->comment('年份');
            $table->string('cultivate_name')->nullable()->comment('培育人');
            
            $table->string('status')->nullable()->comment('新品种状态');
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
        Schema::dropIfExists('new_varieties');
    }
};
