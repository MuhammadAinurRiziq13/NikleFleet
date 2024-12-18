@extends('layouts.app')

@section('content')
    <div class="card ">
        <div class="card-header bg-dark-blue">
            <div class="card-tools float-right">
                @if (Auth::user()->role == 'admin')
                    <a class="btn btn-sm text-white bg-primary" href="{{ url('vehicle/create') }}">
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_vehicle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kendaraan</th>
                        <th>Plat Kendaraan</th>
                        <th>Tipe Kendaraan</th>
                        <th>Status Kendaraan</th>
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
            var datavehicle = $('#table_vehicle').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('vehicle/list') }}",
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
                        data: "vehicle_name",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "vehicle_plate",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "vehicle_type",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "vehicle_status",
                        className: '',
                        orderable: true,
                        searchable: true,
                        render: function(data) {
                            var badgeClass = '';
                            var badgeText = '';

                            // Define badge color based on status
                            if (data === 'in_use') {
                                badgeClass = 'badge-warning';
                                badgeText = 'In Use';
                            } else if (data === 'available') {
                                badgeClass = 'badge-success';
                                badgeText = 'Available';
                            }

                            return `<span class="badge ${badgeClass}">${badgeText}</span>`;
                        }
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
