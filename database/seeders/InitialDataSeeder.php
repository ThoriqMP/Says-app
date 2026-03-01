<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\ProfilSekolah;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin Sekolah',
            'email' => 'admin@sayyidah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create pimpinan user
        User::create([
            'name' => 'Pimpinan Sekolah',
            'email' => 'pimpinan@sayyidah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'pimpinan',
        ]);

        // Create school profile
        ProfilSekolah::create([
            'nama_sekolah' => 'Sayyidah School',
            'alamat' => 'Jl. Pendidikan No. 123, Jakarta Selatan',
            'bank_nama' => 'Bank Mandiri',
            'no_rekening' => '1234567890',
            'atas_nama' => 'Yayasan Sayyidah School',
            'pimpinan_nama' => 'Dr. Siti Rahayu, M.Pd',
        ]);

        // Create sample students with accounts
        $students = [
            [
                'nama_siswa' => 'Ahmad Fauzan',
                'nama_orang_tua' => 'Bapak Fauzan',
                'alamat_tagihan' => 'Jl. Merdeka No. 45, Jakarta',
                'email' => 'fauzan@student.com',
                'nis' => '10001',
                'class' => '7A'
            ],
            [
                'nama_siswa' => 'Fatimah Azzahra',
                'nama_orang_tua' => 'Ibu Siti Nurhalimah',
                'alamat_tagihan' => 'Jl. Sudirman No. 78, Jakarta',
                'email' => 'fatimah@student.com',
                'nis' => '10002',
                'class' => '7B'
            ],
            [
                'nama_siswa' => 'Muhammad Rizki',
                'nama_orang_tua' => 'Bapak Rizki Pratama',
                'alamat_tagihan' => 'Jl. Thamrin No. 23, Jakarta',
                'email' => 'rizki@student.com',
                'nis' => '10003',
                'class' => '8A'
            ],
            [
                'nama_siswa' => 'Sarah Maulidia',
                'nama_orang_tua' => 'Ibu Sarah Dewi',
                'alamat_tagihan' => 'Jl. Gatot Subroto No. 56, Jakarta',
                'email' => 'sarah@student.com',
                'nis' => '10004',
                'class' => '8B'
            ],
            [
                'nama_siswa' => 'Ali bin Abi Thalib',
                'nama_orang_tua' => 'Bapak Ali Hasan',
                'alamat_tagihan' => 'Jl. Rasuna Said No. 89, Jakarta',
                'email' => 'ali@student.com',
                'nis' => '10005',
                'class' => '9A'
            ],
        ];

        foreach ($students as $data) {
            $user = User::create([
                'name' => $data['nama_siswa'],
                'email' => $data['email'],
                'password' => Hash::make('password123'),
                'role' => 'student',
            ]);

            Siswa::create([
                'nama_siswa' => $data['nama_siswa'],
                'nama_orang_tua' => $data['nama_orang_tua'],
                'alamat_tagihan' => $data['alamat_tagihan'],
                'user_id' => $user->id,
                'nis' => $data['nis'],
                'class' => $data['class'],
            ]);
        }

        // Create sample services
        $services = [
            ['nama_layanan' => 'SPP Bulanan', 'harga_standar' => 1500000],
            ['nama_layanan' => 'Kelas Terapi', 'harga_standar' => 500000],
            ['nama_layanan' => 'Ekstrakurikuler', 'harga_standar' => 300000],
            ['nama_layanan' => 'Buku Paket', 'harga_standar' => 750000],
            ['nama_layanan' => 'Seragam Sekolah', 'harga_standar' => 450000],
            ['nama_layanan' => 'Kegiatan Praktikum', 'harga_standar' => 200000],
            ['nama_layanan' => 'Study Tour', 'harga_standar' => 1200000],
            ['nama_layanan' => 'Ulangan Kenaikan Kelas', 'harga_standar' => 100000],
        ];

        foreach ($services as $service) {
            Layanan::create($service);
        }
    }
}
