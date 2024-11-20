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
        Schema::create('dana_alokasi_otsuses', function (Blueprint $table) {
            $table->id();
            $table->year('tahun')->unique();
            $table->decimal('alokasi_sg', 32, 2)->nullable();
            $table->decimal('alokasi_bg', 32, 2)->nullable();
            $table->decimal('alokasi_dti', 32, 2)->nullable();
            $table->enum('status', ['realisasi', 'indikatif', 'perkiraan'])->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dana_alokasi_otsuses');
    }
};
