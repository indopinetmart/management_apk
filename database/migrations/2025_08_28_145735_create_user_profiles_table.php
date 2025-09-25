<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nik_karyawan')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('no_tlp', 20)->nullable();
            $table->string('nik_ktp', 20)->unique()->nullable();
            $table->string('foto_ktp')->nullable();
            $table->text('alamat_rumah')->nullable();
            $table->string('lokasi_rumah')->nullable();
            $table->string('kota')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('norek', 50)->nullable();
            $table->string('bank', 100)->nullable();
            $table->string('foto')->nullable();
            $table->string('verifikasi_muka')->nullable();
            $table->string('kontak_darurat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
