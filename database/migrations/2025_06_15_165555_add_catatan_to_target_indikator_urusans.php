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
            $table->text('catatan')->nullable()->after('pembahasan');
            $table->boolean('validasi')->default(false)->after('catatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('target_indikator_urusans', function (Blueprint $table) {
            $table->dropColumn('catatan');
            $table->dropColumn('validasi');
        });
    }
};
