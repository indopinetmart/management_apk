<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Seed roles table dengan daftar role tetap.
     */
    public function run(): void
    {
        $roles = [
            'superadmin',
            'komisaris',
            'dirut',
            'dirkeu',
            'accounting',
            'manager',
            'supervisor',
            'admin',
            'gudang',
            'mitra',
            'referal',
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
