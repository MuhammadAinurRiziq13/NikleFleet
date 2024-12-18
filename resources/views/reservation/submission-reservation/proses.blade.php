@extends('layouts.app')

@section('content')
    <div class="card card-outline card-primary shadow">
        <div class="card-header bg-dark-blue">
            <h6 class="card-title mb-0 text-white">{{ $page->title }}</h6>
        </div>
        <div class="card-body">
            @empty($reservation)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <!-- Data Pegawai dan Kendaraan -->
                <h5 class="mb-3"><strong>Data Pegawai dan Kendaraan</strong></h5>
                <table class="table table-bordered table-hover table-sm mb-4">
                    <tr>
                        <th>Nama Pegawai</th>
                        <td>{{ $reservation->employee->employee_name }}</td>
                    </tr>
                    <tr>
                        <th>No Pegawai</th>
                        <td>{{ $reservation->employee->employee_number }}</td>
                    </tr>
                    <tr>
                        <th>Region</th>
                        <td>{{ $reservation->region->region_name }}</td>
                    </tr>
                    <tr>
                        <th>Nama Tambang</th>
                        <td>{{ $reservation->mine->mine_name }}</td>
                    </tr>
                    <tr>
                        <th>Nama Kendaraan</th>
                        <td>{{ $reservation->vehicle->vehicle_name }}</td>
                    </tr>
                    <tr>
                        <th>Plat Kendaraan</th>
                        <td>{{ $reservation->vehicle->vehicle_plate }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Mulai</th>
                        <td>{{ date('d-m-Y', strtotime($reservation->start_date)) }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Selesai</th>
                        <td>{{ date('d-m-Y', strtotime($reservation->end_date)) }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span
                                class="badge 
                                @if ($reservation->status == 'pending') badge-warning
                                @elseif($reservation->status == 'approved') badge-success
                                @elseif($reservation->status == 'rejected') badge-danger @endif">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Status Pengembalian</th>
                        <td>
                            <span
                                class="badge 
                                @if ($reservation->return_status == 'pending') badge-warning
                                @elseif($reservation->return_status == 'returned') badge-success @endif">
                                {{ ucfirst($reservation->return_status) }}
                            </span>
                        </td>
                    </tr>
                </table>

                <!-- Data Approver -->
                <h5 class="mb-3"><strong>Data Approver</strong></h5>
                <table class="table table-bordered table-hover table-sm">
                    <tr>
                        <th>Nama Approver Cabang</th>
                        <td>{{ $reservation->approver1->full_name }}</td>
                    </tr>
                    <tr>
                        <th>Status Approver Cabang</th>
                        <td>
                            <span
                                class="badge 
                                @if ($reservation->approver_1_status == 'pending') badge-warning
                                @elseif($reservation->approver_1_status == 'approved') badge-success
                                @elseif($reservation->approver_1_status == 'rejected') badge-danger @endif">
                                {{ ucfirst($reservation->approver_1_status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Nama Approver Pusat</th>
                        <td>{{ $reservation->approver2->full_name }}</td>
                    </tr>
                    <tr>
                        <th>Status Approver Pusat</th>
                        <td>
                            <span
                                class="badge 
                                @if ($reservation->approver_2_status == 'pending') badge-warning
                                @elseif($reservation->approver_2_status == 'approved') badge-success
                                @elseif($reservation->approver_2_status == 'rejected') badge-danger @endif">
                                {{ ucfirst($reservation->approver_2_status) }}
                            </span>
                        </td>
                    </tr>
                </table>

                <!-- Tombol Aksi -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <a href="{{ url('submission-reservation') }}" class="btn btn-secondary btn-sm w-100">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <div class="col-md-4">
                        <form method="POST" action="{{ url('/submission-reservation/' . $reservation->id) }}">
                            @csrf
                            {!! method_field('PUT') !!}
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success btn-sm w-100">
                                <i class="fas fa-check-circle"></i> Terima
                            </button>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <form method="POST" action="{{ url('/submission-reservation/' . $reservation->id) }}">
                            @csrf
                            {!! method_field('PUT') !!}
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                <i class="fas fa-times-circle"></i> Tolak
                            </button>
                        </form>
                    </div>
                </div>
            @endempty
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
