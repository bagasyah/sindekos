<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kamars', function (Blueprint $table) {
            $table->id();
            $table->integer('no_kamar')->nullable();
            $table->enum('status',['Terisi', 'Tidak Terisi'])->default('Tidak Terisi');
            $table->integer('harga')->nullable();
            $table->unsignedBigInteger('indekos_id')->nullable();
            $table->timestamps();
            $table->foreign('indekos_id')->references('id')->on('indekos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamars');
    }
};
