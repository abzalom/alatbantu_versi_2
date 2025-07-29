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
        Schema::create('indikator_urusan_pemdas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bidang')->index();
            $table->string('kode_indikator')->index();
            $table->text('nama_indikator');
            $table->string('satuan')->index();
            // $table->decimal('target_nasional', 16, 2);
            // $table->decimal('target_daerah', 16, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indikator_urusan_pemdas');
    }
};
