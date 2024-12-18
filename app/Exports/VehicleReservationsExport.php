<?php

namespace App\Exports;

use App\Models\VehicleReservations;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VehicleReservationsExport implements FromQuery, WithHeadings
{
    /**
     * Query data yang akan diexport.
     */
    public function query()
    {
        return VehicleReservations::query()
            ->select(
                'vehicle_reservations.id',
                'employees.employee_number',
                'employees.employee_name',
                'vehicle_reservations.start_date',
                'vehicle_reservations.end_date',
                'vehicle_reservations.status',
                'vehicle_reservations.return_status',
                'mines.mine_name',
                'regions.region_name',
                'vehicles.vehicle_name',
                'vehicles.vehicle_plate',
                'approver1.full_name as approver_1_name', // Approver 1 Name
                'vehicle_reservations.approver_1_status', // Approver 1 Status
                'approver2.full_name as approver_2_name', // Approver 2 Name
                'vehicle_reservations.approver_2_status'  // Approver 2 Status
            )
            ->join('employees', 'vehicle_reservations.employee_id', '=', 'employees.id')
            ->join('mines', 'vehicle_reservations.mine_id', '=', 'mines.id')
            ->join('regions', 'vehicle_reservations.region_id', '=', 'regions.id')
            ->join('vehicles', 'vehicle_reservations.vehicle_id', '=', 'vehicles.id')
            ->join('users as approver1', 'vehicle_reservations.approver_1_id', '=', 'approver1.id') // Join untuk approver 1
            ->join('users as approver2', 'vehicle_reservations.approver_2_id', '=', 'approver2.id'); // Join untuk approver 2
    }

    /**
     * Tambahkan header untuk file Excel.
     */
    public function headings(): array
    {
        return [
            'ID Reservasi',
            'Nomor Pegawai',
            'Nama Pegawai',
            'Tanggal Mulai',
            'Tanggal Akhir',
            'Status Reservasi',
            'Status Pengembalian',
            'Nama Tambang',
            'Nama Region',
            'Nama Kendaraan',
            'Plat Kendaraan',
            'Nama Approver Cabang ',
            'Status Approver Cabang',
            'Nama Approver Pusat ',
            'Status Approver Pusat',
        ];
    }
}