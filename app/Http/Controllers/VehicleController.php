<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class VehicleController extends Controller
{
    public function index()
    {
        Log::info('Accessed vehicle index page.');
        $breadcrumb = (object)[
            'title' => 'Data Kendaraan',
            'list' => ['Home', 'Kendaraan']
        ];

        $page = (object)[
            'title' => 'Daftar Kendaraan yang terdaftar dalam sistem'
        ];

        return view(
            'vehicle.index',
            [
                'breadcrumb' => $breadcrumb,
                'page' => $page,
            ]
        );
    }

    public function list(Request $request)
    {
        Log::info('Accessed vehicle list.', ['filters' => $request->all()]);
        // Query dasar
        $vehicles = Vehicles::select();

        // Gunakan DataTables untuk mengelola data
        return DataTables::of($vehicles)
            ->addIndexColumn()
            ->addColumn('aksi', function ($vehicle) {
                $btn = '<a href="' . url('/vehicle/' . $vehicle->id) . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a> ';
                if (Auth::user()->role == 'admin') {
                    $btn .= '<a href="' . url('/vehicle/' . $vehicle->id . '/edit') . '" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a> ';
                    $btn .= '<form class="d-inline-block" method="POST" action="' . url('/vehicle/' . $vehicle->id) . '">'
                        . csrf_field() . method_field('DELETE') .
                        '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');"><i class="fas fa-trash-alt"></i></button></form>';
                }
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        Log::info('Accessed create vehicle form.');
        $breadcrumb = (object)[
            'title' => '',
            'list' => ['Home', 'Kendaraan', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Form Tambah Data Kendaraan Kendaraan'
        ];

        return view('vehicle.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
        ]);
    }

    public function store(Request $request)
    {
        Log::info('Attempting to store new vehicle.', ['data' => $request->all()]);
        $validatedData = $request->validate([
            'vehicle_name' => 'required|string|max:255',
            'vehicle_type' => 'required|in:angkutan orang,angkutan barang',
            'vehicle_plate' => 'required|string|max:255',
            'vehicle_owner' => 'required|in:company,rental',
        ]);

        // Tambahkan default value untuk vehicle_status
        $validatedData['vehicle_status'] = 'available';

        // Simpan data ke database
        Vehicles::create($validatedData);

        Log::info('Vehicle stored successfully.', ['vehicle_name' => $validatedData['vehicle_name']]);
        return redirect('/vehicle')->with('success', 'Kendaraan berhasil disimpan.');
    }

    public function show(string $id)
    {
        Log::info('Accessed vehicle details.', ['vehicle_id' => $id]);
        // Mengambil data reservation dengan join ke tabel employees, vehicles, dan users
        $vehicle = Vehicles::find($id);

        // Mengatur breadcrumb dan page
        $breadcrumb = (object)[
            'title' => 'Detail Kendaraan',
            'list' => ['Home', 'Kendaraan', 'Detail']
        ];
        $page = (object)[
            'title' => 'Detail Data Kendaraan'
        ];

        // Mengirim data ke view
        return view('vehicle.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'vehicle' => $vehicle,
        ]);
    }

    public function edit(string $id)
    {
        Log::info('Accessed edit vehicle form.', ['vehicle_id' => $id]);
        // Mengambil data reservation dengan join ke tabel employees, vehicles, dan users
        $vehicle = Vehicles::find($id);

        // Mengatur breadcrumb dan page
        $breadcrumb = (object)[
            'title' => 'Edit Kendaraan',
            'list' => ['Home', 'Kendaraan', 'Edit']
        ];
        $page = (object)[
            'title' => 'Edit Data Kendaraan'
        ];

        // Mengirim data ke view
        return view('vehicle.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'vehicle' => $vehicle,
        ]);
    }

    public function update(Request $request, $id)
    {
        Log::info('Attempting to update vehicle.', ['vehicle_id' => $id, 'data' => $request->all()]);
        // Validasi data input
        $validatedData = $request->validate([
            'vehicle_name' => 'required|string|max:255',
            'vehicle_type' => 'required|in:angkutan orang,angkutan barang',
            'vehicle_plate' => 'required|string|max:255',
            'vehicle_owner' => 'required|in:company,rental',
        ]);

        // Cari kendaraan berdasarkan ID
        $vehicle = Vehicles::findOrFail($id);

        // Update data kendaraan
        $vehicle->update($validatedData);

        Log::info('Vehicle updated successfully.', ['vehicle_id' => $id]);
        return redirect('/vehicle')->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        Log::info('Attempting to delete vehicle.', ['vehicle_id' => $id]);
        // Cek apakah kendaraan ada
        $vehicle = Vehicles::find($id);
        if (!$vehicle) {
            Log::warning('Vehicle not found for deletion.', ['vehicle_id' => $id]);
            return redirect('/vehicle')->with('error', 'Data kendaraan tidak ditemukan');
        }

        try {
            // Hapus data kendaraan
            $vehicle->delete();
            Log::info('Vehicle deleted successfully.', ['vehicle_id' => $id]);
            return redirect('/vehicle')->with('success', 'Data kendaraan berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Failed to delete vehicle.', ['vehicle_id' => $id, 'error' => $e->getMessage()]);
            return redirect('/vehicle')->with('error', 'Data kendaraan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}