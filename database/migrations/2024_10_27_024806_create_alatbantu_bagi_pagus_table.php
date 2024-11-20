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
        Schema::create('alatbantu_bagi_pagus', function (Blueprint $table) {
            $table->id();
            $table->string('kode_unik_opd')->index();
            $table->string('kode_opd')->index();
            $table->string('nama_opd')->index();
            $table->text('text_opd');
            $table->text('uraian');
            $table->text('keterangan')->nullable();
            $table->enum('jenis', ['rutin', 'non rutin'])->nullable();
            $table->enum('belanja', ['skpd', 'non skpd'])->nullable();
            $table->decimal('pad', 64, 4)->nullable();
            $table->decimal('dbh_prov', 64, 4)->nullable();
            $table->decimal('dbh_pusat', 64, 4)->nullable();
            $table->decimal('dau_umum', 64, 4)->nullable();
            $table->decimal('dau_pkkk', 64, 4)->nullable();
            $table->decimal('dau_pendidikan', 64, 4)->nullable();
            $table->decimal('dau_kesehatan', 64, 4)->nullable();
            $table->decimal('dau_infra', 64, 4)->nullable();
            $table->decimal('dak_fisik', 64, 4)->nullable();
            $table->decimal('dak_nonfisik', 64, 4)->nullable();
            $table->decimal('otsus_bg', 64, 4)->nullable();
            $table->decimal('otsus_sg', 64, 4)->nullable();
            $table->decimal('dti', 64, 4)->nullable();
            $table->decimal('dana_desa', 64, 4)->nullable();
            $table->year('tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alatbantu_bagi_pagus');
    }
};
