<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveForeignKeyAndIndekosIdFromFasilitasTable extends Migration
{
    public function up()
    {
        Schema::table('fasilitas', function (Blueprint $table) {
            // Hapus foreign key constraint
            $table->dropForeign(['indekos_id']);
            // Hapus kolom indekos_id
            $table->dropColumn('indekos_id');
        });
    }

    public function down()
    {
        Schema::table('fasilitas', function (Blueprint $table) {
            // Tambahkan kembali kolom indekos_id
            $table->unsignedBigInteger('indekos_id')->nullable();
            // Tambahkan kembali foreign key constraint
            $table->foreign('indekos_id')->references('id')->on('indekos')->onDelete('cascade');
        });
    }
}