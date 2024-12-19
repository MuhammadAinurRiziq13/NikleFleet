<?php

namespace App\Http\Controllers;

use App\Models\VehicleReservations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SubmissionReservationController extends Controller
{
    public function index()
    {
        Log::info('Accessed submission reservation index page.');
        $breadcrumb = (object)[
            'title' => 'Daftar Pengajuan Data Reservasi',
            'list' => ['Home', 'Pengajuan Reservasi']
        ];
        return view(
            'reservation.submission-reservation.index',
            [
                'breadcrumb' => $breadcrumb
            ]
        );
    }

    public function list(Request $request)
    {
        Log::info('Accessed submission reservation list.', ['filters' => $request->all()]);
        // Ambil ID pengguna yang sedang login
        $loggedInUserId = Auth::id();

        // Ambil posisi pengguna yang sedang login
        $userPosition = Auth::user()->position;

        // Query dasar
        $query = VehicleReservations::select(
            'vehicle_reservations.*',
            'employees.employee_name',
            'employees.employee_number'
        )
            ->join('employees', 'vehicle_reservations.employee_id', '=', 'employees.id');

        // Tambahkan kondisi berdasarkan posisi pengguna
        if ($userPosition === 'approver cabang') {
            $query->where('vehicle_reservations.approver_1_id', $loggedInUserId);
        } elseif ($userPosition === 'approver pusat') {
            $query->where('vehicle_reservations.approver_2_id', $loggedInUserId);
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
            ->addColumn('waktu_pengajuan', function ($reservation) {
                return date('d-m-Y H:i:s', strtotime($reservation->created_at));
            })
            ->addColumn('aksi', function ($reservation) {
                return '<a href="' . url('/submission-reservation/' . $reservation->id) . '" class="btn btn-info btn-sm">Detail</a>';
            })
            ->addColumn('status', function ($reservation) {
                // Ambil posisi pengguna yang sedang login
                $userPosition = Auth::user()->position;

                if ($userPosition === 'approver cabang') {
                    // Logika untuk approver cabang
                    if ($reservation->status == 'pending' && $reservation->approver_1_status == 'pending') {
                        return '<a href="' . url('/submission-reservation/' . $reservation->id . '/proses') . '" class="btn btn-primary btn-sm">Proses</a>';
                    } elseif ($reservation->status == 'approved') {
                        return '<p class="text-success">Disetujui</p>';
                    } elseif ($reservation->status == 'rejected') {
                        return '<p class="text-danger">Ditolak</p>';
                    } elseif ($reservation->approver_1_status != 'pending') {
                        return '<p class="text-warning">Pending</p>';
                    }
                } elseif ($userPosition === 'approver pusat') {
                    // Logika untuk approver pusat
                    if ($reservation->status == 'pending' && $reservation->approver_2_status == 'pending' && $reservation->approver_1_status != 'pending') {
                        return '<a href="' . url('/submission-reservation/' . $reservation->id . '/proses') . '" class="btn btn-primary btn-sm">Proses</a>';
                    } elseif ($reservation->status == 'approved') {
                        return '<p class="text-success">Disetujui</p>';
                    } elseif ($reservation->status == 'rejected') {
                        return '<p class="text-danger">Ditolak</p>';
                    } elseif ($reservation->approver_2_status == 'pending') {
                        return '<p class="text-warning">Pending</p>';
                    }
                }
            })
            ->rawColumns(['aksi', 'status'])
            ->make(true);
    }

    public function show(string $id)
    {
        Log::info('Accessed submission reservation details.', ['reservation_id' => $id]);
        // Mengambil data reservation dengan join ke tabel employees, vehicles, dan users
        $reservation = VehicleReservations::with(['employee', 'vehicle', 'mine', 'region', 'approver1', 'approver2'])
            ->find($id);

        // Mengatur breadcrumb dan page
        $breadcrumb = (object)[
            'title' => 'Detail Reservasi Kendaraan',
            'list' => ['Home', 'Reservasi', 'Detail']
        ];
        $page = (object)[
            'title' => 'Detail Data Reservasi'
        ];

        // Mengirim data ke view
        return view('reservation.submission-reservation.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'reservation' => $reservation,
        ]);
    }

    public function proses(string $id)
    {
        Log::info('Accessed submission reservation processing page.', ['reservation_id' => $id]);
        // Mengambil data reservation dengan join ke tabel employees, vehicles, dan users
        $reservation = VehicleReservations::with(['employee', 'vehicle', 'mine', 'region', 'approver1', 'approver2'])
            ->find($id);

        // Mengatur breadcrumb dan page
        $breadcrumb = (object)[
            'title' => 'Proses Reservasi Kendaraan',
            'list' => ['Home', 'Reservasi', 'Proses']
        ];
        $page = (object)[
            'title' => 'Proses Data Reservasi'
        ];

        // Mengirim data ke view
        return view('reservation.submission-reservation.proses', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'reservation' => $reservation,
        ]);
    }

    public function update(Request $request, $id)
    {
        Log::info('Attempting to update submission reservation.', ['reservation_id' => $id, 'data' => $request->all()]);
        // Validasi input
        $validatedData = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        // Cari data reservation berdasarkan ID
        $reservation = VehicleReservations::findOrFail($id);

        // Dapatkan posisi pengguna yang sedang login
        $userPosition = Auth::user()->position;

        // Logika pengubahan status berdasarkan posisi pengguna
        if ($validatedData['status'] === 'approved') {
            // Hanya pengguna dengan posisi tertentu yang bisa menyetujui
            if ($userPosition === 'approver cabang') {
                $reservation->approver_1_status = 'approved';
            } else if ($userPosition === 'approver pusat') {
                $reservation->approver_2_status = 'approved';
                $reservation->status = 'approved';
                // Update vehicle_status menjadi 'in_use'
                $vehicle = $reservation->vehicle;
                if ($vehicle) {
                    $vehicle->vehicle_status = 'in_use';
                    $vehicle->save();
                }
            }
        } else {
            // Status ditetapkan ke 'rejected'
            if ($userPosition === 'approver cabang') {
                $reservation->approver_1_status = 'rejected';
            } else if ($userPosition === 'approver pusat') {
                $reservation->approver_2_status = 'rejected';
            }
            $reservation->status = 'rejected'; // Set kolom status menjadi 'rejected'
        }

        // Simpan perubahan
        $reservation->save();

        Log::info('Submission reservation updated successfully.', ['reservation_id' => $id]);

        // Redirect dengan pesan sukses
        return redirect('/submission-reservation')
            ->with('success', 'Data reservasi berhasil diperbarui');
    }
}