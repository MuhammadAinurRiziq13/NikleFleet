<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\VehicleReservations;
use App\Models\Vehicles;

class PresentaseKendaraan
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\DonutChart
    {
        // Calculate the data
        $totalVehicleResevation = Vehicles::where('vehicle_status', 'in_use')->count();
        $totalVehicleAvailable = Vehicles::where('vehicle_status', 'available')->count();

        $data = [$totalVehicleResevation, $totalVehicleAvailable];
        $labels = ['Company', 'Rental'];


        return $this->chart->donutChart()
            ->setTitle('Status Kendaraan')
            ->addData($data)
            ->setHeight(180)
            ->setLabels(['Terpakai', 'Tersedia']);
    }
}