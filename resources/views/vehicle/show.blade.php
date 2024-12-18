@extends('layouts.app')

@section('content')
    <div class="card card-outline card-primary shadow">
        <div class="card-header bg-dark-blue">
            <h6 class="card-title mb-0 text-white">{{ $page->title }}</h6>
        </div>
        <div class="card-body">
            @empty($vehicle)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data kendaraan yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-hover table-sm">
                    <tr>
                        <th>Nama Kendaraan</th>
                        <td>{{ $vehicle->vehicle_name }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kendaraan</th>
                        <td>{{ ucfirst($vehicle->vehicle_type) }}</td>
                    </tr>
                    <tr>
                        <th>Plat Kendaraan</th>
                        <td>{{ $vehicle->vehicle_plate }}</td>
                    </tr>
                    <tr>
                        <th>Status Kendaraan</th>
                        <td>
                            <span
                                class="badge {{ $vehicle->vehicle_status == 'available' ? 'badge-success' : 'badge-warning' }}">
                                {{ ucfirst($vehicle->vehicle_status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Pemilik Kendaraan</th>
                        <td>{{ ucfirst($vehicle->vehicle_owner) }}</td>
                    </tr>
                </table>
            @endempty
            <a href="{{ url('vehicle') }}" class="btn btn-sm btn-secondary mt-3 float-right">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .badge {
            font-size: 0.9em;
            padding: 5px 10px;
        }

        .table th {
            width: 40%;
        }

        .card-body {
            padding: 20px;
        }
    </style>
@endpush
