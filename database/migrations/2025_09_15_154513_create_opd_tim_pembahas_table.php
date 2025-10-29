<?php

use App\Models\Config\TimPembahas;
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
        Schema::create('opd_tim_pembahas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('urutan')->default(0)->index();
            $table->foreignIdFor(TimPembahas::class);
            $table->foreignIdFor(Opd::class);
            $table->year('tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_tim_pembahas');
    }
};
