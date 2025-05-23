<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveJumlahKamarAndJumlahPenghuniFromIndekosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indekos', function (Blueprint $table) {
            $table->dropColumn('jumlah_kamar');
            $table->dropColumn('jumlah_penghuni');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indekos', function (Blueprint $table) {
            $table->integer('jumlah_kamar')->default(0);
            $table->integer('jumlah_penghuni')->default(0);
        });
    }
}
