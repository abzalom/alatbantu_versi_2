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
        Schema::table('nomenklatur_sikds', function (Blueprint $table) {
            $table->year('tahun')->after('sumberdana')->nullable()->comment('Tahun untuk nomenklatur SIKD');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nomenklatur_sikds', function (Blueprint $table) {
            $table->dropColumn('tahun');
        });
    }
};
