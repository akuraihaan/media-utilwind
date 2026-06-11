<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUsersSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Admin Utama',
                'email' => 'admin@gmail.com',
                'old_email' => 'dosen.utama@gmail.com',
                'phone' => '081234560001',
            ],
            [
                'name' => 'Admin Dua',
                'email' => 'admin2@gmail.com',
                'old_email' => 'admin.akademik@gmail.com',
                'phone' => '081234560002',
            ],
        ];

        foreach ($admins as $admin) {
            User::where('email', $admin['old_email'])
                ->where('role', 'admin')
                ->update(['email' => $admin['email']]);

            User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'admin',
                    'class_group' => null,
                    'institution' => 'Universitas Pendidikan Ganesha',
                    'study_program' => 'Pendidikan Teknik Informatika',
                    'phone' => $admin['phone'],
                    'xp' => 0,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
