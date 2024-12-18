@extends('layouts.app')

@section('content')
    <div class="card card-outline card-primary shadow">
        <div class="card-header bg-dark-blue">
            <h6 class="card-title mb-0 text-white">{{ $page->title }}</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('vehicle') }}" class="form-horizontal">
                @csrf
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Nama Kendaraan</label>
                    <div class="col-10">
                        <input type="text" class="form-control" id="vehicle_name" name="vehicle_name"
                            value="{{ old('vehicle_name') }}" required>
                        @error('vehicle_name')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Jenis Kendaraan</label>
                    <div class="col-10">
                        <select class="form-control" id="vehicle_type" name="vehicle_type" required>
                            <option value="">Pilih Jenis Kendaraan</option>
                            <option value="angkutan orang" {{ old('vehicle_type') == 'angkutan orang' ? 'selected' : '' }}>
                                Angkutan Orang</option>
                            <option value="angkutan barang"
                                {{ old('vehicle_type') == 'angkutan barang' ? 'selected' : '' }}>Angkutan Barang</option>
                        </select>
                        @error('vehicle_type')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Plat Kendaraan</label>
                    <div class="col-10">
                        <input type="text" class="form-control" id="vehicle_plate" name="vehicle_plate"
                            value="{{ old('vehicle_plate') }}" required>
                        @error('vehicle_plate')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Pemilik Kendaraan</label>
                    <div class="col-10">
                        <select class="form-control" id="vehicle_owner" name="vehicle_owner" required>
                            <option value="">Pilih Pemilik Kendaraan</option>
                            <option value="company" {{ old('vehicle_owner') == 'company' ? 'selected' : '' }}>Company
                            </option>
                            <option value="rental" {{ old('vehicle_owner') == 'rental' ? 'selected' : '' }}>Rental</option>
                        </select>
                        @error('vehicle_owner')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label"></label>
                    <div class="col-10">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a class="btn btn-sm btn-secondary ml-1" href="{{ url('vehicle') }}">Kembali</a>
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
