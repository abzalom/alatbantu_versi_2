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
        Schema::create('a5_subkegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_urusan')->index();
            $table->string('kode_bidang')->index();
            $table->string('kode_program')->index();
            $table->string('kode_kegiatan')->index();
            $table->string('kode_subkegiatan')->unique();
            $table->text('uraian');
            $table->text('indikator');
            $table->text('kinerja');
            $table->string('satuan');
            $table->string('klasifikasi_belanja')->index();
            $table->boolean('rutin')->index();
            $table->boolean('gaji')->index();
            $table->text('referensi');
            $table->boolean('prioritas_pendidikan')->index();
            $table->boolean('pendukung_pendidikan')->index();
            $table->boolean('prioritas_kesehatan')->index();
            $table->boolean('pendukung_kesehatan')->index();
            $table->boolean('prioritas_pu')->index();
            $table->boolean('pendukung_pu')->index();
            $table->year('tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a5_subkegiatans');
    }
};
