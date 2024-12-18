@extends('layouts.app')

@section('content')
    <div class="card shadow">
        <div class="card-header bg-dark-blue">
            <div class="card-tools float-right">
                @if (Auth::user()->role == 'approver')
                    <a class="btn btn-sm text-white bg-primary" href="{{ url('submission-reservation') }}">Daftar
                        Pengajuan</a>
                @endif
                @if (Auth::user()->role == 'admin')
                    <a class="btn btn-sm text-white bg-primary" href="{{ url('reservation/create') }}"><i
                            class="fas fa-fw fa-plus"></i> Tambah</a>
                @endif
                <a class="btn btn-sm text-white bg-primary" href="{{ url('reservation/export') }}">
                    <i class="fas fa-regular fa-file-excel"></i> Export
                </a>
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
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="status" class="col-1 control-label col-form-label">Filter Status:</label>
                        <div class="col-3">
                            <select name="status" id="filter_status" class="form-control">
                                <option value="">- Semua Status -</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            <small class="form-text text-muted">Status Reservasi</small>
                        </div>
                        <label for="return_status" class="col-1 control-label col-form-label">Filter Return:</label>
                        <div class="col-3">
                            <select name="return_status" id="filter_return_status" class="form-control">
                                <option value="">- Semua -</option>
                                <option value="pending">Pending</option>
                                <option value="returned">Returned</option>
                            </select>
                            <small class="form-text text-muted">Status Pengembalian</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_reservation">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pegawai</th>
                        <th>Nama Pegawai</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Akhir</th>
                        <th>Status</th>
                        <th>Pengembalian</th>
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
            var dataFamily = $('#table_reservation').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('reservation/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.status = $('#filter_status').val();
                        d.return_status = $('#filter_return_status').val();
                    }
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
                        data: "start_date",
                        className: "",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "end_date",
                        className: "",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "status",
                        className: '',
                        orderable: true,
                        searchable: true,
                        render: function(data) {
                            var badgeClass = '';
                            var badgeText = '';

                            // Define badge color based on status
                            if (data === 'pending') {
                                badgeClass = 'badge-warning';
                                badgeText = 'Pending';
                            } else if (data === 'approved') {
                                badgeClass = 'badge-success';
                                badgeText = 'Approved';
                            } else if (data === 'rejected') {
                                badgeClass = 'badge-danger';
                                badgeText = 'Rejected';
                            }

                            return `<span class="badge ${badgeClass}">${badgeText}</span>`;
                        }
                    },
                    {
                        data: "return_status",
                        className: '',
                        orderable: true,
                        searchable: true,
                        render: function(data) {
                            var badgeClass = '';
                            var badgeText = '';

                            // Define badge color based on return status
                            if (data === 'pending') {
                                badgeClass = 'badge-warning';
                                badgeText = 'pending';
                            } else if (data === 'returned') {
                                badgeClass = 'badge-success';
                                badgeText = 'Returned';
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
                ]
            });

            // Event listener untuk filter
            $('#filter_status, #filter_return_status').change(function() {
                dataFamily.ajax.reload(); // Reload tabel dengan data filter
            });
        });
    </script>
@endpush
