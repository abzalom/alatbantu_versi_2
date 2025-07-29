<?php

use App\Models\Data\Opd;
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
        Schema::create('pagu_opds', function (Blueprint $table) {
            $table->id();
            // $table->string('kode_unik_pagu')->unique(); // kode_unik_opd-sumberdana-tahun
            $table->string('kode_unik_opd')->unique();
            // $table->enum('sumberdana', ['sg', 'bg', 'dti'])->index();
            $table->decimal('sg', 16, 2)->nullable();
            $table->decimal('bg', 16, 2)->nullable();
            $table->decimal('dti', 16, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->year('tahun')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagu_opds');
    }
};
