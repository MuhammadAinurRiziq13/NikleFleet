@extends('layouts.app')

@section('content')
    <div class="row">
        <!-- Total Employees -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Jumlah Karyawan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEmployees }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Vehicles -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Jumlah Kendaraan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalVehicles }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Vehicle Reservations (Not Returned) -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Kendaraan Terpakai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalVehicleResevation }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Available Vehicles -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Kendaraan Tersedia</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalVehicleAvailable }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car-side fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-8">
            <div class="card shadow mb-4">
                <!-- Card Body -->
                <div class="card-body" style="height: 450px;">
                    {!! $chart->container() !!}
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-4">
            <div class="card shadow mb-4" style="height: 210px;">
                <!-- Card Body -->
                <div class="card-body">
                    {!! $chart1->container() !!}
                </div>
            </div>

            <div class="card shadow mb-4" style="height: 210px;">
                <!-- Card Body -->
                <div class="card-body">
                    {!! $chart2->container() !!}
                </div>
            </div>
        </div>
    </div>

    @if ($message = Session::get('LoginBerhasil'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "Login Telah Berhasil.."
                });
            });
        </script>
    @endif
@endsection

@push('js')
    <script src="{{ $chart->cdn() }}"></script>
    <script src="{{ $chart1->cdn() }}"></script>
    <script src="{{ $chart2->cdn() }}"></script>

    {{ $chart->script() }}
    {{ $chart1->script() }}
    {{ $chart2->script() }}
@endpush
