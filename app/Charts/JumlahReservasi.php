<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\VehicleReservations;
use Carbon\Carbon;

class JumlahReservasi
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        // Get the count of reservations grouped by month for the current year
        $reservationsPerMonth = VehicleReservations::selectRaw('MONTH(start_date) as month, COUNT(*) as count')
            ->whereYear('start_date', Carbon::now()->year)  // Get reservations for the current year
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Prepare data for the chart
        $months = [];
        $counts = [];

        // Add months and counts to arrays
        foreach ($reservationsPerMonth as $reservation) {
            $months[] = Carbon::create()->month($reservation->month)->format('F');  // Month name (e.g. January)
            $counts[] = $reservation->count;
        }

        return $this->chart->barChart()
            ->setTitle('Jumlah Reservasi Kendaraan')
            ->addData('Jumlah Reservasi', $counts)
            ->setHeight(430)
            ->setXAxis($months);
    }
}