<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndekosController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\Pengaduan2Controller;
use App\Http\Controllers\PenyewaController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\RiwayatKeuanganController;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FasilitasAdminController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationUserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\CspReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Perbaikan pada route home

Route::get('/', [HomeController::class, 'homepage'])->name('homepage');
Route::get('/user', [HomeController::class, 'kelolaakun'])->name('kelolaakun');
// Route::get('/create', [HomeController::class, 'createakun'])->name('createakun');
// Route::post('/store', [HomeController::class, 'store'])->name('store');
// Route::get('/edit/{id}', [HomeController::class, 'editakun'])->name('editakun');
// Route::put('/update/{id}', [HomeController::class, 'update'])->name('update');
Route::delete('/delete/{id}', [HomeController::class, 'delete'])->name('delete');

Route::get('/login', [HomeController::class, 'showLoginForm'])->name('login');
Route::post('/login', [HomeController::class, 'login']);
Route::post('/logout', [HomeController::class, 'logout'])->name('logout');

// Rute yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/kelolaakun', [HomeController::class, 'kelolaakun'])->name('kelolaakun');
        Route::get('/createakun', [HomeController::class, 'createakun'])->name('createakun');
        Route::post('/storeakun', [HomeController::class, 'store'])->name('storeakun');
        Route::get('/editakun/{id}', [HomeController::class, 'editakun'])->name('editakun');
        Route::put('/updateakun/{id}', [HomeController::class, 'update'])->name('updateakun');
        Route::delete('/deleteakun/{id}', [HomeController::class, 'destroy'])->name('deleteakun');
        Route::get('/admin/indekos', [AdminController::class, 'indekos'])->name('admin.indekos');
        Route::resource('indekos', IndekosController::class);
        Route::get('/indekos/{id}', [IndekosController::class, 'show'])->name('indekos.detail');
        // Route::get('/kamar', [KamarController::class, 'viewkamar'])->name('kamar.index');
        Route::get('/indekos/{indekosId}/kamar', [KamarController::class, 'index'])->name('kamar.index');
        Route::post('/indekos/{indekosId}/kamar', [KamarController::class, 'store'])->name('kamar.store');
        Route::delete('/indekos/{indekosId}/kamar/{kamarId}', [KamarController::class, 'destroy'])->name('kamar.destroy');
        Route::get('/admin/pengaduan', [Pengaduan2Controller::class, 'index'])->name('admin.pengaduan');
        Route::put('/pengaduan/update/{id}', [Pengaduan2Controller::class, 'update'])->name('pengaduan.update');
        Route::get('/riwayat-pengaduan', [Pengaduan2Controller::class, 'riwayat'])->name('riwayat.pengaduan');
        Route::get('/data-penyewa/{indekosId}', [PenyewaController::class, 'index'])->name('penyewa.index');
        Route::post('/get-category', [KamarController::class, 'getCategory'])->name('get-category');
        Route::get('/indekos/{indekos}/laporan-keuangan/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
        Route::post('/indekos/{indekos}/laporan-keuangan/pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
        Route::get('/indekos/{indekos}/laporan-keuangan/riwayat', [RiwayatKeuanganController::class, 'index'])->name('riwayat_keuangan.index');
        Route::get('/riwayat-keuangan/{indekosId}/export', [RiwayatKeuanganController::class, 'export'])->name('riwayat-keuangan.export');
        Route::resource('payments', PaymentController::class);
        Route::post('/send-email/{id}', [EmailController::class, 'sendEmail'])->name('send.email');
        Route::get('/indekos/{id}/edit', [IndekosController::class, 'edit'])->name('indekos.edit');
        Route::put('/indekos/{id}', [IndekosController::class, 'update'])->name('indekos.update');
        Route::get('/fasilitas', [FasilitasAdminController::class, 'index'])->name('fasilitas.index');
        Route::post('/fasilitas', [FasilitasAdminController::class, 'store'])->name('fasilitas.store');
        Route::put('/fasilitas/{id}', [FasilitasAdminController::class, 'update'])->name('fasilitas.update');
        Route::delete('/fasilitas/{id}', [FasilitasAdminController::class, 'destroy'])->name('fasilitas.destroy');
        Route::get('/admin/notifications', [NotificationController::class, 'index'])->name('admin.notifications');
        Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('adminnotifications.read');
        Route::get('/admin/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/pengeluaran/{indekosId}', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
    });

    Route::middleware(['role:user'])->group(function () {
        Route::get('/user/dashboard', [DashboardUserController::class, 'index'])->name('user.dashboard');
        Route::get('/user/akun', [UserController::class, 'akun'])->name('user.akun');
        Route::get('/user/pembayaran', [PaymentController::class, 'index'])->name('user.pembayaran');
        Route::get('/user/pengaduan', [PengaduanController::class, 'index'])->name('user.pengaduan');
        Route::post('/pengaduan/store', [PengaduanController::class, 'store'])->name('pengaduan.store');
        Route::post('/midtrans-notification', [PaymentController::class, 'notificationHandler']);
        Route::post('/checkout', [PaymentController::class, 'checkout']);
        Route::get('/user/edit-photo', [DashboardUserController::class, 'editPhoto'])->name('user.editPhoto');
        Route::post('/user/update-photo', [DashboardUserController::class, 'updatePhoto'])->name('user.updatePhoto');
       // Rute untuk menampilkan notifikasi pengguna
        Route::get('/user/notifications', [NotificationUserController::class, 'index'])->name('user.notifications');

        // Rute untuk menandai notifikasi sebagai dibaca
        Route::patch('/user/notifications/{id}/read', [NotificationUserController::class, 'markAsRead'])->name('notifications.read');
    });
});

