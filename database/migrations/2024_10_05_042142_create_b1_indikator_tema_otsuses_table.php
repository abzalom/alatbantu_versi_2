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
        Schema::create('b1_indikator_tema_otsuses', function (Blueprint $table) {
            $table->id();
            // $table->string('kode_unik_tema')->index();
            $table->string('kode_tema')->index();

            $table->text('uraian');
            $table->string('satuan');
            $table->decimal('kondisi_awal', 32, 2);
            $table->decimal('target_impact', 32, 2);
            $table->year('tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('b1_indikator_tema_otsuses');
    }
};
