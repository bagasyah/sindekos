<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Kolom ID
            $table->date('tanggal_bayar'); // Kolom Tanggal Bayar
            $table->date('batas_pembayaran'); // Kolom Batas Pembayaran
            $table->string('status'); // Kolom Status
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
