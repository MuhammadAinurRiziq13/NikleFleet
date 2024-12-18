@extends('layouts.app')

@section('content')
    <div class="card card-outline card-primary shadow">
        <div class="card-header bg-dark-blue">
            <h6 class="card-title mb-0 text-white">{{ $page->title }}</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('region/' . $region->id) }}" class="form-horizontal">
                @csrf
                {!! method_field('PUT') !!}
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Nama Tambang</label>
                    <div class="col-10">
                        <input type="text" class="form-control" id="region_name" name="region_name"
                            value="{{ old('region_name', $region->region_name) }}" required>
                        @error('region_name')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Alamat</label>
                    <div class="col-10">
                        <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address', $region->address) }}</textarea>
                        @error('address')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label"></label>
                    <div class="col-10">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a class="btn btn-sm btn-secondary ml-1" href="{{ url('region') }}">Kembali</a>
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
