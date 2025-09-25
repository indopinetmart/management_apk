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
        Schema::table('table_user_devices', function (Blueprint $table) {
             // tambahan kolom untuk informasi device
            $table->string('platform')->nullable()->after('user_agent');
            $table->string('resolution')->nullable()->after('platform');
            $table->decimal('latitude', 10, 7)->nullable()->after('resolution');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_user_devices', function (Blueprint $table) {
            $table->dropColumn(['platform', 'resolution', 'latitude', 'longitude']);
        });
    }
};
