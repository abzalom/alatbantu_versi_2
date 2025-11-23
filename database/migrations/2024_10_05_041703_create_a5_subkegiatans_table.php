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
            $table->string('kode_subkegiatan')->index();
            $table->text('uraian');
            $table->text('indikator')->nullable();
            $table->text('kinerja')->nullable();
            $table->string('satuan')->nullable();
            $table->string('klasifikasi_belanja')->index()->nullable();
            $table->boolean('rutin')->index()->nullable();
            $table->boolean('gaji')->index()->nullable();
            $table->text('referensi')->nullable();
            $table->boolean('prioritas_pendidikan')->index()->nullable();
            $table->boolean('pendukung_pendidikan')->index()->nullable();
            $table->boolean('prioritas_kesehatan')->index()->nullable();
            $table->boolean('pendukung_kesehatan')->index()->nullable();
            $table->boolean('prioritas_pu')->index()->nullable();
            $table->boolean('pendukung_pu')->index()->nullable();
            $table->json('tag')->nullable();
            $table->text('definisi')->nullable();
            $table->text('pelaksana')->nullable();
            $table->string('spm')->index()->nullable();
            $table->string('jenis')->index()->nullable();
            $table->text('subkegiatan_sebelumnya')->nullable();
            $table->year('tahun')->nullable();
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
