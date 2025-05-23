<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlamatToIndekosTable extends Migration
{
    public function up()
    {
        Schema::table('indekos', function (Blueprint $table) {
            $table->string('alamat')->nullable()->after('nama'); // Menambahkan kolom alamat
        });
    }

    public function down()
    {
        Schema::table('indekos', function (Blueprint $table) {
            $table->dropColumn('alamat');
        });
    }
};
