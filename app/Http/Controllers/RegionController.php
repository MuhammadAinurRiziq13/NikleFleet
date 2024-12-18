<?php

namespace App\Http\Controllers;

use App\Models\Regions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class RegionController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Data Region',
            'list' => ['Home', 'Region']
        ];

        $page = (object)[
            'title' => 'Daftar Region yang terdaftar dalam sistem'
        ];

        return view(
            'region.index',
            [
                'breadcrumb' => $breadcrumb,
                'page' => $page,
            ]
        );
    }

    public function list(Request $request)
    {
        // Query dasar
        $regions = Regions::select();

        // Gunakan DataTables untuk mengelola data
        return DataTables::of($regions)
            ->addIndexColumn()
            ->addColumn('aksi', function ($region) {
                $btn = '<a href="' . url('/region/' . $region->id) . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a> ';
                if (Auth::user()->role == 'admin') {
                    $btn .= '<a href="' . url('/region/' . $region->id . '/edit') . '" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a> ';
                    $btn .= '<form class="d-inline-block" method="POST" action="' . url('/region/' . $region->id) . '">'
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
            'list' => ['Home', 'Region', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Form Tambah Data Region'
        ];

        return view('region.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'region_name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        // Simpan data ke database
        Regions::create($validatedData);

        // Redirect dengan pesan sukses
        return redirect('/region')->with('success', 'Data Region berhasil disimpan.');
    }


    public function show(string $id)
    {
        // Mengambil data reservation dengan join ke tabel regions, regions, dan users
        $region = Regions::find($id);

        // Mengatur breadcrumb dan page
        $breadcrumb = (object)[
            'title' => 'Detail Region',
            'list' => ['Home', 'Region', 'Detail']
        ];
        $page = (object)[
            'title' => 'Detail Data Region'
        ];

        // Mengirim data ke view
        return view('region.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'region' => $region,
        ]);
    }

    public function edit(string $id)
    {
        // Mengambil data reservation dengan join ke tabel regions, regions, dan users
        $region = Regions::find($id);

        // Mengatur breadcrumb dan page
        $breadcrumb = (object)[
            'title' => 'Edit Region',
            'list' => ['Home', 'Region', 'Edit']
        ];
        $page = (object)[
            'title' => 'Edit Data Region'
        ];

        // Mengirim data ke view
        return view('region.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'region' => $region,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'region_name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        // Temukan data dan update di database
        $region = Regions::findOrFail($id);
        $region->update($validatedData);

        // Redirect dengan pesan sukses
        return redirect('/region')->with('success', 'Data Region berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        // Cek apakah Region ada
        $region = Regions::find($id);
        if (!$region) {
            return redirect('/region')->with('error', 'Data Region tidak ditemukan');
        }

        try {
            // Hapus data Region
            $region->delete();

            return redirect('/region')->with('success', 'Data Region berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data
            return redirect('/region')->with('error', 'Data Region gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}