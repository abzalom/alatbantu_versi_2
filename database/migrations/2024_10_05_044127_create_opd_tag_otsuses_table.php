<?php

use App\Models\Data\Sumberdana;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('opd_tag_otsuses', function (Blueprint $table) {
            $table->id();
            $table->string('kode_unik_opd')->index();
            $table->string('kode_unik_opd_tag_otsus')->unique();
            $table->string('kode_opd')->index();
            $table->string('kode_tema')->index();
            $table->string('kode_program')->index();
            $table->string('kode_keluaran')->index();;
            $table->string('kode_aktifitas')->index();
            $table->string('kode_target_aktifitas')->index();
            $table->string('kode_indikator')->nullable()->index();
            $table->string('kode_unik_indikator')->nullable()->index();
            $table->string('satuan')->nullable();
            // Usulan user
            $table->decimal('volume_usulan', 32, 2)->nullable();
            $table->enum('sumberdana_usulan', [
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
            ])->index()->nullable();
            $table->enum('alias_dana_usulan', ['bg', 'sg', 'dti'])->index()->nullable();

            // hasil pembahasan
            $table->decimal('volume', 32, 2)->nullable();
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
            ])->index()->nullable();
            $table->enum('alias_dana', ['bg', 'sg', 'dti'])->nullable()->index();
            $table->enum('pembahasan', ['setujui', 'perbaikan', 'tolak'])->nullable();
            $table->boolean('validasi')->default(false); // menambahkan kolom validasi
            $table->text('catatan')->nullable();
            $table->year('tahun');
            $table->softDeletes(); // akan menambahkan kolom deleted_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_tag_otsuses');
    }
};
