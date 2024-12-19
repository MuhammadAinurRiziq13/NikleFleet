<?php

namespace App\Http\Controllers;

use App\Models\Regions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class RegionController extends Controller
{
    public function index()
    {
        Log::info('Accessed region index page.');
        $breadcrumb = (object)[
            'title' => 'Data Region',
            'list' => ['Home', 'Region']
        ];

        $page = (object)[
            'title' => 'Daftar Region yang terdaftar dalam sistem'
        ];

        return view('region.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
        ]);
    }

    public function list(Request $request)
    {
        Log::info('Accessed region list API.');
        $regions = Regions::select();

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
        Log::info('Accessed create region form.');
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
        Log::info('Attempting to store new region.', ['data' => $request->all()]);
        $validatedData = $request->validate([
            'region_name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        Regions::create($validatedData);

        Log::info('New region stored successfully.', ['region_name' => $validatedData['region_name']]);
        return redirect('/region')->with('success', 'Data Region berhasil disimpan.');
    }

    public function show(string $id)
    {
        Log::info('Accessed region detail.', ['region_id' => $id]);
        $region = Regions::find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Region',
            'list' => ['Home', 'Region', 'Detail']
        ];
        $page = (object)[
            'title' => 'Detail Data Region'
        ];

        return view('region.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'region' => $region,
        ]);
    }

    public function edit(string $id)
    {
        Log::info('Accessed edit region form.', ['region_id' => $id]);
        $region = Regions::find($id);

        $breadcrumb = (object)[
            'title' => 'Edit Region',
            'list' => ['Home', 'Region', 'Edit']
        ];
        $page = (object)[
            'title' => 'Edit Data Region'
        ];

        return view('region.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'region' => $region,
        ]);
    }

    public function update(Request $request, $id)
    {
        Log::info('Attempting to update region.', ['region_id' => $id, 'data' => $request->all()]);
        $validatedData = $request->validate([
            'region_name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        $region = Regions::findOrFail($id);
        $region->update($validatedData);

        Log::info('Region updated successfully.', ['region_id' => $id]);
        return redirect('/region')->with('success', 'Data Region berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        Log::info('Attempting to delete region.', ['region_id' => $id]);
        $region = Regions::find($id);

        if (!$region) {
            Log::warning('Region not found for deletion.', ['region_id' => $id]);
            return redirect('/region')->with('error', 'Data Region tidak ditemukan');
        }

        try {
            $region->delete();
            Log::info('Region deleted successfully.', ['region_id' => $id]);
            return redirect('/region')->with('success', 'Data Region berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Failed to delete region.', ['region_id' => $id, 'error' => $e->getMessage()]);
            return redirect('/region')->with('error', 'Data Region gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}