@extends('layouts.app')

@section('content')
    <div class="card ">
        <div class="card-header bg-dark-blue">
            <div class="card-tools float-right">
                @if (Auth::user()->role == 'admin')
                    <a class="btn btn-sm text-white bg-primary" href="{{ url('employee/create') }}">
                        <i class="fas fa-fw fa-plus"></i> Tambah
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_employee">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Pegawai</th>
                        <th>Nama Pegawai</th>
                        <th>Email </th>
                        <th>Jabatan</th>
                        <th style="width: 14%">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('css')
@endpush
@push('js')
    <script>
        $(document).ready(function() {
            var dataemployee = $('#table_employee').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('employee/list') }}",
                    "dataType": "json",
                    "type": "POST",
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "employee_number",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "employee_name",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "employee_email",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "employee_position",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ],
            });
        });
    </script>
@endpush
