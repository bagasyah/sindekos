<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Payment;

class CheckoutController extends Controller
{
    public function getSnapToken(Request $request)
    {
        // Set konfigurasi Midtrans dengan server key langsung
        Config::$serverKey = 'SB-Mid-server-vqQ5YchRR_ImQZrz6V9axekq'; // Ganti dengan server key Anda
        Config::$isProduction = false; // Set ke true jika di lingkungan produksi
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Ambil pembayaran berdasarkan ID
        $payment = Payment::find($request->payment_id);

        if (!$payment) {
            return response()->json(['error' => 'Pembayaran tidak ditemukan'], 404);
        }

        // Buat parameter transaksi
        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $payment->price,
            ],
            'customer_details' => [
                'first_name' => $payment->user->name,
                'email' => $payment->user->email,
            ],
        ];

        try {
            // Dapatkan Snap Token
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function continuePayment(Request $request)
    {
        $payment = Payment::find($request->payment_id);

        if (!$payment) {
            return response()->json(['error' => 'Pembayaran tidak ditemukan'], 404);
        }

        // Set konfigurasi Midtrans
        Config::$serverKey = 'SB-Mid-server-vqQ5YchRR_ImQZrz6V9axekq';
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Buat parameter transaksi
        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $payment->price,
            ],
            'customer_details' => [
                'first_name' => $payment->user->name,
                'email' => $payment->user->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function updatePaymentStatus(Request $request)
    {
        $payment = Payment::find($request->payment_id);

        if (!$payment) {
            return response()->json(['error' => 'Pembayaran tidak ditemukan'], 404);
        }

        // Update status berdasarkan status yang dikirim
        $payment->status = $request->status;
            $payment->save();

        return response()->json(['success' => true]);
    }

    public function checkPaymentStatus($paymentId)
    {
        $payment = Payment::find($paymentId);

        if (!$payment) {
            return response()->json(['error' => 'Pembayaran tidak ditemukan'], 404);
        }

        return response()->json(['status' => $payment->status]);
    }
}
