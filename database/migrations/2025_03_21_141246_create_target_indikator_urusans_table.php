<?php

use App\Models\Nomenklatur\A2Bidang;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Data\Perencanaan\IndikatorUrusanPemda;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('target_indikator_urusans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(IndikatorUrusanPemda::class);
            $table->foreignIdFor(A2Bidang::class);
            $table->string('kode_bidang')->index();
            $table->string('kode_indikator')->index();
            $table->decimal('target_nasional', 16, 2)->nullable()->index();
            $table->decimal('usulan_target_daerah', 16, 2)->nullable()->index();
            $table->decimal('target_daerah', 16, 2)->nullable()->index();
            $table->enum('pembahasan', ['setujui', 'perbaikan', 'tolak'])->nullable();
            $table->text('catatan')->nullable();
            $table->string('satuan')->index();
            $table->year('tahun')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_indikator_urusans');
    }
};
