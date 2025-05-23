<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFasilitasIdTypeInKamarsTable extends Migration
{
    public function up()
    {
        Schema::table('kamars', function (Blueprint $table) {
            $table->string('fasilitas_id')->change();
        });
    }

    public function down()
    {
        Schema::table('kamars', function (Blueprint $table) {
            $table->unsignedBigInteger('fasilitas_id')->change(); // Sesuaikan dengan tipe sebelumnya
        });
    }
}
