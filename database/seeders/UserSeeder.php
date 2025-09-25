<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed users table dengan user default untuk setiap role.
     */
    public function run(): void
    {
        // Ambil semua role dari tabel roles (key = name, value = id)
        $roles = DB::table('roles')->pluck('id', 'name');

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('Password1234567890,'),
                'role_id' => $roles['superadmin'] ?? null,
                'status' => 'aktif',
            ],
            [
                'name' => 'Komisaris Utama',
                'email' => 'komisaris@example.com',
                'password' => Hash::make('Password1234567890,'),
                'role_id' => $roles['komisaris'] ?? null,
                'status' => 'aktif',
            ],
            [
                'name' => 'Direktur Utama',
                'email' => 'dirut@example.com',
                'password' => Hash::make('Password1234567890,'),
                'role_id' => $roles['dirut'] ?? null,
                'status' => 'aktif',
            ],
            [
                'name' => 'Direktur Keuangan',
                'email' => 'dirkeu@example.com',
                'password' => Hash::make('Password1234567890,'),
                'role_id' => $roles['dirkeu'] ?? null,
                'status' => 'aktif',
            ],
            [
                'name' => 'Accounting Staff',
                'email' => 'accounting@example.com',
                'password' => Hash::make('Password1234567890,'),
                'role_id' => $roles['accounting'] ?? null,
                'status' => 'aktif',
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@example.com',
                'password' => Hash::make('Password1234567890,'),
                'role_id' => $roles['manager'] ?? null,
                'status' => 'aktif',
            ],
            [
                'name' => 'Supervisor',
                'email' => 'supervisor@example.com',
                'password' => Hash::make('Password1234567890,'),
                'role_id' => $roles['supervisor'] ?? null,
                'status' => 'aktif',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('Password1234567890,'),
                'role_id' => $roles['admin'] ?? null,
                'status' => 'aktif',
            ],
            [
                'name' => 'Gudang',
                'email' => 'gudang@example.com',
                'password' => Hash::make('Password1234567890,'),
                'role_id' => $roles['gudang'] ?? null,
                'status' => 'aktif',
            ],
            [
                'name' => 'Mitra',
                'email' => 'mitra@example.com',
                'password' => Hash::make('Password1234567890,'),
                'role_id' => $roles['mitra'] ?? null,
                'status' => 'aktif',
            ],
            [
                'name' => 'Referal',
                'email' => 'referal@example.com',
                'password' => Hash::make('Password1234567890,'),
                'role_id' => $roles['referal'] ?? null,
                'status' => 'aktif',
            ],
        ];

        foreach ($users as $user) {
            if ($user['role_id']) {
                DB::table('users')->updateOrInsert(
                    ['email' => $user['email']], // kunci unik
                    [
                        'name' => $user['name'],
                        'password' => $user['password'],
                        'role_id' => $user['role_id'],
                        'status' => $user['status'],
                        'email_verified_at' => now(),
                        'remember_token' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
