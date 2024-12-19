<?php

namespace App\Http\Controllers;

use App\Models\Mines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class MineController extends Controller
{
    public function index()
    {
        Log::info('Accessed mine index page.');
        $breadcrumb = (object)[
            'title' => 'Data Tambang',
            'list' => ['Home', 'Tambang']
        ];

        $page = (object)[
            'title' => 'Daftar Tambang yang terdaftar dalam sistem'
        ];

        return view('mine.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
        ]);
    }

    public function list(Request $request)
    {
        Log::info('Accessed mine list API.');
        $mines = Mines::select();

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
        Log::info('Accessed create mine form.');
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
        Log::info('Attempting to store new mine.', ['data' => $request->all()]);
        $validatedData = $request->validate([
            'mine_name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        Mines::create($validatedData);

        Log::info('New mine stored successfully.', ['mine_name' => $validatedData['mine_name']]);
        return redirect('/mine')->with('success', 'Data tambang berhasil disimpan.');
    }

    public function show(string $id)
    {
        Log::info('Accessed mine detail.', ['mine_id' => $id]);
        $mine = Mines::find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Tambang',
            'list' => ['Home', 'Tambang', 'Detail']
        ];
        $page = (object)[
            'title' => 'Detail Data Tambang'
        ];

        return view('mine.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'mine' => $mine,
        ]);
    }

    public function edit(string $id)
    {
        Log::info('Accessed edit mine form.', ['mine_id' => $id]);
        $mine = Mines::find($id);

        $breadcrumb = (object)[
            'title' => 'Edit Tambang',
            'list' => ['Home', 'Tambang', 'Edit']
        ];
        $page = (object)[
            'title' => 'Edit Data Tambang'
        ];

        return view('mine.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'mine' => $mine,
        ]);
    }

    public function update(Request $request, $id)
    {
        Log::info('Attempting to update mine.', ['mine_id' => $id, 'data' => $request->all()]);
        $validatedData = $request->validate([
            'mine_name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        $mine = Mines::findOrFail($id);
        $mine->update($validatedData);

        Log::info('Mine updated successfully.', ['mine_id' => $id]);
        return redirect('/mine')->with('success', 'Data tambang berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        Log::info('Attempting to delete mine.', ['mine_id' => $id]);
        $mine = Mines::find($id);

        if (!$mine) {
            Log::warning('Mine not found for deletion.', ['mine_id' => $id]);
            return redirect('/mine')->with('error', 'Data Tambang tidak ditemukan');
        }

        try {
            $mine->delete();
            Log::info('Mine deleted successfully.', ['mine_id' => $id]);
            return redirect('/mine')->with('success', 'Data Tambang berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Failed to delete mine.', ['mine_id' => $id, 'error' => $e->getMessage()]);
            return redirect('/mine')->with('error', 'Data Tambang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}