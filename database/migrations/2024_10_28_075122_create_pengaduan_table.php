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
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id(); // ID pengaduan
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke tabel users
            $table->foreignId('kamar_id')->constrained()->onDelete('cascade'); // Relasi ke tabel kamars
            $table->date('tanggal_pelaporan'); // Tanggal pelaporan
            $table->string('masalah'); // Deskripsi masalah
            $table->string('status')->default('Pending'); // Status pengaduan
            $table->string('foto')->nullable(); // Foto (jika ada)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
