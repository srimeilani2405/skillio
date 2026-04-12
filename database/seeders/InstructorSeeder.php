<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instructor;
use Illuminate\Support\Facades\DB;

class InstructorSeeder extends Seeder
{
    public function run(): void
    {
        $instructors = [
            [
                'email' => 'andi.pratama@gmail.com',
                'nama' => 'Andi Pratama',
                'no_telp' => '081234567890',
                'spesialisasi' => 'Matematika SMA',
                'status' => 'aktif'
            ],
            [
                'email' => 'siti.rahma@gmail.com',
                'nama' => 'Siti Rahmawati',
                'no_telp' => '082345678901',
                'spesialisasi' => 'Bahasa Indonesia',
                'status' => 'aktif'
            ],
            [
                'email' => 'budi.santoso@gmail.com',
                'nama' => 'Budi Santoso',
                'no_telp' => '083456789012',
                'spesialisasi' => 'Bahasa Inggris',
                'status' => 'aktif'
            ],
            [
                'email' => 'rina.kartika@gmail.com',
                'nama' => 'Rina Kartika',
                'no_telp' => '084567890123',
                'spesialisasi' => 'Fisika & Kimia',
                'status' => 'aktif'
            ],
            [
                'email' => 'dimas.saputra@gmail.com',
                'nama' => 'Dimas Saputra',
                'no_telp' => '085678901234',
                'spesialisasi' => 'Web Development',
                'status' => 'aktif'
            ],
            [
                'email' => 'laila.nur@gmail.com',
                'nama' => 'Laila Nurhaliza',
                'no_telp' => '086789012345',
                'spesialisasi' => 'UI/UX Design',
                'status' => 'nonaktif'
            ],
            [
                'email' => 'farhan.maulana@gmail.com',
                'nama' => 'Farhan Maulana',
                'no_telp' => '087890123456',
                'spesialisasi' => 'Data Science',
                'status' => 'aktif'
            ],
        ];

        foreach ($instructors as $instructor) {
            Instructor::updateOrCreate(
                ['email' => $instructor['email']], // Check if exists by email
                $instructor // Update or create with this data
            );
        }
    }
}