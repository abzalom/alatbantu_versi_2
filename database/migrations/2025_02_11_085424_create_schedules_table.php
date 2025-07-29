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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->enum('tahapan', ['rakortek', 'ranwal', 'rancangan', 'final', 'perubahan']);
            $table->string('keterangan');
            $table->year('tahun');
            $table->timestamp('mulai');
            $table->timestamp('selesai');
            $table->boolean('status')->default(false);
            $table->boolean('penginputan')->default(false);
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
