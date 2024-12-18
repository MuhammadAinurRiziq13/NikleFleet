@extends('layouts.app')

@section('content')
    <div class="card ">
        <div class="card-header bg-dark-blue">
            <div class="card-tools float-right">
                @if (Auth::user()->role == 'admin')
                    <a class="btn btn-sm text-white bg-primary" href="{{ url('mine/create') }}">
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_mine">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Tambang</th>
                        <th>Alamat </th>
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
            var datamine = $('#table_mine').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('mine/list') }}",
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
                        data: "mine_name",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "address",
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
