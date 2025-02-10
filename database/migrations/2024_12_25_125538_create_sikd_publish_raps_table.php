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
        Schema::create('sikd_publish_raps', function (Blueprint $table) {
            $table->id();
            $table->enum("sumberdana", ['bg', 'sg', 'dti'])->nullable()->index();
            $table->bigInteger("id_rap")->nullable()->index();
            $table->bigInteger("rencanaanggaranprogram_id")->nullable()->index();
            $table->bigInteger("subkegiatan_id")->nullable()->index();
            $table->bigInteger("subkegiatan_history_id")->nullable()->index();
            $table->string("jenis_kegiatan")->nullable()->index();
            $table->string("target_keluaran")->nullable();
            $table->string("target_keluaran_efisiensi")->nullable();
            $table->string("target_keluaran_non_efisiensi")->nullable();
            $table->string("pagu_alokasi")->nullable();
            $table->string("pagu_efisiensi")->nullable();
            $table->string("pagu_non_efisiensi")->nullable();
            $table->json("lokus")->nullable();
            $table->text("koordinat")->nullable();
            $table->text("koordinat_lintang")->nullable();
            $table->text("koordinat_bujur")->nullable();
            $table->bigInteger("opd_id")->nullable()->index();
            $table->string("jadwal_kegiatan_awal")->nullable()->index();
            $table->string("jadwal_kegiatan_akhir")->nullable()->index();
            $table->text("keterangan")->nullable();
            $table->text("link_file_dukung")->nullable();
            $table->bigInteger("helper_id")->nullable()->index();
            $table->bigInteger("aktivitas_id")->nullable()->index();
            $table->string("jenis_layanan")->nullable()->index();
            $table->string("penerima_manfaat")->nullable()->index();
            $table->string("program_strategis")->nullable()->index();
            $table->json("pendanaan_lain")->nullable();
            $table->string("multiyears")->nullable()->index();
            $table->string("kode_urusan")->nullable()->index();
            $table->text("uraian_urusan")->nullable();
            $table->string("kode_bidang_urusan")->nullable()->index();
            $table->text("uraian_bidang_urusan")->nullable();
            $table->string("kode_program")->nullable()->index();
            $table->text("uraian_program")->nullable();
            $table->string("kode_kegiatan")->nullable()->index();
            $table->text("uraian_kegiatan")->nullable();
            $table->string("kode_subkegiatan")->nullable()->index();
            $table->string("klasifikasi_belanja")->nullable()->index();
            $table->text("subkegiatan_uraian")->nullable();
            $table->text("indikator_keluaran")->nullable();
            $table->string("satuan")->nullable()->index();
            $table->string("kode_subkegiatan_full")->nullable()->index();
            $table->text("text_subkegiatan")->nullable();
            $table->text("opd_uraian")->nullable();
            $table->string("kesesuaian")->nullable()->index();
            $table->year('tahun')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sikd_publish_raps');
    }
};
