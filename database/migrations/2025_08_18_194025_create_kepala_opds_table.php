<?php

use App\Enums\JabatanEnum;
use App\Enums\PangkatEnums;
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
        Schema::create('kepala_opds', function (Blueprint $table) {
            $table->string('nip')->primary();  // jadikan primary key
            $table->string('nama');
            $table->enum('pangkat', array_column(PangkatEnums::cases(), 'value'));
            $table->enum('jabatan', array_column(JabatanEnum::cases(), 'value'));
            $table->enum('status_jabatan', ['plt', 'definitif']);
            $table->string('kode_unik_opd');
            $table->year('tahun');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kepala_opds');
    }
};
