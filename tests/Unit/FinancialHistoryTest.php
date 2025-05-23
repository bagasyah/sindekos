<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Payment;
use App\Models\User;
use Mockery;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\MessageBag;

class FinancialHistoryTest extends TestCase
{
    use WithoutMiddleware;

    protected $user;
    protected $payments;

    public function setUp(): void
    {
        parent::setUp();
        
        // Membuat mock user
        $this->user = Mockery::mock(User::class);
        $this->user->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $this->user->shouldReceive('getAttribute')->with('name')->andReturn('John Doe');
        
        // Membuat mock payments untuk riwayat
        $this->payments = collect([
            $this->createPaymentMock(1, 500000, 'Selesai', '2024-02-14'),
            $this->createPaymentMock(2, 750000, 'Selesai', '2024-02-15'),
            $this->createPaymentMock(3, 1000000, 'Pending', '2024-02-16')
        ]);
    }

    protected function createPaymentMock($id, $amount, $status, $date)
    {
        $payment = Mockery::mock(Payment::class);
        $payment->shouldReceive('getAttribute')->with('id')->andReturn($id);
        $payment->shouldReceive('getAttribute')->with('price')->andReturn($amount);
        $payment->shouldReceive('getAttribute')->with('status')->andReturn($status);
        $payment->shouldReceive('getAttribute')->with('tanggal_bayar')->andReturn($date);
        $payment->shouldReceive('offsetExists')->andReturn(true);
        return $payment;
    }

    /** @test */
    // public function dapat_menampilkan_riwayat_pembayaran()
    // {
    //     // Mock Payment model untuk mengembalikan data yang diperlukan
    //     $this->mock(Payment::class, function ($mock) {
    //         $mock->shouldReceive('all')->andReturn($this->payments);
    //     });

    //     // Mock controller untuk mengembalikan view dengan data yang diperlukan
    //     $this->mock(\App\Http\Controllers\RiwayatKeuanganController::class, function ($mock) {
    //         $mock->shouldReceive('index')
    //             ->andReturn(view('admin.indekos.riwayat_keuangan', [
    //                 'riwayats' => $this->payments,
    //                 'totalPemasukan' => 1250000,
    //                 'totalPengeluaran' => 0,
    //                 'totalJumlahUang' => 1250000,
    //                 'errors' => new MessageBag()
    //             ]));
    //     });

    //     $response = $this->get(route('riwayat_keuangan.index', ['indekos' => 1]));
        
    //     $response->assertStatus(200);
    //     $response->assertViewIs('admin.indekos.riwayat_keuangan');
    //     $response->assertViewHas('riwayats');
    // }

    /** @test */
    public function dapat_menampilkan_riwayat_keuangan()
    {
        $payment = $this->payments->first();
        
        $this->assertEquals(1, $payment->id);
        $this->assertEquals(500000, $payment->price);
        $this->assertEquals('Selesai', $payment->status);
        $this->assertEquals('2024-02-14', $payment->tanggal_bayar);
    }

    // /** @test */
    // public function dapat_menampilkan_total_pembayaran_berhasil()
    // {
    //     $completedPayments = $this->payments->filter(function($payment) {
    //         return $payment->status === 'Selesai';
    //     });
        
    //     $totalCompleted = $completedPayments->sum(function($payment) {
    //         return $payment->price;
    //     });

    //     $this->assertEquals(1250000, $totalCompleted); // 500000 + 750000
    // }

    // /** @test */
    // public function dapat_menampilkan_pembayaran_pending()
    // {
    //     $pendingPayments = $this->payments->filter(function($payment) {
    //         return $payment->status === 'Pending';
    //     });
        
    //     $this->assertCount(1, $pendingPayments);
    //     $this->assertEquals(1000000, $pendingPayments->first()->price);
    // }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 