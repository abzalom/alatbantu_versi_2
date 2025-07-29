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
        Schema::table('target_indikator_urusans', function (Blueprint $table) {
            $table->enum('pembahasan', ['setujui', 'perbaikan', 'tolak'])
                ->nullable()
                ->after('satuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('target_indikator_urusans', function (Blueprint $table) {
            $table->dropColumn('pembahasan');
        });
    }
};
