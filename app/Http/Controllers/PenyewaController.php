<?php

namespace App\Http\Controllers;

use App\Models\Indekos;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;

class PenyewaController extends Controller
{
    public function index($indekosId)
    {
        $indekos = Indekos::findOrFail($indekosId);
        
        $query = User::whereHas('kamar', function ($query) use ($indekosId) {
            $query->where('indekos_id', $indekosId);
        })->where('role', 'user')
        ->with(['kamar', 'payments']);

        if ($search = request('search')) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        // Menambahkan logika pengurutan
        $sort = request('sort', 'name'); // Default sort by name
        $direction = request('direction', 'asc'); // Default direction ascending

        switch($sort) {
            case 'name':
                $query->orderBy('name', $direction);
                break;
            case 'no_kamar':
                $query->join('kamars', 'users.kamar_id', '=', 'kamars.id')
                    ->orderBy('kamars.no_kamar', $direction)
                    ->select('users.*');
                break;
            case 'created_at':
                $query->orderBy('created_at', $direction);
                break;
            case 'status':
                $query->orderBy('status', $direction);
                break;
            case 'batas_pembayaran':
                $query->leftJoin('payments', function($join) {
                    $join->on('users.id', '=', 'payments.user_id')
                        ->whereRaw('payments.id IN (SELECT MAX(id) FROM payments GROUP BY user_id)');
                })
                ->orderBy('payments.batas_pembayaran', $direction)
                ->select('users.*');
                break;
        }

        $penyewa = $query->get();

        return view('admin.indekos.datapenyewa', compact('indekos', 'penyewa'));
    }

    public function sendEmail($indekosId, $id)
    {
        try {
            // Cari pengguna berdasarkan id dan pastikan mereka terkait dengan indekos yang benar
            $penyewa = User::whereHas('kamar', function ($query) use ($indekosId) {
                $query->where('indekos_id', $indekosId);
            })->findOrFail($id);

            // Logika pengiriman email
            // Contoh: Mail::to($penyewa->email)->send(new YourMailableClass());

            return redirect()->route('datapenyewa.index', $indekosId)->with('success', 'Email berhasil dikirim.');
        } catch (\Exception $e) {
            return redirect()->route('datapenyewa.index', $indekosId)->with('error', 'Email gagal dikirim.');
        }
    }
}
