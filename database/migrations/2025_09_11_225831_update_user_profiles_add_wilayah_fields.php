<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            // Ubah field lama
            if (Schema::hasColumn('user_profiles', 'kabupaten')) {
                $table->renameColumn('kabupaten', 'province_name'); // kabupaten → province_name
            }
            if (Schema::hasColumn('user_profiles', 'kota')) {
                $table->renameColumn('kota', 'city_name');          // kota → city_name
            }

            // Tambah field baru
            if (!Schema::hasColumn('user_profiles', 'district_name')) {
                $table->string('district_name')->nullable()->after('city_name');
            }
            if (!Schema::hasColumn('user_profiles', 'village_name')) {
                $table->string('village_name')->nullable()->after('district_name');
            }
            if (!Schema::hasColumn('user_profiles', 'kodepos')) {
                $table->string('kodepos', 10)->nullable()->after('village_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            // Balikin ke struktur lama
            if (Schema::hasColumn('user_profiles', 'province_name')) {
                $table->renameColumn('province_name', 'kabupaten');
            }
            if (Schema::hasColumn('user_profiles', 'city_name')) {
                $table->renameColumn('city_name', 'kota');
            }

            if (Schema::hasColumn('user_profiles', 'district_name')) {
                $table->dropColumn('district_name');
            }
            if (Schema::hasColumn('user_profiles', 'village_name')) {
                $table->dropColumn('village_name');
            }
            if (Schema::hasColumn('user_profiles', 'kodepos')) {
                $table->dropColumn('kodepos');
            }
        });
    }
};
