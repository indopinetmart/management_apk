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
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index(); // Foreign key dan index
            $table->string('email')->nullable();               // Email yang digunakan saat mencoba
            $table->string('ip_address');
            $table->boolean('success')->default(false);        // Berhasil login atau tidak
            $table->text('user_agent')->nullable();            // Info browser/device
            $table->string('device')->nullable();              // Device type: mobile, desktop, etc.
            $table->string('browser')->nullable();             // Browser name/version
            $table->decimal('latitude', 10, 7)->nullable();    // Latitude coordinate
            $table->decimal('longitude', 10, 7)->nullable();   // Longitude coordinate
            $table->timestamp('attempted_at')->useCurrent();

            // Definisikan foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            // OnDelete set null supaya jika user dihapus, kolom user_id di sini jadi null, tidak menghapus data login attempt
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('login_attempts', function (Blueprint $table) {
            // Hapus foreign key dulu saat rollback
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('login_attempts');
    }
};
