<?php

namespace App\Http\Controllers;
use App\Models\Indekos;
use Illuminate\Http\Request;
use App\Models\Payment;
use Carbon\Carbon;

class PemasukanController extends Controller
{
    public function index($indekosId, Request $request)
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

        $query = Payment::with(['user', 'user.kamar'])
            ->whereHas('user.kamar', function ($q) use ($indekosId) {
                $q->where('indekos_id', $indekosId);
            });

        // Filter pencarian
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_bayar', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_bayar', '<=', $request->end_date);
        }

        // Pengurutan
        $sortField = $request->input('sort_by', 'tanggal_bayar');
        $sortDirection = $request->input('sort_direction', 'desc');

        switch ($sortField) {
            case 'name':
                $query->join('users', 'payments.user_id', '=', 'users.id')
                      ->orderBy('users.name', $sortDirection)
                      ->select('payments.*');
                break;
            case 'no_kamar':
                $query->join('users', 'payments.user_id', '=', 'users.id')
                      ->join('kamars', 'users.kamar_id', '=', 'kamars.id')
                      ->orderBy('kamars.no_kamar', $sortDirection)
                      ->select('payments.*');
                break;
            case 'price':
                $query->orderBy('price', $sortDirection);
                break;
            case 'status':
                $query->orderBy('status', $sortDirection);
                break;
            case 'tanggal_bayar':
                $query->orderBy('tanggal_bayar', $sortDirection);
                break;
            case 'batas_pembayaran':
                $query->orderBy('batas_pembayaran', $sortDirection);
                break;
            default:
                $query->orderBy('tanggal_bayar', 'desc');
        }

        $payments = $query->get();

        return view('admin.indekos.pemasukan', compact('indekos', 'payments'));
    }
}