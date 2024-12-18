@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header bg-dark-blue">
            <div class="card-tools float-right">
                <a class="btn btn-sm btn-secondary ml-1" href="{{ url('reservation') }}">Kembali</a>
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_submission">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pegawai</th>
                        <th>Nama Pegawai</th>
                        <th>Waktu Pengajuan</th>
                        <th>Approver Cabang</th>
                        <th>Approver Pusat</th>
                        <th>Status</th>
                        <th>Aksi</th>
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
            var dataFamily = $('#table_submission').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('submission-reservation/list') }}",
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
                        data: "waktu_pengajuan",
                        className: "",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "approver_1_status",
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
                        data: "approver_2_status",
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
                        data: 'status', // Show status with badge
                        className: '',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
