@extends('layouts.app')

@section('content')
    <div class="card card-outline card-primary shadow">
        <div class="card-header bg-dark-blue">
            <h6 class="card-title mb-0 text-white">{{ $page->title }}</h6>
        </div>
        <div class="card-body">
            @empty($employee)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data pegawai yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-hover table-sm">
                    <tr>
                        <th>Nomor Pegawai</th>
                        <td>{{ $employee->employee_number }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pegawai</th>
                        <td>{{ $employee->employee_name }}</td>
                    </tr>
                    <tr>
                        <th>Email Pegawai</th>
                        <td>{{ $employee->employee_email }}</td>
                    </tr>
                    <tr>
                        <th>Posisi Pekerjaan</th>
                        <td>{{ $employee->employee_position }}</td>
                    </tr>
                </table>
            @endempty
            <a href="{{ url('employee') }}" class="btn btn-sm btn-secondary mt-3 float-right">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
@endsection
