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
        Schema::create('b5_target_aktifitas_utama_otsuses', function (Blueprint $table) {
            $table->id();
            // $table->string('kode_unik_tema')->index();
            // $table->string('kode_unik_program')->index();
            // $table->string('kode_unik_keluaran')->index();
            // $table->string('kode_unik_aktifitas')->index();
            // $table->string('kode_unik_target_aktifitas')->unique();

            $table->string('kode_tema')->index();
            $table->string('kode_program')->index();
            $table->string('kode_keluaran')->index();;
            $table->string('kode_aktifitas')->index();
            $table->string('kode_target_aktifitas')->unique();
            $table->text('uraian');
            $table->string('satuan');
            // $table->decimal('volume', 32, 2)->nullable();
            // $table->enum('sumberdana', [
            //     "PAD",
            //     "DAU",
            //     "DBH",
            //     "DAK Fisik",
            //     "DAK Non Fisik",
            //     "Otsus 1%",
            //     "Otsus 1,25%",
            //     "DTI",
            //     "Tambahan DTI Migas",
            //     "Belanja KL",
            //     "Lainnya",
            //     "Tidak Ada",
            // ])->index()->nullable();
            $table->year('tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('b5_target_aktifitas_utama_otsuses');
    }
};
