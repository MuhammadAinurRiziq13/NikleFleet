@extends('layouts.app')

@section('content')
    <div class="card card-outline card-primary shadow">
        <div class="card-header bg-dark-blue">
            <h6 class="card-title mb-0 text-white">{{ $page->title }}</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('employee') }}" class="form-horizontal">
                @csrf
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Nomor Pegawai</label>
                    <div class="col-10">
                        <input type="text" class="form-control" id="employee_number" name="employee_number"
                            value="{{ old('employee_number') }}" required>
                        @error('employee_number')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Nama Pegawai</label>
                    <div class="col-10">
                        <input type="text" class="form-control" id="employee_name" name="employee_name"
                            value="{{ old('employee_name') }}" required>
                        @error('employee_name')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Email Pegawai</label>
                    <div class="col-10">
                        <input type="email" class="form-control" id="employee_email" name="employee_email"
                            value="{{ old('employee_email') }}" required>
                        @error('employee_email')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Posisi Pekerjaan</label>
                    <div class="col-10">
                        <input type="text" class="form-control" id="employee_position" name="employee_position"
                            value="{{ old('employee_position') }}" required>
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
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush
