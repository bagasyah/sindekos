<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Indekos;
use App\Models\Payment;
use App\Models\Pengeluaran;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class RiwayatKeuanganController extends Controller
{
    public function index(Request $request, $indekosId)
    {
        // Validasi tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            if ($endDate->lt($startDate)) {
                return redirect()->back()->withErrors(['error' => 'Urutan tanggal yang dimasukkan salah. Tanggal akhir tidak boleh lebih awal dari tanggal mulai.']);
            }
        }

        $indekos = Indekos::findOrFail($indekosId);
        
        // Mengambil data riwayat
        $query = collect();
        
        // Ambil data pembayaran yang statusnya Selesai saja
        $paymentsQuery = Payment::with(['user', 'user.kamar'])
            ->where('status', 'Selesai')  // Tambahkan filter status Selesai
            ->whereHas('user.kamar', function($q) use ($indekosId) {
                $q->where('indekos_id', $indekosId);
            });
            
        // Ambil data pengeluaran
        $pengeluaranQuery = Pengeluaran::where('indekos_id', $indekosId);

        // Filter pencarian
        if ($request->filled('search')) {
            $paymentsQuery->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
            $pengeluaranQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter tanggal
        if ($request->filled('start_date')) {
            $paymentsQuery->whereDate('tanggal_bayar', '>=', $request->start_date);
            $pengeluaranQuery->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $paymentsQuery->whereDate('tanggal_bayar', '<=', $request->end_date);
            $pengeluaranQuery->whereDate('tanggal', '<=', $request->end_date);
        }

         // Gabungkan dan format data
        $payments = $paymentsQuery->get()->map(function($payment) {
            return [
                'nama' => $payment->user->name,
                'no_kamar' => $payment->user->kamar->no_kamar,
                'jenis' => 'Penyewa',
                'tanggal_bayar' => $payment->tanggal_bayar,
                'jumlah_uang' => $payment->price,
                'status' => $payment->status
            ];
        });

        $pengeluarans = $pengeluaranQuery->get()->map(function($pengeluaran) {
            return [
                'nama' => $pengeluaran->nama,
                'no_kamar' => '-',
                'jenis' => 'Pengeluaran',
                'tanggal_bayar' => $pengeluaran->tanggal,
                'jumlah_uang' => $pengeluaran->jumlah_uang, // Hapus tanda negatif di sini
                'status' => 'Selesai'
            ];
        });

        $riwayats = $payments->concat($pengeluarans);

        // Pengurutan
        $sortField = $request->input('sort_by', 'tanggal_bayar');
        $sortDirection = $request->input('sort_direction', 'desc');

        $riwayats = $riwayats->sortBy(function ($riwayat) use ($sortField) {
            return $riwayat[$sortField];
        }, SORT_REGULAR, $sortDirection === 'desc');

        // Hitung total (hanya dari pembayaran status Selesai)
        $totalPemasukan = $payments->sum('jumlah_uang');
        $totalPengeluaran = $pengeluarans->sum('jumlah_uang'); // Hapus abs() di sini karena sudah positif
        $totalJumlahUang = $totalPemasukan - $totalPengeluaran;

        return view('admin.indekos.riwayat_keuangan', compact('indekos', 'riwayats', 'totalPemasukan', 'totalPengeluaran', 'totalJumlahUang'));
    }

    public function export($indekosId)
    {
        // Ambil data payments yang terkait dengan indekosId dan status "Selesai"
        $payments = Payment::whereHas('user.kamar', function($query) use ($indekosId) {
            $query->where('indekos_id', $indekosId);
        })->where('status', 'Selesai')
          ->with(['user.kamar' => function($query) use ($indekosId) {
              $query->where('indekos_id', $indekosId);
          }])->get();

        // Ambil data pengeluaran yang terkait dengan indekosId dan status "Selesai"
        $pengeluarans = Pengeluaran::where('indekos_id', $indekosId)
                                   ->where('status', 'Selesai')
                                   ->get();

        // Gabungkan data payments dan pengeluaran
        $riwayats = collect();

        if ($payments->isNotEmpty()) {
            $riwayats = $riwayats->merge($payments->map(function($payment) {
                return [
                    'nama' => $payment->user->name,
                    'no_kamar' => $payment->user->kamar->no_kamar ?? '-',
                    'jenis' => 'Penyewa',
                    'tanggal_bayar' => $payment->tanggal_bayar,
                    'jumlah_uang' => $payment->price,
                    'status' => $payment->status,
                ];
            }));
        }

        if ($pengeluarans->isNotEmpty()) {
            $riwayats = $riwayats->merge($pengeluarans->map(function($pengeluaran) {
                return [
                    'nama' => $pengeluaran->nama,
                    'no_kamar' => '-',
                    'jenis' => 'Pengeluaran',
                    'tanggal_bayar' => $pengeluaran->tanggal,
                    'jumlah_uang' => $pengeluaran->jumlah_uang,
                    'status' => $pengeluaran->status,
                ];
            }));
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'NAMA');
        $sheet->setCellValue('B1', 'No Kamar');
        $sheet->setCellValue('C1', 'Jenis');
        $sheet->setCellValue('D1', 'Tanggal Bayar');
        $sheet->setCellValue('E1', 'Jumlah Uang');
        $sheet->setCellValue('F1', 'Status');

        // Isi data
        $row = 2;
        foreach ($riwayats as $riwayat) {
            $sheet->setCellValue('A' . $row, $riwayat['nama']);
            $sheet->setCellValue('B' . $row, $riwayat['no_kamar']);
            $sheet->setCellValue('C' . $row, $riwayat['jenis']);
            $sheet->setCellValue('D' . $row, $riwayat['tanggal_bayar']);
            $sheet->setCellValue('E' . $row, $riwayat['jumlah_uang']);
            $sheet->setCellValue('F' . $row, $riwayat['status']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'riwayat_keuangan.xlsx';

        // Set header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
