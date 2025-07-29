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
        Schema::table('opd_tag_otsuses', function (Blueprint $table) {
            $table->enum('alias_dana', ['bg', 'sg', 'dti'])->index()->after('sumberdana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opd_tag_otsuses', function (Blueprint $table) {
            //
        });
    }
};
