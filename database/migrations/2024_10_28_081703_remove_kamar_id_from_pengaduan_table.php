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
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->dropForeign(['kamar_id']); // Hapus foreign key jika ada
            $table->dropColumn('kamar_id'); // Hapus kolom kamar_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->foreignId('kamar_id')->constrained()->onDelete('cascade'); // Tambahkan kembali kolom kamar_id
        });
    }
};
