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
        Schema::table('rap_otsuses', function (Blueprint $table) {
            $table->enum('pembahasan', ['setujui', 'perbaiki', 'tolak'])
                ->index()
                ->nullable()
                ->after('koordinat');
            $table->boolean('validasi')
                ->index()
                ->default(false)
                ->after('pembahasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rap_otsuses', function (Blueprint $table) {
            $table->dropColumn('pembahasan');
            $table->dropColumn('validasi');
        });
    }
};
