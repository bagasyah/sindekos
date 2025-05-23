<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\kamar;
use App\Models\Indekos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function homepage()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Arahkan pengguna berdasarkan peran
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role === 'user') {
            return redirect()->route('user.dashboard');
        }
    }
    public function createakun()
    {
        $data = User::get();
        $kamars = kamar::all();
        $indekos = Indekos::all();

        // Ambil ID kamar yang sudah digunakan
        $usedKamarIds = User::pluck('kamar_id')->toArray();

        // Filter kamar yang tidak digunakan
        $availableKamars = $kamars->whereNotIn('id', $usedKamarIds);

        return view('createakun', compact('data', 'availableKamars', 'indekos'));
    }
    public function kelolaakun(Request $request)
    {
        $query = User::with(['kamar', 'kamar.indekos']);

        // Filter status hidden
        if (!$request->has('show_hidden')) {
            $query->where('status', '!=', 'hidden');
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhereHas('kamar', function($q) use ($search) {
                      $q->where('no_kamar', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('kamar.indekos', function($q) use ($search) {
                      $q->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }

        // Pengurutan
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');

        if ($sort === 'indekos') {
            $query->join('kamars', 'users.kamar_id', '=', 'kamars.id')
                  ->join('indekos', 'kamars.indekos_id', '=', 'indekos.id')
                  ->orderBy('indekos.nama', $direction)
                  ->select('users.*');
        } else {
            $query->orderBy($sort, $direction);
        }

        $data = $query->paginate(7);

        return view('kelolaakun', compact('data'));
    }
    public function store(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:users,email',
            'nama' => 'required|string|regex:/^[A-Za-z\s]+$/',
            'no_telp' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|in:admin,user',
        ];

        if ($request->role === 'user') {
            $rules['indekos_id'] = 'required|exists:indekos,id';
            $rules['kamar_id'] = 'required|exists:kamars,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator)
                ->with('error', 'Gagal menambahkan akun. Silakan periksa kembali input Anda.');
        }

        try {
            $data = [
                'name' => $request->nama,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => 'active',
            ];

            if ($request->role === 'user') {
                $indekos = Indekos::find($request->indekos_id);
                $data['indekos_id'] = $request->indekos_id;
                $data['nama_indekos'] = $indekos->nama;
                $data['kamar_id'] = $request->kamar_id;
            }

            User::create($data);

            return redirect()->route('kelolaakun')->with('success', 'Akun berhasil ditambahkan');
        } catch (\Exception $e) {
            \Log::error('Gagal menambahkan akun: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan akun. Silakan coba lagi.');
        }
    }
    public function editakun(Request $request, $id)
    {
        $data = User::find($id);
        $indekos = Indekos::all();

        // Ambil ID kamar yang sudah digunakan
        $usedKamarIds = User::pluck('kamar_id')->toArray();

        // Filter kamar yang tidak digunakan
        $availableKamars = Kamar::where('indekos_id', $data->indekos_id)
                                ->whereNotIn('id', $usedKamarIds)
                                ->orWhere('id', $data->kamar_id) // Tambahkan kamar yang sedang digunakan oleh user ini
                                ->get();

        return view('editakun', compact('data', 'availableKamars', 'indekos'));
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'nama'     => 'required|string|regex:/^[A-Za-z\s]+$/',
            'email'    => 'required|email|unique:users,email,' . $id,
            'no_telp'  => 'required|string',
            'status'   => 'required|in:active,non active,hidden',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator)
                ->with('error', 'Gagal memperbarui akun. Silakan periksa kembali input Anda.');
        }

        try {
            $user = User::find($id);
            if (!$user) {
                return redirect()->back()->with('error', 'Akun tidak ditemukan');
            }

            // Cek perubahan status
            if ($user->status !== $request->status) {
                // Cek apakah user punya kamar
                if ($user->kamar_id) {
                    $kamar = Kamar::find($user->kamar_id);
                    if ($kamar) {
                        if ($request->status === 'active') {
                            // Cek apakah ada user lain dengan status 'active' yang menempati kamar yang sama
                            $existingUser = User::where('kamar_id', $kamar->id)
                                                ->where('status', 'active')
                                                ->where('id', '!=', $user->id)
                                                ->first();

                            if ($existingUser) {
                                return redirect()->back()
                                    ->withInput()
                                    ->with('error', 'Tidak dapat mengubah status menjadi active. Kamar nomor ' . $kamar->no_kamar . ' di indekos ' . $kamar->indekos->nama . ' sudah terisi.');
                            }
                            $kamar->status = 'Terisi';
                        } elseif ($request->status === 'non active') {
                            $kamar->status = 'Tidak Terisi';
                        }
                        // Jika status 'hidden', tidak perlu mengubah status kamar
                        $kamar->save();
                    }
                }
            }

            $user->name    = $request->nama;
            $user->email   = $request->email;
            $user->no_telp = $request->no_telp;
            $user->status  = $request->status;
            $user->save();

            return redirect()->route('kelolaakun')->with('success', 'Akun berhasil diperbarui');
        } catch (\Exception $e) {
            \Log::error('Gagal memperbarui akun: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui akun. Silakan coba lagi.');
        }
    }
    public function delete(Request $request,$id)
    {
        $data = User::find($id);
        if($data){
            $data->delete();
        }
        return redirect()->route('kelolaakun');
    }
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Cek status pengguna
            if (Auth::user()->status === 'non active' || Auth::user()->status === 'hidden') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak dapat diakses. Silakan hubungi administrator.',
                ])->onlyInput('email');
            }

            // Arahkan pengguna berdasarkan peran
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->role === 'user') {
                return redirect()->route('user.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
            'password' => 'Password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function getKamars($indekosId)
    {
        $kamars = Kamar::where('indekos_id', $indekosId)->get();
        return response()->json($kamars);
    }

    public function getAvailableKamars($indekosId)
    {
        $kamars = Kamar::where('indekos_id', $indekosId)
                       ->where('status', 'Tidak Terisi') // Hanya ambil kamar yang statusnya "Tidak Terisi"
                       ->get();
        return response()->json($kamars);
    }
}
