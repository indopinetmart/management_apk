<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk menambahkan kolom foto.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom foto dengan nilai default null
            $table->string('photo')->nullable()->after('email');
        });
    }

    /**
     * Rollback perubahan migration.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom foto jika di-rollback
            $table->dropColumn('photo');
        });
    }
};