Route::get('/get-kamars/{indekosId}', [HomeController::class, 'getAvailableKamars']);

// Rute untuk menghasilkan snapToken
Route::post('/get-snap-token', [PaymentController::class, 'getSnapToken']);


Route::post('/api/checkout', [PaymentController::class, 'checkout'])->name('checkout.process');
Route::get('/api/check-payment-status/{paymentId}', [PaymentController::class, 'checkPaymentStatus'])->name('payment.check-status');
Route::post('/api/midtrans/notification', [PaymentController::class, 'handleNotification'])->name('midtrans.notification');
Route::get('/api/verify-payment/{paymentId}', [PaymentController::class, 'verifyPaymentData'])->name('payment.verify');

Route::post('/api/update-payment-status', [PaymentController::class, 'updatePaymentStatus']);

Route::get('/pemasukan/{indekosId}', [PemasukanController::class, 'index'])->name('pemasukan.index');

Route::middleware(['web'])->group(function () {
    Route::post('/pengaduan/store', [PengaduanController::class, 'store'])->name('pengaduan.store');

    // Payment routes
    Route::match(['get', 'post'], '/api/midtrans/finish', [PaymentController::class, 'handleFinish'])
        ->name('midtrans.finish')
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        
    Route::match(['get', 'post'], '/api/midtrans/unfinish', [PaymentController::class, 'handleUnfinish'])
        ->name('midtrans.unfinish')
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        
    Route::match(['get', 'post'], '/api/midtrans/error', [PaymentController::class, 'handleError'])
        ->name('midtrans.error')
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        
    Route::post('/api/midtrans/notification', [PaymentController::class, 'handleNotification'])
        ->name('midtrans.notification')
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
});

// Route untuk verifikasi pembayaran
Route::get('/api/verify-payment/{paymentId}', [PaymentController::class, 'verifyPaymentData'])
    ->name('payment.verify')
    ->middleware('auth');

// Route::middleware(['auth'])->group(function () {
//     Route::get('/indekos/fasilitas', [FasilitasController::class, 'index'])->name('fasilitasuser.index');
//     Route::post('/indekos/fasilitas', [FasilitasController::class, 'store'])->name('fasilitasuser.store');
//     Route::put('/indekos/fasilitas/{id}', [FasilitasController::class, 'update'])->name('fasilitasuser.update');
//     Route::delete('/indekos/fasilitas/{id}', [FasilitasController::class, 'destroy'])->name('fasilitasuser.destroy');
// });

Route::get('indekos/{indekosId}/kamar/{kamarId}/edit', [KamarController::class, 'edit'])->name('kamar.edit');
Route::put('indekos/{indekosId}/kamar/{kamarId}', [KamarController::class, 'update'])->name('kamar.update');

// Tambahkan rute ini jika belum ada
Auth::routes(['verify' => true]);

// Rute untuk menampilkan form lupa password
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

// Rute untuk mengirim email reset password
Route::post('/forgot-password', function (Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

// Rute untuk menampilkan form reset password
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token, 'email' => request()->email]);
})->middleware('guest')->name('password.reset');

// Rute untuk menyimpan password baru
Route::post('/reset-password', [NewPasswordController::class, 'store'])->middleware('guest')->name('password.store');

// Rute untuk menampilkan pengaduan pengguna
Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');

// Rute untuk menyimpan pengaduan
Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');

// Rute untuk menampilkan pengaduan admin
Route::get('/admin/pengaduan', [PengaduanController::class, 'adminIndex'])->name('admin.pengaduan');

Route::get('/pembayaran/{id}/download-pdf', [PaymentController::class, 'downloadPdf'])->name('pembayaran.downloadPdf');

// Rute untuk halaman data penyewa
Route::get('/datapenyewa', [PenyewaController::class, 'index'])->name('datapenyewa.index');

// Rute untuk mengirim email
Route::post('/datapenyewa/{id}/send-email', [PenyewaController::class, 'sendEmail'])->name('send.email');

Route::get('/indekos/{indekosId}/datapenyewa', [PenyewaController::class, 'index'])->name('datapenyewa.index');
Route::post('/indekos/{indekosId}/datapenyewa/{id}/send-email', [PenyewaController::class, 'sendEmail'])->name('send.email');

Route::post('/pemasukan/update/{id}', [PaymentController::class, 'update'])->name('pemasukan.update');

Route::post('/csp-report', [CspReportController::class, 'handleReport']);

Route::get('/sitemap.xml', 'SitemapController@index')->middleware('csp');
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index']);

Route::get('/riwayat-pengaduan/export', [PengaduanController::class, 'export'])->name('riwayat.pengaduan.export');

// Route untuk Midtrans callback
Route::match(['get', 'post'], '/payment/finish', [PaymentController::class, 'handleFinish'])->name('midtrans.finish');
Route::match(['get', 'post'], '/payment/unfinish', [PaymentController::class, 'handleUnfinish'])->name('midtrans.unfinish');
Route::match(['get', 'post'], '/payment/error', [PaymentController::class, 'handleError'])->name('midtrans.error');
Route::post('/api/midtrans/notification', [PaymentController::class, 'handleNotification'])->name('midtrans.notification');

// Route untuk callback Midtrans
Route::get('/pembayaran/status', [PaymentController::class, 'paymentStatus'])->name('payment.status');
Route::get('/pembayaran/error', [PaymentController::class, 'paymentError'])->name('payment.error');
Route::get('/pembayaran/pending', [PaymentController::class, 'paymentPending'])->name('payment.pending');

// Route untuk verifikasi pembayaran
Route::get('/api/verify-payment/{paymentId}', [PaymentController::class, 'verifyPaymentData']);

Route::delete('/pengaduan/{id}', [PengaduanController::class, 'destroy'])->name('pengaduan.destroy');














