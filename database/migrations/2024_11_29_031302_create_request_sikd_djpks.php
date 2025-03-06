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
        Schema::create('request_sikd_djpks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->year('tahun');
            $table->string('url');
            $table->text('param_key');
            $table->text('param_value');
            $table->enum('method', ['get', 'post']);
            $table->enum('jenis', ['nomenklatur', 'rap']);
            $table->enum('sumberdana', ['bg', 'sg', 'dti']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_sikd_djpks');
    }
};
