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
        Schema::create('rap_otsuses', function (Blueprint $table) {
            $table->id();
            $table->string('kode_unik_opd')->index();
            $table->string('kode_unik_opd_tag_bidang')->index();
            $table->string('kode_unik_opd_tag_otsus')->index();
            $table->string('kode_opd')->index();
            $table->string('kode_tema')->index();
            $table->string('kode_program')->index();
            $table->string('kode_keluaran')->index();;
            $table->string('kode_aktifitas')->index();
            $table->string('kode_target_aktifitas')->index();
            $table->string('kode_subkegiatan')->index();
            $table->string('nama_subkegiatan')->index();
            $table->text('indikator_subkegiatan');
            $table->string('satuan_subkegiatan')->index();
            $table->string('klasifikasi_belanja')->index();
            $table->text('text_subkegiatan')->index();
            $table->enum('sumberdana', [
                "PAD",
                "DAU",
                "DBH",
                "DAK Fisik",
                "DAK Non Fisik",
                "Otsus 1%",
                "Otsus 1,25%",
                "DTI",
                "Tambahan DTI Migas",
                "Belanja KL",
                "Lainnya",
                "Tidak Ada",
            ])->nullable()->index();
            $table->enum('penerima_manfaat', [
                'oap',
                'umum',
            ]);
            $table->enum('jenis_layanan', [
                'pendukung',
                'terkait',
            ]);
            $table->enum('jenis_kegiatan', [
                'fisik',
                'nonfisik'
            ])->nullable();
            $table->json('dana_lain');
            $table->json('lokus');
            $table->decimal('vol_subkeg', 16, 2)->nullable();
            $table->decimal('anggaran', 16, 2)->nullable();
            $table->date('mulai')->nullable();
            $table->date('selesai')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('ppsb', ['ya', 'tidak'])->nullable();
            $table->enum('multiyears', ['ya', 'tidak'])->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_kak_name')->nullable();
            $table->string('file_rab_name')->nullable();
            $table->string('file_pendukung1_name')->nullable();
            $table->string('file_pendukung2_name')->nullable();
            $table->string('file_pendukung3_name')->nullable();
            $table->string('link_file_dukung_lain')->nullable();
            $table->text('koordinat')->nullable();
            $table->text('catatan')->nullable();
            $table->year('tahun')->nullable();
            $table->timestamps();
            $table->dropSoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rap_otsuses');
    }
};
