<?php

namespace Database\Seeders;

use App\Models\Employees;
use App\Models\User;
use App\Models\Mines;
use App\Models\Regions;
use App\Models\VehicleReservations;
use App\Models\Vehicles;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Seeder untuk tabel regions
        $regions = ['Semarang', 'Kalimantan', 'Sulawesi', 'Papua'];
        foreach ($regions as $region) {
            Regions::create([
                'region_name' => $region,
                'address' => $faker->address,
            ]);
        }

        // Seeder untuk tabel mines
        $mines = ['Tambang A', 'Tambang B', 'Tambang C', 'Tambang D', 'Tambang E', 'Tambang F'];
        foreach ($mines as $mine) {
            Mines::create([
                'mine_name' => $mine,
                'address' => $faker->address,
            ]);
        }

        User::updateOrCreate(
            ['role' => 'admin'], // Kondisi unik berdasarkan peran admin
            [
                'username' => 'admin',
                'password' => bcrypt('password'), // Password default
                'role' => 'admin', // Peran admin
                'position' => 'admin', // Posisi admin
                'full_name' => 'Administrator',
                'email' => 'admin@company.com',
                'phone' => '081234567890',
            ]
        );

        // Data untuk approvers
        for ($i = 0; $i < 10; $i++) {
            User::updateOrCreate(
                ['username' => "approver_$i"], // Username unik
                [
                    'password' => bcrypt('password'), // Password default
                    'role' => 'approver', // Peran approver
                    'position' => $faker->randomElement(['approver cabang', 'approver pusat']), // Posisi fiktif
                    'full_name' => $faker->name, // Nama orang random
                    'email' => $faker->unique()->safeEmail, // Email unik
                    'phone' => $faker->phoneNumber, // Nomor telepon fiktif
                ]
            );
        }

        // Seeder untuk tabel vehicles
        $vehicles = ['Kendaraan A', 'Kendaraan B', 'Kendaraan C', 'Kendaraan D', 'Kendaraan E', 'Kendaraan F', 'Kendaraan G', 'Kendaraan H']; // Tambahkan kendaraan untuk lebih banyak variasi
        foreach ($vehicles as $key => $vehicle) {
            // Tentukan tipe kendaraan secara bergantian (angkutan orang atau barang)
            $vehicle_type = $key % 2 == 0 ? 'angkutan orang' : 'angkutan barang';

            Vehicles::create([
                'vehicle_name' => $vehicle,
                'vehicle_type' => $vehicle_type, // Jenis kendaraan
                'vehicle_plate' => $faker->bothify('??-####'), // Contoh: AB-1234
                'vehicle_status' => 'available', // Status kendaraan
                'vehicle_owner' => $faker->randomElement(['company', 'rental']), // Status kepemilikan
            ]);
        }


        for ($i = 1; $i <= 10; $i++) {
            Employees::create([
                'employee_number' => sprintf('EMP%04d', $i),
                'employee_name' => $faker->name,
                'employee_email' => $faker->unique()->safeEmail,
                'employee_position' => $faker->jobTitle,
            ]);
        }

        // Seeder untuk tabel vehicle_reservations
        // Hitung jumlah mines yang tersedia
        $totalMines = Mines::count();
        $totalRegions = Regions::count();

        if ($totalMines > 0 && $totalRegions > 0) {
            for ($i = 1; $i <= 20; $i++) {
                $startDate = $faker->dateTimeBetween('-12 months', '+1 month');
                $endDate = (clone $startDate)->modify('+' . rand(1, 30) . ' days');

                // Pilih approver_1_id dari 'approver cabang'
                $approver1 = User::where('position', 'approver cabang')->inRandomOrder()->first();

                // Pilih approver_2_id dari 'approver pusat'
                $approver2 = User::where('position', 'approver pusat')->inRandomOrder()->first();

                // Pastikan kedua approver ditemukan
                if ($approver1 && $approver2) {
                    VehicleReservations::create([
                        'employee_id' => rand(1, 10),
                        'vehicle_id' => rand(1, 8),
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d'),
                        'status' => 'pending',
                        'return_status' => 'pending',
                        'approver_1_id' => $approver1->id, // Kepala cabang
                        'approver_2_id' => $approver2->id, // Kepala pusat
                        'approver_1_status' => 'pending',
                        'approver_2_status' => 'pending',
                        'mine_id' => rand(1, $totalMines), // Pastikan mine_id valid
                        'region_id' => rand(1, $totalRegions), // Pastikan region_id valid
                    ]);
                }
            }
        }
    }
}