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
        Schema::create('rakor_otsuses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Opd::class);
            $table->text('keterangan');
            $table->decimal('anggaran', 16, 2);
            $table->decimal('volume', 16, 2);
            $table->string('satuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rakor_otsuses');
    }
};
