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
        Schema::table('opd_tag_otsuses', function (Blueprint $table) {
            $table->string('kode_indikator')->nullable()->index()->after('kode_target_aktifitas');
            $table->string('kode_unik_indikator')->nullable()->index()->after('kode_indikator');
        });

        Schema::table('rap_otsuses', function (Blueprint $table) {
            $table->string('kode_indikator')->nullable()->index()->after('kode_target_aktifitas');
            $table->string('kode_unik_indikator')->nullable()->index()->after('kode_indikator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opd_tag_otsuses', function (Blueprint $table) {
            $table->dropColumn(['kode_indikator', 'kode_unik_indikator']);
        });

        Schema::table('rap_otsuses', function (Blueprint $table) {
            $table->dropColumn(['kode_indikator', 'kode_unik_indikator']);
        });
    }
};
