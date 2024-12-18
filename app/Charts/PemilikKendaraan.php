<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Vehicles;

class PemilikKendaraan
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\DonutChart
    {
        // Count the number of vehicles by owner type (company and rental)
        $companyCount = Vehicles::where('vehicle_owner', 'company')->count();
        $rentalCount = Vehicles::where('vehicle_owner', 'rental')->count();

        // Prepare data for the donut chart
        $data = [$companyCount, $rentalCount];
        $labels = ['Company', 'Rental'];

        return $this->chart->donutChart()
            ->setTitle('Distribusi Pemilik Kendaraan')
            ->addData($data)
            ->setHeight(180)
            ->setColors(['#4ccdfe', '#ff6384'])
            ->setLabels($labels);
    }
}