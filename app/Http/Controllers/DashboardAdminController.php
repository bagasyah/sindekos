<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\Payment;
use App\Models\Indekos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    public function index(Request $request)
    {
        $timeRange = $request->input('timeRange', 'day');

        // Hitung total, dan FORMAT ANGKA DI SINI
        $totalPengeluaran = number_format(Pengeluaran::sum('jumlah_uang'), 0, ',', '.');
        $totalPemasukan = number_format(Payment::where('status', 'Selesai')->sum('price'), 0, ',', '.');
        $totalPenghasilan = number_format(Payment::where('status', 'Selesai')->sum('price') - Pengeluaran::sum('jumlah_uang'), 0, ',', '.');
        $totalIndekos = Indekos::count(); // total Indekos tidak perlu di format

        $data = $this->getDataForRange($timeRange);

        if ($request->ajax()) {
            // Kirim angka yang SUDAH DIFORMAT
            return response()->json([
                'totalPengeluaran' => $totalPengeluaran,
                'totalPemasukan' => $totalPemasukan,
                'totalPenghasilan' => $totalPenghasilan,
                'totalIndekos' => $totalIndekos, //tidak perlu di format karena integer
                'allDates' => $data['allDates'],
                'dataPengeluaran' => $data['dataPengeluaran'],
                'dataPemasukan' => $data['dataPemasukan'],
                'dataPenghasilan' => $data['dataPenghasilan'],
                'timeRange' => $timeRange,
            ]);
        }
        // Data awal untuk Blade (jika bukan AJAX)
            return view('admin.dashboard', [
                'initialData' => [
                    'totalPengeluaran' => $totalPengeluaran,
                    'totalPemasukan' => $totalPemasukan,
                    'totalPenghasilan' => $totalPenghasilan,
                    'totalIndekos' => $totalIndekos, //tidak perlu di format karena integer
                    'allDates' => $data['allDates'],
                    'dataPengeluaran' => $data['dataPengeluaran'],
                    'dataPemasukan' => $data['dataPemasukan'],
                    'dataPenghasilan' => $data['dataPenghasilan'],
                    'timeRange' => $timeRange,
                ]
            ]);
    }

    private function getDataForRange($timeRange)
    {
        switch ($timeRange) {
            case 'month':
                $dateFormat = 'Y-m';
                $dateFunction = "DATE_FORMAT(tanggal, '%Y-%m')";
                $dateFunctionPayment = "DATE_FORMAT(updated_at, '%Y-%m')";
                break;
            case 'year':
                $dateFormat = 'Y';
                $dateFunction = "DATE_FORMAT(tanggal, '%Y')";
                $dateFunctionPayment = "DATE_FORMAT(updated_at, '%Y')";
                break;
            default: // day
                $dateFormat = 'Y-m-d';
                $dateFunction = 'DATE(tanggal)';
                $dateFunctionPayment = 'DATE(updated_at)';
                break;
        }
    
    // 1. Pengeluaran
    $pengeluaranData = Pengeluaran::select(
        DB::raw("$dateFunction as date"),
        DB::raw('SUM(jumlah_uang) as total')
    )
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // 2. Pemasukan
    $pemasukanData = Payment::where('status', 'Selesai')
        ->select(
            DB::raw("$dateFunctionPayment as date"),
            DB::raw('SUM(price) as total')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    // 3. Menggabungkan Tanggal
    $allDates = $pengeluaranData->pluck('date')
        ->merge($pemasukanData->pluck('date'))
        ->unique()
        ->sort()
        ->values()
        ->map(function ($date) use ($dateFormat) {
            if ($dateFormat === 'Y') {
                return $date . '-01-01';  // Buat tanggal lengkap HANYA untuk tahun
            }
            return $date; // Untuk hari dan bulan, biarkan apa adanya
        });

    // 4. Membuat Array Data Grafik (PERBAIKAN DI SINI)
    $dataPengeluaran = [];
    $dataPemasukan = [];
    $dataPenghasilan = [];

    foreach ($allDates as $date) {
        // Perbaikan: Gunakan $dateFormat untuk menentukan perbandingan
        if ($dateFormat === 'Y') {
            $compareValue = date('Y', strtotime($date));
        } elseif ($dateFormat === 'Y-m') {
            $compareValue = date('Y-m', strtotime($date));
        } else { // 'Y-m-d'
            $compareValue = $date;
        }

        $pengeluaran = $pengeluaranData->firstWhere('date', $compareValue);
        $dataPengeluaran[] = $pengeluaran ? $pengeluaran->total : 0;

        $pemasukan = $pemasukanData->firstWhere('date', $compareValue);
        $dataPemasukan[] = $pemasukan ? $pemasukan->total : 0;

        $dataPenghasilan[] = ($pemasukan ? $pemasukan->total : 0) - ($pengeluaran ? $pengeluaran->total : 0);
    }

    return [
        'allDates' => $allDates,
        'dataPengeluaran' => $dataPengeluaran,
        'dataPemasukan' => $dataPemasukan,
        'dataPenghasilan' => $dataPenghasilan,
    ];
}

}