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
        Schema::whenTableDoesntHaveColumn(
            'appraises',
            'save_company_id',
            function (Blueprint $table) {
                $table->unsignedBigInteger('save_company_id')->default(0)->after('source_address')->comment('单位ID');
                $table->dropColumn('save_company');
                $table->dropColumn('save_company_no');

                $table->unsignedBigInteger('breeding_company_id')->default(0)->after('pedigree')->comment('选育单位ID');
                $table->dropColumn('breeding_company');
            }
        );

        Schema::whenTableDoesntHaveColumn(
            'assembles',
            'assemble_company_id',
            function (Blueprint $table) {
                $table->unsignedBigInteger('assemble_company_id')->default(0)->after('name')->comment('收集单位ID');
                $table->dropColumn('company');
            }
        );

        Schema::whenTableDoesntHaveColumn(
            'catalogs',
            'assemble_company_id',
            function (Blueprint $table) {
                $table->unsignedBigInteger('assemble_company_id')->default(0)->after('assemble_address')->comment('收集单位ID');
                $table->dropColumn('assemble_company');

                $table->unsignedBigInteger('temp_save_company_id')->default(0)->after('provider_phone')->comment('临时保存单位ID');
                $table->dropColumn('temp_save_company');

                $table->unsignedBigInteger('original_save_company_id')->default(0)->after('temp_save_company_id')->comment('原保存单位ID');
                $table->dropColumn('original_save_company');
                $table->dropColumn('original_save_company_no');
            }
        );

        Schema::whenTableDoesntHaveColumn(
            'theses',
            'company_id',
            function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->default(0)->after('author_name')->comment('收集单位ID');
                $table->dropColumn('company_name');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
