<?php

namespace App\Http\Controllers;

use App\Models\Mines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;

class MineController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Data Tambang',
            'list' => ['Home', 'Tambang']
        ];

        $page = (object)[
            'title' => 'Daftar Tambang yang terdaftar dalam sistem'
        ];

        return view(
            'mine.index',
            [
                'breadcrumb' => $breadcrumb,
                'page' => $page,
            ]
        );
    }

    public function list(Request $request)
    {
        // Query dasar
        $mines = Mines::select();

        // Gunakan DataTables untuk mengelola data
        return DataTables::of($mines)
            ->addIndexColumn()
            ->addColumn('aksi', function ($mine) {
                $btn = '<a href="' . url('/mine/' . $mine->id) . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a> ';
                if (Auth::user()->role == 'admin') {
                    $btn .= '<a href="' . url('/mine/' . $mine->id . '/edit') . '" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a> ';
                    $btn .= '<form class="d-inline-block" method="POST" action="' . url('/mine/' . $mine->id) . '">'
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
            'list' => ['Home', 'Tambang', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Form Tambah Data Tambang'
        ];

        return view('mine.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'mine_name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        // Simpan data ke database
        Mines::create($validatedData);

        // Redirect dengan pesan sukses
        return redirect('/mine')->with('success', 'Data tambang berhasil disimpan.');
    }


    public function show(string $id)
    {
        // Mengambil data reservation dengan join ke tabel mines, mines, dan users
        $mine = Mines::find($id);

        // Mengatur breadcrumb dan page
        $breadcrumb = (object)[
            'title' => 'Detail Tambang',
            'list' => ['Home', 'Tambang', 'Detail']
        ];
        $page = (object)[
            'title' => 'Detail Data Tambang'
        ];

        // Mengirim data ke view
        return view('mine.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'mine' => $mine,
        ]);
    }

    public function edit(string $id)
    {
        // Mengambil data reservation dengan join ke tabel mines, mines, dan users
        $mine = Mines::find($id);

        // Mengatur breadcrumb dan page
        $breadcrumb = (object)[
            'title' => 'Edit Tambang',
            'list' => ['Home', 'Tambang', 'Edit']
        ];
        $page = (object)[
            'title' => 'Edit Data Tambang'
        ];

        // Mengirim data ke view
        return view('mine.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'mine' => $mine,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'mine_name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        // Temukan data dan update di database
        $mine = Mines::findOrFail($id);
        $mine->update($validatedData);

        // Redirect dengan pesan sukses
        return redirect('/mine')->with('success', 'Data tambang berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        // Cek apakah Tambang ada
        $mine = Mines::find($id);
        if (!$mine) {
            return redirect('/mine')->with('error', 'Data Tambang tidak ditemukan');
        }

        try {
            // Hapus data Tambang
            $mine->delete();

            return redirect('/mine')->with('success', 'Data Tambang berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data
            return redirect('/mine')->with('error', 'Data Tambang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}