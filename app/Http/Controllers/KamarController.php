<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar;
use App\Models\Indekos;
use App\Models\Fasilitas;

class KamarController extends Controller
{
    public function viewkamar(){
        $kamar = Kamar::all();
        return view('admin.indekos.kamar', compact('kamar'));
    }
    public function getCategory(Request $request)
    {
        // Ambil semua fasilitas jika tidak ada pencarian
        if ($request->name) {
            $fasilitas = Fasilitas::where('nama_fasilitas', 'LIKE', "%{$request->name}%")->get();
        } else {
            $fasilitas = Fasilitas::all(); // Ambil semua fasilitas jika tidak ada pencarian
        }
        return response()->json($fasilitas);
    }
    public function index($indekosId)
    {
        $indekos = Indekos::findOrFail($indekosId);
        $query = Kamar::with(['users', 'indekos'])  // tambahkan relasi indekos
                    ->where('indekos_id', $indekosId);

        // Pencarian
        if ($search = request('search')) {
            $query->where('no_kamar', 'LIKE', "%{$search}%");
        }

        // Pengurutan
        $sortBy = request('sort_by', 'no_kamar');
        $sortDirection = request('sort_direction', 'asc');

        // Validasi kolom yang diizinkan untuk sorting
        $allowedSortColumns = ['no_kamar', 'harga', 'status'];
        
        if (in_array($sortBy, $allowedSortColumns)) {
            if ($sortBy === 'harga') {
                $query->orderByRaw('CAST(harga AS DECIMAL) ' . strtoupper($sortDirection));
            } else {
                $query->orderBy($sortBy, $sortDirection);
            }
        }

        $kamars = $query->get();
        $fasilitas = Fasilitas::all();

        // Update status kamar
        foreach ($kamars as $kamar) {
            $isOccupied = $kamar->users->contains(function ($user) {
                return $user->status === 'active';
            });
            
            $kamar->status = $isOccupied ? 'Terisi' : 'Tidak Terisi';
            $kamar->save();
        }

        return view('admin.indekos.kamar', compact('indekos', 'kamars', 'fasilitas'));
    }
    
    public function store(Request $request, $indekosId)
    {
        $request->validate([
            'no_kamar' => 'required|string|max:255|unique:kamars,no_kamar,NULL,id,indekos_id,' . $indekosId,
            'harga' => 'required|string',
            'fasilitas_id' => 'required|array',
        ]);

        $indekos = Indekos::findOrFail($indekosId);

        $harga = str_replace('.', '', $request->harga);

        $kamars = new Kamar([
            'no_kamar' => $request->no_kamar,
            'status' => 'Tidak Terisi',
            'harga' => $harga,
            'fasilitas_id' => $request->fasilitas_id ? implode(',', $request->fasilitas_id) : null,
        ]);

        $indekos->kamars()->save($kamars);
        return redirect()->route('kamar.index', ['indekosId' => $indekosId])
            ->with('success', 'Kamar berhasil ditambahkan');
    }

    public function edit($indekosId, $kamarId)
    {
        $indekos = Indekos::findOrFail($indekosId);
        $kamar = Kamar::findOrFail($kamarId);
        $fasilitas = Fasilitas::all();

        return view('admin.indekos.edit_kamar', compact('indekos', 'kamar', 'fasilitas'));
    }

    public function update(Request $request, $indekosId, $kamarId)
    {
        $request->validate([
            'no_kamar' => 'required|string|max:255|unique:kamars,no_kamar,' . $kamarId . ',id,indekos_id,' . $indekosId,
            'harga' => 'required|numeric',
            'fasilitas_id' => 'required|array',
        ]);

        $kamar = Kamar::findOrFail($kamarId);
        $kamar->update([
            'no_kamar' => $request->no_kamar,
            'harga' => $request->harga,
            'fasilitas_id' => $request->fasilitas_id ? implode(',', $request->fasilitas_id) : null,
        ]);

        return redirect()->route('kamar.index', ['indekosId' => $indekosId])
                     ->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy($indekosId, $kamarId)
    {
        $kamar = Kamar::findOrFail($kamarId);
        $kamar->delete();

        return redirect()->route('kamar.index', ['indekosId' => $indekosId])
            ->with('success', 'Kamar berhasil dihapus');
    }
}
