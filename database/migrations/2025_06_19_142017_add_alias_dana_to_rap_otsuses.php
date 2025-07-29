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
            $table->enum('alias_dana', ['bg', 'sg', 'dti'])->nullable()->after('sumberdana');
            $table->boolean('kirim')->default(false)->after('catatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rap_otsuses', function (Blueprint $table) {
            $table->dropColumn('alias_dana');
            $table->dropColumn('kirim');
        });
    }
};
