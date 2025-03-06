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
        Schema::table('rap_otsuses', function (Blueprint $table) {
            $table->string('kode_kegiatan')->index()->after('kode_program');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rap_otsuses', function (Blueprint $table) {
            $table->dropColumn('kode_kegiatan');
        });
    }
};
