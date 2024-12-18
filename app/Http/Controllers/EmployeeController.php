<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Data Pegawai',
            'list' => ['Home', 'Pegawai']
        ];

        $page = (object)[
            'title' => 'Daftar Pegawai yang terdaftar dalam sistem'
        ];

        return view(
            'employee.index',
            [
                'breadcrumb' => $breadcrumb,
                'page' => $page,
            ]
        );
    }

    public function list(Request $request)
    {
        // Query dasar
        $employees = Employees::select();

        // Gunakan DataTables untuk mengelola data
        return DataTables::of($employees)
            ->addIndexColumn()
            ->addColumn('aksi', function ($employee) {
                $btn = '<a href="' . url('/employee/' . $employee->id) . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a> ';
                if (Auth::user()->role == 'admin') {
                    $btn .= '<a href="' . url('/employee/' . $employee->id . '/edit') . '" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a> ';
                    $btn .= '<form class="d-inline-block" method="POST" action="' . url('/employee/' . $employee->id) . '">'
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
            'list' => ['Home', 'Pegawai', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Form Tambah Data Pegawai'
        ];

        return view('employee.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_number' => 'required|string|max:255|unique:employees,employee_number',
            'employee_name' => 'required|string|max:255',
            'employee_email' => 'required|email|max:255|unique:employees,employee_email',
            'employee_position' => 'required|string|max:255',
        ]);

        // Simpan data ke database
        Employees::create($validatedData);

        // Redirect dengan pesan sukses
        return redirect('/employee')->with('success', 'Pegawai berhasil disimpan.');
    }


    public function show(string $id)
    {
        // Mengambil data reservation dengan join ke tabel employees, Employees, dan users
        $employee = Employees::find($id);

        // Mengatur breadcrumb dan page
        $breadcrumb = (object)[
            'title' => 'Detail Pegawai',
            'list' => ['Home', 'Pegawai', 'Detail']
        ];
        $page = (object)[
            'title' => 'Detail Data Pegawai'
        ];

        // Mengirim data ke view
        return view('employee.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'employee' => $employee,
        ]);
    }

    public function edit(string $id)
    {
        // Mengambil data reservation dengan join ke tabel employees, Employees, dan users
        $employee = Employees::find($id);

        // Mengatur breadcrumb dan page
        $breadcrumb = (object)[
            'title' => 'Edit Pegawai',
            'list' => ['Home', 'Pegawai', 'Edit']
        ];
        $page = (object)[
            'title' => 'Edit Data Pegawai'
        ];

        // Mengirim data ke view
        return view('employee.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'employee' => $employee,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'employee_number' => 'required|string|max:255',
            'employee_name' => 'required|string|max:255',
            'employee_email' => 'required|email|max:255',
            'employee_position' => 'required|string|max:255',
        ]);

        // Cari Pegawai berdasarkan ID
        $employee = Employees::findOrFail($id);

        // Update data Pegawai
        $employee->update($validatedData);

        // Redirect dengan pesan sukses
        return redirect('/employee')->with('success', 'Data Pegawai berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        // Cek apakah Pegawai ada
        $employee = Employees::find($id);
        if (!$employee) {
            return redirect('/employee')->with('error', 'Data Pegawai tidak ditemukan');
        }

        try {
            // Hapus data Pegawai
            $employee->delete();

            return redirect('/employee')->with('success', 'Data Pegawai berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data
            return redirect('/employee')->with('error', 'Data Pegawai gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}