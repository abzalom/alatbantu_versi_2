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
        Schema::create('a4_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_urusan')->index();
            $table->string('kode_bidang')->index();
            $table->string('kode_program')->index();
            $table->string('kode_kegiatan')->index();
            $table->text('uraian');
            $table->year('tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a4_kegiatans');
    }
};
