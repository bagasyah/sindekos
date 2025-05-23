<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Checkout;
use Illuminate\Http\Request;
use PDF;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans di constructor
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
        
        // Tambahkan konfigurasi notifikasi
        Config::$overrideNotifUrl = config('app.url') . '/api/midtrans/notification';
        Config::$appendNotifUrl = '';
    }

    public function index()
    {
        $payments = Payment::where('user_id', auth()->id())->get();
        return view('user.pembayaran-user', compact('payments'));
    }

    public function checkout(Request $request)
    {
        \Log::info('Checkout dimulai', ['request' => $request->all()]);
        
        try {
            // Validasi input
            $request->validate([
                'payment_id' => 'required|exists:payments,id'
            ], [
                'payment_id.required' => 'ID pembayaran tidak ditemukan',
                'payment_id.exists' => 'Data pembayaran tidak valid'
            ]);

            $payment = Payment::with('user')->findOrFail($request->payment_id);
            
            if (!$payment->user) {
                throw new \Exception('Data user tidak ditemukan');
            }

            if (!$payment->price || $payment->price <= 0) {
                throw new \Exception('Jumlah pembayaran tidak valid');
            }

            \Log::info('Data pembayaran ditemukan', [
                'payment_id' => $payment->id,
                'user_id' => $payment->user_id,
                'amount' => $payment->price
            ]);

            // Generate order ID yang unik
            $orderId = 'ORDER-' . $payment->id . '-' . time();

            // Set parameter untuk Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $payment->price,
                ],
                'customer_details' => [
                    'first_name' => $payment->user->name,
                    'email' => $payment->user->email,
                ],
                'callbacks' => [
                    'finish' => url('/api/midtrans/finish'),
                    'unfinish' => url('/api/midtrans/unfinish'),
                    'error' => url('/api/midtrans/error'),
                    'notification' => url('/api/midtrans/notification')
                ]
            ];

            \Log::info('Mencoba mendapatkan snap token', ['params' => $params]);

            try {
                $snapToken = Snap::getSnapToken($params);
                \Log::info('Snap token berhasil didapat', ['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                \Log::error('Gagal mendapatkan snap token', [
                    'error' => $e->getMessage(),
                    'params' => $params
                ]);
                throw new \Exception('Gagal membuat transaksi: ' . $e->getMessage());
            }

            // Update data pembayaran dengan order_id dan snap_token
            try {
                DB::beginTransaction();

                $updateResult = DB::table('payments')
                    ->where('id', $payment->id)
                    ->where('user_id', $payment->user_id)
                    ->update([
                        'order_id' => $orderId,
                        'snap_token' => $snapToken,
                        'status' => 'Dalam Proses',
                        'updated_at' => now()
                    ]);

                if (!$updateResult) {
                    throw new \Exception('Gagal mengupdate data pembayaran');
                }

                DB::commit();

                \Log::info('Data pembayaran berhasil diupdate', [
                    'payment_id' => $payment->id,
                    'order_id' => $orderId,
                    'snap_token' => $snapToken
                ]);

                return response()->json([
                    'success' => true,
                    'snap_token' => $snapToken,
                    'order_id' => $orderId
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error saat update payment', [
                    'error' => $e->getMessage(),
                    'payment_id' => $payment->id
                ]);
                throw new \Exception('Gagal menyimpan data pembayaran: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            \Log::error('Error dalam proses checkout', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function continuePayment(Request $request)
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);

        $payment = Payment::find($request->payment_id);

        if (!$payment) {
            return response()->json(['error' => 'Pembayaran tidak ditemukan'], 404);
        }

        // Generate order_id dengan format yang sama seperti di checkout
        $orderId = 'ORDER-' . $payment->id . '-' . time();

        // Buat parameter transaksi
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $payment->price,
            ],
            'customer_details' => [
                'first_name' => $payment->user->name,
                'email' => $payment->user->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            // Update payment dengan snap_token dan order_id
            $payment->update([
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'status' => 'Dalam Proses'
            ]);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            \Log::error('Continue Payment Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function updatePaymentStatus(Request $request)
    {
        try {
            $payment = Payment::findOrFail($request->payment_id);
            
            $updated = $payment->update([
                'status' => $request->status
            ]);

            if (!$updated) {
                throw new \Exception('Gagal mengupdate status pembayaran');
            }

            \Log::info('Payment status updated', [
                'payment_id' => $payment->id,
                'new_status' => $request->status
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Update Status Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function checkPaymentStatus($paymentId)
    {
        try {
            $payment = Payment::findOrFail($paymentId);
            return response()->json(['status' => $payment->status]);
        } catch (\Exception $e) {
            \Log::error('Check Status Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string', // Validasi input status
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update([
            'status' => $request->input('status'), // Hanya memperbarui status
        ]);

        // Pastikan bahwa Payment memiliki kolom indekos_id
        $indekosId = $payment->indekos_id;

        if (!$indekosId) {
            return redirect()->back()->with('error', 'Indekos ID tidak ditemukan.');
        }

        return redirect()->route('pemasukan.index', ['indekosId' => $indekosId])->with('success', 'Status pembayaran berhasil diperbarui');
    }

    public function downloadPdf($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            $pdf = PDF::loadView('pdf.pembayaran', compact('payment'));
            return $pdf->download('Kwitansi Pembayaran' . $payment->id . '.pdf');
        } catch (\Exception $e) {
            \Log::error('PDF Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengunduh PDF');
        }
    }

    public function getPaymentDetail($id)
    {
        $payment = Payment::findOrFail($id);
        return response()->json([
            'tanggal_bayar' => \Carbon\Carbon::parse($payment->tanggal_bayar)->format('d-m-Y'),
            'status' => $payment->status,
            'price' => number_format($payment->price, 0, ',', '.')
        ]);
    }

    public function handleFinish(Request $request)
    {
        \Log::info('Payment Finish', ['request' => $request->all()]);
        
        try {
            $orderId = $request->order_id;
            $payment = Payment::where('order_id', $orderId)->first();
            
            if ($payment) {
                $payment->update([
                    'status' => 'Selesai',
                    'updated_at' => now()
                ]);
                
                \Log::info('Payment status updated to Selesai', ['payment' => $payment->toArray()]);
                return redirect()->route('user.pembayaran')->with('success', 'Pembayaran berhasil');
            }
            
            return redirect()->route('user.pembayaran')->with('warning', 'Status pembayaran tidak ditemukan');
        } catch (\Exception $e) {
            \Log::error('Error in handleFinish', ['error' => $e->getMessage()]);
            return redirect()->route('user.pembayaran')->with('error', 'Terjadi kesalahan saat memproses pembayaran');
        }
    }

    public function handleUnfinish(Request $request)
    {
        \Log::info('Payment Unfinish', ['request' => $request->all()]);
        
        try {
            $orderId = $request->order_id;
            $payment = Payment::where('order_id', $orderId)->first();
            
            if ($payment) {
                $payment->update([
                    'status' => 'Dalam Proses',
                    'updated_at' => now()
                ]);
            }
            
            return redirect()->route('user.pembayaran')->with('warning', 'Pembayaran belum selesai');
        } catch (\Exception $e) {
            \Log::error('Error in handleUnfinish', ['error' => $e->getMessage()]);
            return redirect()->route('user.pembayaran')->with('error', 'Terjadi kesalahan');
        }
    }

    public function handleError(Request $request)
    {
        \Log::error('Payment Error', ['request' => $request->all()]);
        
        try {
            $orderId = $request->order_id;
            $payment = Payment::where('order_id', $orderId)->first();
            
            if ($payment) {
                $payment->update([
                    'status' => 'Belum Dibayar',
                    'updated_at' => now()
                ]);
            }
            
            return redirect()->route('user.pembayaran')->with('error', 'Pembayaran gagal');
        } catch (\Exception $e) {
            \Log::error('Error in handleError', ['error' => $e->getMessage()]);
            return redirect()->route('user.pembayaran')->with('error', 'Terjadi kesalahan');
        }
    }

    public function handleNotification(Request $request)
    {
        \Log::info('Handling Midtrans notification', ['request' => $request->all()]);

        try {
            $notif = new \Midtrans\Notification();
            
            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $orderId = $notif->order_id;
            $fraudStatus = $notif->fraud_status;
            $signatureKey = $notif->signature_key;

            // Validasi signature key
            $expectedSignatureKey = hash('sha512', 
                $orderId . 
                $notif->status_code . 
                $notif->gross_amount . 
                config('midtrans.server_key')
            );

            if ($signatureKey !== $expectedSignatureKey) {
                \Log::error('Invalid signature key', [
                    'received' => $signatureKey,
                    'expected' => $expectedSignatureKey
                ]);
                return response()->json(['error' => 'Invalid signature'], 403);
            }

            \Log::info('Processing notification', [
                'order_id' => $orderId,
                'transaction' => $transaction,
                'type' => $type,
                'fraud' => $fraudStatus
            ]);

            $payment = Payment::where('order_id', $orderId)->first();

            if (!$payment) {
                \Log::error('Payment not found', ['order_id' => $orderId]);
                return response()->json(['message' => 'Payment not found'], 404);
            }

            DB::beginTransaction();
            try {
                $newStatus = 'Belum Dibayar';

                if ($transaction == 'capture') {
                    if ($fraudStatus == 'challenge') {
                        $newStatus = 'Dalam Proses';
                    } else if ($fraudStatus == 'accept') {
                        $newStatus = 'Selesai';
                    }
                } else if ($transaction == 'settlement') {
                    $newStatus = 'Selesai';
                } else if ($transaction == 'pending') {
                    $newStatus = 'Dalam Proses';
                } else if (in_array($transaction, ['deny', 'expire', 'cancel'])) {
                    $newStatus = 'Belum Dibayar';
                }

                $payment->update([
                    'status' => $newStatus,
                    'updated_at' => now()
                ]);

                DB::commit();

                \Log::info('Payment status updated successfully', [
                    'payment_id' => $payment->id,
                    'new_status' => $newStatus,
                    'transaction' => $transaction
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Notification handled successfully'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error updating payment status', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Error processing notification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Error processing notification: ' . $e->getMessage()
            ], 500);
        }
    }

    public function paymentStatus(Request $request)
    {
        \Log::info('Payment Status Callback', ['request' => $request->all()]);
        return redirect()->route('user.pembayaran')->with('success', 'Pembayaran berhasil diproses');
    }

    public function paymentError(Request $request)
    {
        \Log::error('Payment Error Callback', ['request' => $request->all()]);
        return redirect()->route('user.pembayaran')->with('error', 'Terjadi kesalahan dalam proses pembayaran');
    }

    public function paymentPending(Request $request)
    {
        \Log::info('Payment Pending Callback', ['request' => $request->all()]);
        return redirect()->route('user.pembayaran')->with('warning', 'Pembayaran sedang diproses');
    }

    public function verifyPaymentData($paymentId)
    {
        try {
            $payment = Payment::with('user')->findOrFail($paymentId);
            
            \Log::info('Verifikasi data pembayaran', [
                'payment_id' => $payment->id,
                'order_id' => $payment->order_id,
                'snap_token' => $payment->snap_token,
                'status' => $payment->status
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                    'snap_token' => $payment->snap_token,
                    'status' => $payment->status
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saat verifikasi pembayaran', [
                'error' => $e->getMessage(),
                'payment_id' => $paymentId
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi data pembayaran'
            ], 500);
        }
    }
}
