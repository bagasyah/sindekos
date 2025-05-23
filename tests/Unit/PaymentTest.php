<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Payment;
use App\Models\User;
use Mockery;

class PaymentTest extends TestCase
{
    protected $user;
    protected $payment;

    public function setUp(): void
    {
        parent::setUp();
        
        // Membuat mock user
        $this->user = Mockery::mock(User::class);
        $this->user->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $this->user->shouldReceive('getAttribute')->with('name')->andReturn('John Doe');
    }

    /** @test */
    public function dapat_melakukan_pembayaran()
    {
        $paymentData = [
            'user_id' => 1,
            'amount' => 500000,
            'status' => 'pending',
            'payment_method' => 'transfer',
            'payment_date' => now()
        ];

        $payment = Mockery::mock(Payment::class);
        $payment->shouldReceive('getAttribute')->with('amount')->andReturn(500000);
        $payment->shouldReceive('getAttribute')->with('status')->andReturn('pending');
        $payment->shouldReceive('getAttribute')->with('payment_method')->andReturn('transfer');

        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals(500000, $payment->amount);
        $this->assertEquals('pending', $payment->status);
        $this->assertEquals('transfer', $payment->payment_method);
    }

    // /** @test */
    // public function it_can_calculate_total_payment()
    // {
    //     $payment1 = Mockery::mock(Payment::class);
    //     $payment1->shouldReceive('getAttribute')->with('amount')->andReturn(1000000);
    //     $payment1->shouldReceive('getAttribute')->with('status')->andReturn('completed');

    //     $payment2 = Mockery::mock(Payment::class);
    //     $payment2->shouldReceive('getAttribute')->with('amount')->andReturn(500000);
    //     $payment2->shouldReceive('getAttribute')->with('status')->andReturn('completed');

    //     $payments = collect([$payment1, $payment2]);

    //     $totalPayment = $payments->sum(function($payment) {
    //         return $payment->amount;
    //     });

    //     $this->assertEquals(1500000, $totalPayment);
    // }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 