<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFasilitasIdFromKamarsTable extends Migration
{
    public function up()
    {
        Schema::table('kamars', function (Blueprint $table) {
            $table->dropForeign(['fasilitas_id']); // Hapus foreign key constraint jika ada
            $table->dropColumn('fasilitas_id'); // Hapus kolom
        });
    }

    public function down()
    {
        Schema::table('kamars', function (Blueprint $table) {
            $table->unsignedBigInteger('fasilitas_id')->nullable(); // Tambahkan kembali kolom
            $table->foreign('fasilitas_id')->references('id')->on('fasilitas'); // Tambahkan kembali foreign key constraint
        });
    }
}
