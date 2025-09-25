<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            // Informasi device & browser
            $table->string('device_id', 100)->nullable()->after('user_id');
            $table->string('device_type', 50)->nullable()->after('device_id');  // PC / Mobile / Tablet
            $table->string('platform', 100)->nullable()->after('device_type');
            $table->string('browser', 100)->nullable()->after('platform');
            $table->string('os', 100)->nullable()->after('browser');
            $table->string('resolution', 50)->nullable()->after('os');

            // Lokasi & IP tambahan
            $table->decimal('latitude', 10, 7)->nullable()->after('resolution');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('city', 150)->nullable()->after('longitude');
            $table->string('region', 150)->nullable()->after('city');
            $table->string('country', 150)->nullable()->after('region');
            $table->string('org', 255)->nullable()->after('country');
            $table->string('postal', 20)->nullable()->after('org');
            $table->string('timezone', 100)->nullable()->after('postal');

            // Tambahkan kolom files untuk data tambahan
            $table->longText('files')->nullable()->after('payload');
        });
    }

    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn([
                'device_id',
                'device_type',
                'platform',
                'browser',
                'os',
                'resolution',
                'latitude',
                'longitude',
                'city',
                'region',
                'country',
                'org',
                'postal',
                'timezone',
                'files'
            ]);
        });
    }
};
