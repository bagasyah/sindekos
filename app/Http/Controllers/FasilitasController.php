<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    public function index()
    {
        $fasilitas = Fasilitas::all();

        return view('admin.indekos.fasilitas', compact('fasilitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255|unique:fasilitas,nama_fasilitas',
        ], [
            'nama_fasilitas.unique' => 'Fasilitas dengan nama ini sudah ada sebelumnya.',
            'nama_fasilitas.required' => 'Nama fasilitas harus diisi.',
            'nama_fasilitas.max' => 'Nama fasilitas tidak boleh lebih dari 255 karakter.'
        ]);

        try {
            Fasilitas::create([
                'nama_fasilitas' => $request->nama_fasilitas,
            ]);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Fasilitas berhasil ditambahkan.']);
            }

            return redirect()->route('fasilitasuser.index')->with('success', 'Fasilitas berhasil ditambahkan.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Terjadi kesalahan saat menambahkan fasilitas.'], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan fasilitas.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255|unique:fasilitas,nama_fasilitas,' . $id,
        ], [
            'nama_fasilitas.unique' => 'Fasilitas dengan nama ini sudah ada sebelumnya.',
            'nama_fasilitas.required' => 'Nama fasilitas harus diisi.',
            'nama_fasilitas.max' => 'Nama fasilitas tidak boleh lebih dari 255 karakter.'
        ]);

        try {
            $fasilitas = Fasilitas::findOrFail($id);
            $fasilitas->update([
                'nama_fasilitas' => $request->nama_fasilitas,
            ]);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Fasilitas berhasil diperbarui.']);
            }

            return redirect()->route('fasilitasuser.index')->with('success', 'Fasilitas berhasil diperbarui.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Terjadi kesalahan saat memperbarui fasilitas.'], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui fasilitas.');
        }
    }

    public function destroy($id)
    {
        try {
            $fasilitas = Fasilitas::findOrFail($id);
            $fasilitas->delete();

            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Fasilitas berhasil dihapus.']);
            }

            return redirect()->route('fasilitasuser.index')->with('success', 'Fasilitas berhasil dihapus.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Terjadi kesalahan saat menghapus fasilitas.'], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus fasilitas.');
        }
    }
}