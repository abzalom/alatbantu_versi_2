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
        Schema::create('schedule_monevs', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->index();
            $table->text('keterangan');
            $table->boolean('status')->default(true)->index();
            $table->foreignId('user_create_id')->nullable()->index();
            $table->foreignId('user_update_id')->nullable()->index();
            $table->year('tahun')->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_monevs');
    }
};
