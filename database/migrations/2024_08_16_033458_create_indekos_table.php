<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndekosTable extends Migration
{
    public function up()
    {
        Schema::create('indekos', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('jumlah_kamar');
            $table->integer('jumlah_penghuni');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('indekos');
    }
}