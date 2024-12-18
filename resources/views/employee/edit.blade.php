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
                <a href="{{ url('employee') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
                <form method="POST" action="{{ url('/employee/' . $employee->id) }}" class="form-horizontal">
                    @csrf
                    {!! method_field('PUT') !!}
                    <div class="form-group row">
                        <label class="col-2 control-label col-form-label">Nomor Pegawai</label>
                        <div class="col-10">
                            <input type="text" class="form-control" id="employee_number" name="employee_number"
                                value="{{ old('employee_number', $employee->employee_number) }}" required>
                            @error('employee_number')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 control-label col-form-label">Nama Pegawai</label>
                        <div class="col-10">
                            <input type="text" class="form-control" id="employee_name" name="employee_name"
                                value="{{ old('employee_name', $employee->employee_name) }}" required>
                            @error('employee_name')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 control-label col-form-label">Email Pegawai</label>
                        <div class="col-10">
                            <input type="email" class="form-control" id="employee_email" name="employee_email"
                                value="{{ old('employee_email', $employee->employee_email) }}" required>
                            @error('employee_email')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 control-label col-form-label">Posisi Pekerjaan</label>
                        <div class="col-10">
                            <input type="text" class="form-control" id="employee_position" name="employee_position"
                                value="{{ old('employee_position', $employee->employee_position) }}" required>
                            @error('employee_position')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 control-label col-form-label"></label>
                        <div class="col-10">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            <a class="btn btn-sm btn-secondary ml-1" href="{{ url('employee') }}">Kembali</a>
                        </div>
                    </div>
                </form>
            @endempty
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush
