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
        Schema::create('tim_pembahas_opds', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('urutan')->default(0)->index();
            $table->foreignIdFor(Opd::class)->constrained()->cascadeOnDelete();
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->string('nip', 18)->nullable()->index();
            $table->year('tahun')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tim_pembahas_opds');
    }
};
