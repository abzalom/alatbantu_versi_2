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
        Schema::create('b1_tema_otsuses', function (Blueprint $table) {
            $table->id();
            // $table->string('kode_unik_tema')->unique();

            $table->string('kode_tema')->unique();
            $table->text('uraian');
            $table->year('tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('b1_tema_otsuses');
    }
};
