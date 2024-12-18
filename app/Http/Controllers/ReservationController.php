<?php

namespace App\Http\Controllers;

use App\Exports\VehicleReservationsExport;
use App\Models\Employees;
use App\Models\Mines;
use App\Models\Regions;
use App\Models\User;
use App\Models\VehicleReservations;
use App\Models\Vehicles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ReservationController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Data Reservasi',
            'list' => ['Home', 'Reservasi']
        ];

        $page = (object)[
            'title' => 'Daftar Reservasi yang terdaftar dalam sistem'
        ];

        return view(
            'reservation.index',
            [
                'breadcrumb' => $breadcrumb,
                'page' => $page,
            ]
        );
    }

    public function list(Request $request)
    {
        // Query dasar
        $query = VehicleReservations::select(
            'vehicle_reservations.*',
            'employees.employee_name',
            'employees.employee_number'
        )
            ->join('employees', 'vehicle_reservations.employee_id', '=', 'employees.id');

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('vehicle_reservations.status', $request->status);
        }

        // Filter berdasarkan status pengembalian
        if ($request->has('return_status') && $request->return_status != '') {
            $query->where('vehicle_reservations.return_status', $request->return_status);
        }

        // Urutkan berdasarkan status
        $query->orderByRaw(
            "CASE 
                WHEN vehicle_reservations.status = 'pending' THEN 1
                WHEN vehicle_reservations.status = 'approved' THEN 2
                WHEN vehicle_reservations.status = 'rejected' THEN 3
                ELSE 4
            END"
        );

        // Ambil data hasil query
        $reservations = $query->get();

        // Gunakan DataTables untuk mengelola data
        return DataTables::of($reservations)
            ->addIndexColumn()
            ->addColumn('aksi', function ($reservation) {
                $btn = '<a href="' . url('/reservation/' . $reservation->id) . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a> ';
                if (Auth::user()->role == 'admin') {
                    $btn .= '<a href="' . url('/reservation/' . $reservation->id . '/edit') . '" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a> ';
                    $btn .= '<form class="d-inline-block" method="POST" action="' . url('/reservation/' . $reservation->id) . '">'
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
            'list' => ['Home', 'Reservasi', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Form Tambah Data Reservasi Kendaraan'
        ];

        return view('reservation.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'employee_id'   => 'required|exists:employees,id',
            'mine_id'   => 'required|exists:mines,id',
            'region_id'   => 'required|exists:regions,id',
            'vehicle_type'  => 'required|in:angkutan orang,angkutan barang',
            'vehicle_id'    => 'required|exists:vehicles,id',
            'start_date'    => 'required|date|before_or_equal:end_date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'approver_1_id' => 'required|exists:users,id',
            'approver_2_id' => 'required|exists:users,id|different:approver_1_id',
        ], [
            'different' => 'Approver 1 dan Approver 2 tidak boleh sama.',
            'before_or_equal' => 'Tanggal mulai harus sebelum atau sama dengan tanggal akhir.',
            'after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal mulai.',
        ]);

        // Simpan data
        VehicleReservations::create([
            'employee_id'   => $validatedData['employee_id'],
            'mine_id'   => $validatedData['mine_id'],
            'region_id'   => $validatedData['region_id'],
            'vehicle_type'  => $validatedData['vehicle_type'],
            'vehicle_id'    => $validatedData['vehicle_id'],
            'start_date'    => $validatedData['start_date'],
            'end_date'      => $validatedData['end_date'],
            'approver_1_id' => $validatedData['approver_1_id'],
            'approver_2_id' => $validatedData['approver_2_id'],
            'status'        => 'pending',        // Status default
            'return_status' => 'pending',        // Status pengembalian default
        ]);

        // Redirect ke halaman utama dengan pesan sukses
        return redirect('/reservation')
            ->with('success', 'Reservasi berhasil ditambahkan');
    }


    public function show(string $id)
    {
        // Mengambil data reservation dengan join ke tabel employees, vehicles, dan users
        $reservation = VehicleReservations::with(['employee', 'vehicle', 'mine', 'region', 'approver1', 'approver2'])
            ->find($id);

        // Jika data tidak ditemukan, redirect atau tampilkan pesan error
        if (!$reservation) {
            return redirect()->route('reservation.index')->with('error', 'Data tidak ditemukan.');
        }

        // Mengatur breadcrumb dan page
        $breadcrumb = (object)[
            'title' => 'Detail Reservasi Kendaraan',
            'list' => ['Home', 'Reservasi', 'Detail']
        ];
        $page = (object)[
            'title' => 'Detail Data Reservasi'
        ];

        // Mengirim data ke view
        return view('reservation.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'reservation' => $reservation,
        ]);
    }

    public function edit(string $id)
    {
        // Ambil data reservasi berdasarkan ID
        $reservation = VehicleReservations::where('id', $id)->firstOrFail();

        // Ambil data pengguna dengan role 'approver'
        $approver = User::where('role', 'approver')->get();

        // Breadcrumb dan halaman
        $breadcrumb = (object)[
            'title' => '',
            'list' => ['Home', 'Reservasi', 'Edit']
        ];
        $page = (object)[
            'title' => 'Form Edit Data Reservasi'
        ];

        // Return view dengan data yang dibutuhkan
        return view('reservation.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'reservation' => $reservation,
            'approver' => $approver,
        ]);
    }

    public function update(Request $request, string $id)
    {
        // Validasi input awal
        $validatedData = $request->validate([
            'employee_id'   => 'required|exists:employees,id',
            'mine_id'       => 'required|exists:mines,id',
            'region_id'     => 'required|exists:regions,id',
            'vehicle_type'  => 'required|in:angkutan orang,angkutan barang',
            'vehicle_id'    => 'required|exists:vehicles,id',
            'start_date'    => 'required|date|before_or_equal:end_date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'approver_1_id' => 'required|exists:users,id',
            'approver_2_id' => 'required|exists:users,id|different:approver_1_id',
            'return_status' => 'required|in:pending,returned',
        ], [
            'before_or_equal' => 'Tanggal mulai harus sebelum atau sama dengan tanggal akhir.',
            'after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal mulai.',
        ]);

        // Validasi tambahan untuk status kendaraan
        if ($validatedData['return_status'] === 'returned') {
            $vehicle = Vehicles::findOrFail($validatedData['vehicle_id']);

            if ($vehicle->vehicle_status === 'available') {
                return redirect()->back()
                    ->withErrors(['return_status' => 'Kendaraan tidak sedang dipakai'])
                    ->withInput();
            }
        }

        // Ambil data reservasi lama
        $reservation = VehicleReservations::findOrFail($id);

        try {
            // Jika vehicle_id berubah, update vehicle_status lama dan baru
            if ($reservation->vehicle_id != $validatedData['vehicle_id']) {
                // Set kendaraan lama menjadi 'available'
                Vehicles::where('id', $reservation->vehicle_id)->update(['vehicle_status' => 'available']);

                // Set kendaraan baru menjadi 'in_use'
                Vehicles::where('id', $validatedData['vehicle_id'])->update(['vehicle_status' => 'in_use']);
            }

            // Update data reservasi
            $reservation->update([
                'employee_id'   => $validatedData['employee_id'],
                'region_id'     => $validatedData['region_id'],
                'mine_id'       => $validatedData['mine_id'],
                'vehicle_type'  => $validatedData['vehicle_type'],
                'vehicle_id'    => $validatedData['vehicle_id'],
                'start_date'    => $validatedData['start_date'],
                'end_date'      => $validatedData['end_date'],
                'approver_1_id' => $validatedData['approver_1_id'],
                'approver_2_id' => $validatedData['approver_2_id'],
                'return_status' => $validatedData['return_status'],
            ]);

            // Jika return_status bernilai 'returned', ubah status kendaraan menjadi 'available'
            if ($validatedData['return_status'] === 'returned') {
                $vehicle->vehicle_status = 'available';
                $vehicle->save();
            }

            // Redirect dengan pesan sukses
            return redirect('/reservation')
                ->with('success', 'Data reservasi berhasil diperbarui');
        } catch (\Exception $e) {
            // Jika ada kesalahan
            return redirect('/reservation')
                ->with('error', 'Terjadi kesalahan saat memperbarui data reservasi');
        }
    }

    public function destroy(string $id)
    {
        // Cek apakah reservasi ada
        $reservation = VehicleReservations::find($id);
        if (!$reservation) {
            return redirect('/reservation')->with('error', 'Data reservasi tidak ditemukan');
        }

        try {
            // Update status kendaraan menjadi 'available'
            Vehicles::where('id', $reservation->vehicle_id)->update(['vehicle_status' => 'available']);

            // Hapus data reservasi
            $reservation->delete();

            return redirect('/reservation')->with('success', 'Data reservasi berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data
            return redirect('/reservation')->with('error', 'Data reservasi gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function export()
    {
        return Excel::download(new VehicleReservationsExport, 'vehicle_reservations.xlsx');
    }

    public function getEmployeesData(Request $request)
    {
        // Ambil data dari database dengan memfilter berdasarkan id
        $employees = Employees::where('id', 'LIKE', '%' . $request->input('q') . '%')
            ->orWhere('employee_name', 'LIKE', '%' . $request->input('q') . '%') // Tambahkan pencarian berdasarkan nama
            ->paginate(100);

        $data = [];
        // Looping untuk menyiapkan data yang akan dikirimkan ke Select2
        foreach ($employees as $employee) {
            // Menyiapkan ID dan Nama karyawan dalam format yang ditampilkan
            $data[] = [
                'id' => $employee->id,
                'text' => $employee->employee_number . ' - ' . $employee->employee_name // Gabungkan ID dan Nama
            ];
        }

        // Kirim data dalam format JSON
        return response()->json($data);
    }

    public function getRegions(Request $request)
    {
        // Ambil data dari database dengan memfilter berdasarkan id
        $regions = Regions::where('id', 'LIKE', '%' . $request->input('q') . '%')
            ->orWhere('region_name', 'LIKE', '%' . $request->input('q') . '%') // Tambahkan pencarian berdasarkan nama
            ->paginate(100);

        $data = [];
        // Looping untuk menyiapkan data yang akan dikirimkan ke Select2
        foreach ($regions as $region) {
            // Menyiapkan ID dan Nama karyawan dalam format yang ditampilkan
            $data[] = [
                'id' => $region->id,
                'text' => $region->id . ' - ' . $region->region_name
            ];
        }

        // Kirim data dalam format JSON
        return response()->json($data);
    }

    public function getMines(Request $request)
    {
        // Ambil data dari database dengan memfilter berdasarkan id
        $mines =  Mines::where('id', 'LIKE', '%' . $request->input('q') . '%')
            ->orWhere('mine_name', 'LIKE', '%' . $request->input('q') . '%') // Tambahkan pencarian berdasarkan nama
            ->paginate(100);

        $data = [];
        // Looping untuk menyiapkan data yang akan dikirimkan ke Select2
        foreach ($mines as $mine) {
            // Menyiapkan ID dan Nama karyawan dalam format yang ditampilkan
            $data[] = [
                'id' => $mine->id,
                'text' => $mine->id . ' - ' . $mine->mine_name
            ];
        }

        // Kirim data dalam format JSON
        return response()->json($data);
    }

    public function getApproverCabang(Request $request)
    {
        // Ambil data user dengan posisi approver cabang, sesuai filter input
        $cabang = User::where('position', 'approver cabang')
            ->where(function ($query) use ($request) {
                $search = $request->input('q');
                if (!empty($search)) {
                    $query->where('id', 'LIKE', '%' . $search . '%')
                        ->orWhere('full_name', 'LIKE', '%' . $search . '%'); // Tambahkan pencarian berdasarkan nama
                }
            })
            ->limit(100) // Batasi hasil untuk efisiensi
            ->get();

        // Siapkan data untuk Select2
        $data = $cabang->map(function ($c) {
            return [
                'id' => $c->id,
                'text' => $c->id . ' - ' . $c->full_name
            ];
        });

        // Kirim data dalam format JSON
        return response()->json($data);
    }

    public function getApproverPusat(Request $request)
    {
        // Ambil data user dengan posisi approver cabang, sesuai filter input
        $cabang = User::where('position', 'approver pusat')
            ->where(function ($query) use ($request) {
                $search = $request->input('q');
                if (!empty($search)) {
                    $query->where('id', 'LIKE', '%' . $search . '%')
                        ->orWhere('full_name', 'LIKE', '%' . $search . '%'); // Tambahkan pencarian berdasarkan nama
                }
            })
            ->limit(100) // Batasi hasil untuk efisiensi
            ->get();

        // Siapkan data untuk Select2
        $data = $cabang->map(function ($c) {
            return [
                'id' => $c->id,
                'text' => $c->id . ' - ' . $c->full_name
            ];
        });

        // Kirim data dalam format JSON
        return response()->json($data);
    }

    public function getVehicles(Request $request)
    {
        // Ambil kendaraan berdasarkan jenis kendaraan dan status 'available'
        $vehicles = Vehicles::where('vehicle_type', $request->vehicle_type)
            ->where('vehicle_status', 'available')
            ->get();

        // Kembalikan response berupa data kendaraan dalam format JSON
        return response()->json($vehicles);
    }

    public function getAvailableApprovers(Request $request)
    {
        // Ambil ID approver_1 yang dipilih dari request
        $approver1Id = $request->input('approver_1_id');

        // Ambil semua approver yang belum dipilih sebagai approver_1
        $approvers = User::where('role', 'approver')
            ->where('id', '!=', $approver1Id)
            ->get();

        $data = [];
        foreach ($approvers as $approver) {
            $data[] = [
                'id' => $approver->id,
                'text' => $approver->username
            ];
        }

        return response()->json($data);
    }
}