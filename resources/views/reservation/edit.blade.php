@extends('layouts.app')

@section('content')
    <div class="card card-outline card-primary shadow">
        <div class="card-header bg-dark-blue">
            <h6 class="card-title mb-0 text-white">{{ $page->title }}</h6>
        </div>
        <div class="card-body">
            @empty($reservation)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('reservation') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
                <form method="POST" action="{{ url('/reservation/' . $reservation->id) }}" class="form-horizontal">
                    @csrf
                    {!! method_field('PUT') !!}

                    <!-- Nama Pegawai -->
                    <div class="form-group row">
                        <div class="col-12 col-md-6">
                            <label class="control-label col-form-label">Nama Pegawai</label>
                            <select class="form-control pegawai" id="employee_id" name="employee_id" required>
                                <option value="{{ $reservation->employee_id }}">
                                    {{ $reservation->employee->employee_name ?? 'Nama Pegawai' }}
                                </option>
                            </select>
                            @error('employee_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="control-label col-form-label">Regions</label>
                            <select class="form-control" id="region_id" name="region_id" required>
                                <option value="{{ $reservation->region_id }}">
                                    {{ $reservation->region->region_name ?? 'Region' }}
                                </option>
                            </select>
                            @error('region_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Jenis Kendaraan dan Nomor Kendaraan -->
                    <div class="form-group row">
                        <div class="col-12 col-md-6">
                            <label class="control-label col-form-label">Jenis Kendaraan</label>
                            <select class="form-control" id="vehicle_type" name="vehicle_type" required>
                                <option value="angkutan orang"
                                    {{ $reservation->vehicle_type == 'angkutan orang' ? 'selected' : '' }}>Angkutan Orang
                                </option>
                                <option value="angkutan barang"
                                    {{ $reservation->vehicle_type == 'angkutan barang' ? 'selected' : '' }}>Angkutan Barang
                                </option>
                            </select>
                            @error('vehicle_type')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="control-label col-form-label">Nomor Kendaraan</label>
                            <select class="form-control select2" id="vehicle_id" name="vehicle_id" required>
                                <option value="{{ $reservation->vehicle_id }}">
                                    {{ $reservation->vehicle->vehicle_plate ?? 'Nomor Kendaraan' }}
                                </option>
                            </select>
                            @error('vehicle_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Tanggal Mulai dan Tanggal Akhir -->
                    <div class="form-group row">
                        <div class="col-12 col-md-6">
                            <label class="control-label col-form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                value="{{ old('start_date', $reservation->start_date) }}" required>
                            @error('start_date')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="control-label col-form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" name="end_date"
                                value="{{ old('end_date', $reservation->end_date) }}" required>
                            @error('end_date')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Approver 1 dan Approver 2 -->
                    <div class="form-group row">
                        <div class="col-12 col-md-6">
                            <label class="control-label col-form-label">Approver Cabang</label>
                            <select class="form-control" id="approver_1_id" name="approver_1_id" required>
                                @foreach ($approver as $a)
                                    <option value="{{ $a->id }}"
                                        {{ $reservation->approver_1_id == $a->id ? 'selected' : '' }}>
                                        {{ $a->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('approver_1_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="control-label col-form-label">Approver Pusat</label>
                            <select class="form-control" id="approver_2_id" name="approver_2_id" required>
                                @foreach ($approver as $a)
                                    <option value="{{ $a->id }}"
                                        {{ $reservation->approver_2_id == $a->id ? 'selected' : '' }}>
                                        {{ $a->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('approver_2_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-md-6">
                            <label class="control-label col-form-label">Nama Tambang</label>
                            <select class="form-control" id="mine_id" name="mine_id" required>
                                <option value="{{ $reservation->mine_id }}">
                                    {{ $reservation->mine->mine_name ?? 'Nama Tambang' }}
                                </option>
                            </select>
                            @error('mine_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="control-label col-form-label">Status Pengembalian</label>
                            <select class="form-control" id="return_status" name="return_status" required>
                                <option value="pending">Belum Dikembalikan</option>
                                <option value="returned">Dikembalikan</option>
                            </select>
                            @error('return_status')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="form-group row">
                        <div class="col-10 m-3">
                            <button type="submit" class="btn btn-primary btn-sm float-left">Simpan</button>
                            <a class="btn btn-sm btn-secondary ml-2 float-left" href="{{ url('reservation') }}">Kembali</a>
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
    <script>
        $(document).ready(function() {
            $('#employee_id').select2({
                placeholder: 'Nama Pegawai',
                ajax: {
                    url: '/employee_id',
                    processResults: function(data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.text
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        });

        $(document).ready(function() {
            // Inisialisasi Select2 untuk vehicle_type
            $('#vehicle_type').select2({
                placeholder: 'Pilih Jenis Kendaraan'
            });

            // Event listener untuk perubahan jenis kendaraan
            $('#vehicle_type').on('change', function() {
                var vehicleType = $(this).val(); // Mendapatkan jenis kendaraan yang dipilih

                console.log('Jenis kendaraan yang dipilih:', vehicleType); // Debugging

                $.ajax({
                    url: '/get-vehicles', // URL untuk mengambil kendaraan
                    method: 'GET',
                    data: {
                        vehicle_type: vehicleType
                    },
                    success: function(response) {
                        console.log('Response kendaraan:', response); // Debugging

                        // Kosongkan dropdown vehicle_id
                        $('#vehicle_id').empty();

                        // Jika ada kendaraan, tambahkan ke dropdown
                        if (response.length > 0) {
                            response.forEach(function(vehicle) {
                                // Menambahkan option dengan data kendaraan
                                $('#vehicle_id').append('<option value="' + vehicle.id +
                                    '">' + vehicle.vehicle_plate + ' - ' + vehicle
                                    .vehicle_name + '</option>');
                            });
                        } else {
                            // Menampilkan pesan jika tidak ada kendaraan
                            $('#vehicle_id').append(
                                '<option value="">Tidak ada kendaraan</option>');
                        }

                        // Re-inisialisasi Select2 setelah update data
                        $('#vehicle_id').select2({
                            placeholder: 'Pilih Nomor Kendaraan'
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX error:', error); // Debugging
                        alert('Terjadi kesalahan saat mengambil data kendaraan.');
                    }
                });
            });
        });

        $(document).ready(function() {
            $('#region_id').select2({
                placeholder: 'Regions',
                ajax: {
                    url: '/get-regions',
                    processResults: function(data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.text
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        });

        $(document).ready(function() {
            $('#mine_id').select2({
                placeholder: 'Tambang',
                ajax: {
                    url: '/get-mines',
                    processResults: function(data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.text
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        });

        $(document).ready(function() {
            $('#approver_1_id').select2({
                placeholder: 'Approver Cabang',
                ajax: {
                    url: '/get-approver-cabang',
                    processResults: function(data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.text
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        });

        $(document).ready(function() {
            $('#approver_2_id').select2({
                placeholder: 'Approver Pusat',
                ajax: {
                    url: '/get-approver-pusat',
                    processResults: function(data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.text
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
@endpush
