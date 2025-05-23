<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\PengaduanBaruNotification;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    public function index()
    {
        // Ambil data pengaduan untuk pengguna yang sedang login dengan relasi
        $pengaduan = Pengaduan::with(['user.kamar'])->where('user_id', Auth::id())->get();

        return view('user.pengaduan-user', compact('pengaduan'));
    }

    public function adminIndex()
    {
        // Ambil semua pengaduan dengan relasi user dan kamar
        $pengaduan = Pengaduan::with(['user.kamar'])->get();

        return view('admin.pengaduan-admin', compact('pengaduan'));
    }

    // Metode untuk menyimpan pengaduan
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'masalah' => 'required|string',
            'foto' => 'nullable|image|max:5120', // Validasi foto, 5120 KB = 5 MB
        ]);

        // Menyimpan pengaduan
        $pengaduan = new Pengaduan();
        $pengaduan->user_id = auth()->id();
        $pengaduan->tanggal_pelaporan = now(); // Isi tanggal pelaporan dengan tanggal saat ini
        $pengaduan->masalah = $request->masalah;

        // Menyimpan foto jika ada
        if ($request->hasFile('foto')) {
            $fileName = time() . '.' . $request->foto->extension();
            $request->foto->storeAs('public', $fileName);
            $pengaduan->foto = $fileName;
        }

        $pengaduan->status = 'Pending'; // Status default
        $pengaduan->save(); // Menyimpan ke database

        // Kirim notifikasi ke semua admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new PengaduanBaruNotification($pengaduan));
        }

        return redirect()->route('user.pengaduan')->with('success', 'Pengaduan berhasil dibuat.');
    }

    public function export()
    {
        // Ambil data pengaduan yang statusnya Selesai
        $pengaduans = Pengaduan::with(['user.kamar.indekos'])
            ->where('status', 'Selesai')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'Nama');
        $sheet->setCellValue('B1', 'Indekos');
        $sheet->setCellValue('C1', 'No Kamar');
        $sheet->setCellValue('D1', 'Tanggal Pelaporan');
        $sheet->setCellValue('E1', 'Tanggal Perbaikan');
        $sheet->setCellValue('F1', 'Masalah');
        $sheet->setCellValue('G1', 'Status');

        // Isi data
        $row = 2;
        foreach ($pengaduans as $pengaduan) {
            $sheet->setCellValue('A' . $row, $pengaduan->user->name ?? '-');
            $sheet->setCellValue('B' . $row, $pengaduan->user->kamar->indekos->nama ?? '-');
            $sheet->setCellValue('C' . $row, $pengaduan->user->kamar->no_kamar ?? '-');
            $sheet->setCellValue('D' . $row, $pengaduan->tanggal_pelaporan);
            $sheet->setCellValue('E' . $row, $pengaduan->tanggal_perbaikan ?? '-');
            $sheet->setCellValue('F' . $row, $pengaduan->masalah);
            $sheet->setCellValue('G' . $row, $pengaduan->status);
            $row++;
        }

        // Auto-size kolom
        foreach(range('A','G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'riwayat_pengaduan.xlsx';

        // Set header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function destroy($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);
        
        // Hapus foto jika ada
        if ($pengaduan->foto) {
            Storage::delete('public/' . $pengaduan->foto);
        }
        if ($pengaduan->foto_akhir) {
            Storage::delete('public/' . $pengaduan->foto_akhir);
        }
        
        $pengaduan->delete();
        
        return redirect()->back()->with('success', 'Pengaduan berhasil dihapus');
    }
}
