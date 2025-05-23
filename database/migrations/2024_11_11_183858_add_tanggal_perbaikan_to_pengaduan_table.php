<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalPerbaikanToPengaduanTable extends Migration
{
    public function up()
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->date('tanggal_perbaikan')->nullable()->after('tanggal_pelaporan'); // Menambahkan kolom tanggal_perbaikan setelah tanggal_pelaporan
        });
    }

    public function down()
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->dropColumn('tanggal_perbaikan');
        });
    }
}