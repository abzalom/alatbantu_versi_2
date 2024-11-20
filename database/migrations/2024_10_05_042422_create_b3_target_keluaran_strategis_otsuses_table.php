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
        Schema::create('b3_target_keluaran_strategis_otsuses', function (Blueprint $table) {
            $table->id();
            // $table->string('kode_unik_tema')->index();
            // $table->string('kode_unik_program')->index();
            // $table->string('kode_unik_keluaran')->unique();

            $table->string('kode_tema')->index();
            $table->string('kode_program')->index();
            $table->string('kode_keluaran')->unique();
            $table->text('uraian');
            $table->string('satuan');
            $table->year('tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('b3_target_keluaran_strategis_otsuses');
    }
};
