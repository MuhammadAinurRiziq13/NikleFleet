<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class VehicleController extends Controller
{
    public function index()
    {
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

        // Redirect dengan pesan sukses
        return redirect('/vehicle')->with('success', 'Kendaraan berhasil disimpan.');
    }

    public function show(string $id)
    {
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

        // Redirect dengan pesan sukses
        return redirect('/vehicle')->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        // Cek apakah kendaraan ada
        $vehicle = Vehicles::find($id);
        if (!$vehicle) {
            return redirect('/vehicle')->with('error', 'Data kendaraan tidak ditemukan');
        }

        try {
            // Hapus data kendaraan
            $vehicle->delete();

            return redirect('/vehicle')->with('success', 'Data kendaraan berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data
            return redirect('/vehicle')->with('error', 'Data kendaraan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}