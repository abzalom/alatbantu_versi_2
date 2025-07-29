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
            $table->softDeletes(); // akan menambahkan kolom deleted_at
            $table->enum('pembahasan', ['setujui', 'perbaikan', 'tolak'])->nullable()->after('alias_dana');
            $table->boolean('validasi')->default(false)->after('pembahasan'); // menambahkan kolom validasi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opd_tag_otsuses', function (Blueprint $table) {
            $table->dropSoftDeletes(); // menghapus kolom deleted_at jika rollback
            $table->dropColumn('pembahasan');
            $table->dropColumn('validasi'); // menghapus kolom validasi jika rollback
        });
    }
};
