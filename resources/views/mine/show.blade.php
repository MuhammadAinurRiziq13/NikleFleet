@extends('layouts.app')

@section('content')
    <div class="card card-outline card-primary shadow">
        <div class="card-header bg-dark-blue">
            <h6 class="card-title mb-0 text-white">{{ $page->title }}</h6>
        </div>
        <div class="card-body">
            @empty($mine)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data pegawai yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-hover table-sm">
                    <tr>
                        <th>Nama Tambang</th>
                        <td>{{ $mine->mine_name }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pegawai</th>
                        <td>{{ $mine->address }}</td>
                    </tr>
                </table>
            @endempty
            <a href="{{ url('mine') }}" class="btn btn-sm btn-secondary mt-3 float-right">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
@endsection
