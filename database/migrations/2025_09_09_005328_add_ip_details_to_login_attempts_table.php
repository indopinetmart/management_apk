<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('login_attempts', function (Blueprint $table) {
            $table->string('city')->nullable()->after('ip_address');
            $table->string('region')->nullable()->after('city');
            $table->string('country')->nullable()->after('region');
            $table->string('org')->nullable()->after('country');
            $table->string('postal')->nullable()->after('org');
            $table->string('timezone')->nullable()->after('postal');
            $table->string('browser_name_frontend')->nullable()->after('browser');
        });
    }

    public function down(): void
    {
        Schema::table('login_attempts', function (Blueprint $table) {
            $table->dropColumn([
                'city', 'region', 'country', 'org', 'postal', 'timezone', 'browser_name_frontend'
            ]);
        });
    }
};
