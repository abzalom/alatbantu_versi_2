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
        Schema::create('nomenklatur_sikds', function (Blueprint $table) {
            $table->id();
            $table->string('id_subkegiatan')->index()->nullable();

            $table->string('kode_bidang')->index()->nullable();
            $table->text('nama_bidang')->nullable();

            $table->string('kode_program')->index()->nullable();
            $table->text('nama_program')->nullable();

            $table->string('kode_kegiatan')->index()->nullable();
            $table->text('nama_kegiatan')->nullable();

            $table->string('kode_subkegiatan')->index()->nullable();
            $table->text('nama_subkegiatan')->nullable();

            $table->text('text')->nullable();
            $table->text('indikator')->nullable();
            $table->string('satuan')->index()->nullable();
            $table->string('klasifikasi_belanja')->index()->nullable();
            $table->enum('sumberdana', ['bg', 'sg', 'dti'])->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomenklatur_sikds');
    }
};
