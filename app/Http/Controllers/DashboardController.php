<?php

namespace App\Http\Controllers;

use App\Charts\JumlahReservasi;
use App\Charts\PemilikKendaraan;
use App\Charts\PresentaseKendaraan;
use App\Models\Employees;
use App\Models\VehicleReservations;
use App\Models\Vehicles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(JumlahReservasi $chart, PresentaseKendaraan $chart1, PemilikKendaraan $chart2)
    {
        $totalEmployees = Employees::count();
        $totalVehicles = Vehicles::count();
        $totalVehicleAvailable = Vehicles::where('vehicle_status', 'available')->count();
        $totalVehicleResevation = Vehicles::where('vehicle_status', 'in_use')->count();

        $chart = $chart->build();
        $chart1 = $chart1->build();
        $chart2 = $chart2->build();

        $breadcrumb = (object)[
            'title' => 'Selamat Datang, ' . Auth::user()->full_name,
            'list' => ['Home', 'Dashboard']
        ];

        return view(
            'dashboard.index',
            [
                'breadcrumb' => $breadcrumb,
                'totalEmployees' => $totalEmployees,
                'totalVehicles' => $totalVehicles,
                'totalVehicleResevation' => $totalVehicleResevation,
                'totalVehicleAvailable' => $totalVehicleAvailable,
                'chart' => $chart,
                'chart1' => $chart1,
                'chart2' => $chart2,
            ]
        );
    }
}