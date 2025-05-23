<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Fasilitas::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama_fasilitas', 'like', '%' . $search . '%');
        }

        $fasilitas = $query->get();

        return view('admin.fasilitas_admin', compact('fasilitas'));
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'nama_fasilitas' => 'required|string|max:255|unique:fasilitas,nama_fasilitas',
            ], [
                'nama_fasilitas.unique' => 'Nama fasilitas sudah ada.',
                'nama_fasilitas.required' => 'Nama fasilitas harus diisi.',
                'nama_fasilitas.max' => 'Nama fasilitas tidak boleh lebih dari 255 karakter.'
            ]);

            // Menyimpan fasilitas baru
            Fasilitas::create([
                'nama_fasilitas' => $request->nama_fasilitas,
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('fasilitas.index')->with('success', 'Fasilitas berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Nama fasilitas sudah ada.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $request->validate([
                'nama_fasilitas' => 'required|string|max:255|unique:fasilitas,nama_fasilitas,' . $id,
            ], [
                'nama_fasilitas.unique' => 'Fasilitas dengan nama ini sudah ada sebelumnya.',
                'nama_fasilitas.required' => 'Nama fasilitas harus diisi.',
                'nama_fasilitas.max' => 'Nama fasilitas tidak boleh lebih dari 255 karakter.'
            ]);

            // Mencari fasilitas berdasarkan ID
            $fasilitas = Fasilitas::findOrFail($id);
            $fasilitas->update([
                'nama_fasilitas' => $request->nama_fasilitas,
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('fasilitas.index')->with('success', 'Fasilitas berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('id', $id)
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui fasilitas.']);
        }
    }

    public function destroy($id)
    {
        // Mencari fasilitas berdasarkan ID
        $fasilitas = Fasilitas::findOrFail($id);
        $fasilitas->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('fasilitas.index')->with('success', 'Fasilitas berhasil dihapus.');
    }
}
