<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function index()
    {
        Log::info('Accessed employee index page.');
        $breadcrumb = (object)[
            'title' => 'Data Pegawai',
            'list' => ['Home', 'Pegawai']
        ];

        $page = (object)[
            'title' => 'Daftar Pegawai yang terdaftar dalam sistem'
        ];

        return view('employee.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
        ]);
    }

    public function list(Request $request)
    {
        Log::info('Accessed employee list API.');
        $employees = Employees::select();

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
        Log::info('Accessed create employee form.');
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
        Log::info('Attempting to store new employee.', ['data' => $request->all()]);
        $validatedData = $request->validate([
            'employee_number' => 'required|string|max:255|unique:employees,employee_number',
            'employee_name' => 'required|string|max:255',
            'employee_email' => 'required|email|max:255|unique:employees,employee_email',
            'employee_position' => 'required|string|max:255',
        ]);

        Employees::create($validatedData);

        Log::info('New employee stored successfully.', ['employee_number' => $validatedData['employee_number']]);
        return redirect('/employee')->with('success', 'Pegawai berhasil disimpan.');
    }

    public function show(string $id)
    {
        Log::info('Accessed employee detail.', ['employee_id' => $id]);
        $employee = Employees::find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Pegawai',
            'list' => ['Home', 'Pegawai', 'Detail']
        ];
        $page = (object)[
            'title' => 'Detail Data Pegawai'
        ];

        return view('employee.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'employee' => $employee,
        ]);
    }

    public function edit(string $id)
    {
        Log::info('Accessed employee edit form.', ['employee_id' => $id]);
        $employee = Employees::find($id);

        $breadcrumb = (object)[
            'title' => 'Edit Pegawai',
            'list' => ['Home', 'Pegawai', 'Edit']
        ];
        $page = (object)[
            'title' => 'Edit Data Pegawai'
        ];

        return view('employee.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'employee' => $employee,
        ]);
    }

    public function update(Request $request, $id)
    {
        Log::info('Attempting to update employee.', ['employee_id' => $id, 'data' => $request->all()]);
        $validatedData = $request->validate([
            'employee_number' => 'required|string|max:255',
            'employee_name' => 'required|string|max:255',
            'employee_email' => 'required|email|max:255',
            'employee_position' => 'required|string|max:255',
        ]);

        $employee = Employees::findOrFail($id);
        $employee->update($validatedData);

        Log::info('Employee updated successfully.', ['employee_id' => $id]);
        return redirect('/employee')->with('success', 'Data Pegawai berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        Log::info('Attempting to delete employee.', ['employee_id' => $id]);
        $employee = Employees::find($id);

        if (!$employee) {
            Log::warning('Employee not found for deletion.', ['employee_id' => $id]);
            return redirect('/employee')->with('error', 'Data Pegawai tidak ditemukan');
        }

        try {
            $employee->delete();
            Log::info('Employee deleted successfully.', ['employee_id' => $id]);
            return redirect('/employee')->with('success', 'Data Pegawai berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Failed to delete employee.', ['employee_id' => $id, 'error' => $e->getMessage()]);
            return redirect('/employee')->with('error', 'Data Pegawai gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}