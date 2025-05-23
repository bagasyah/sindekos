<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSnapTokenToPaymentsTable extends Migration
{
    public function up()
{
    Schema::table('payments', function (Blueprint $table) {
        $table->string('snap_token')->nullable(); // Menambahkan kolom snap_token
    });
}

public function down()
{
    Schema::table('payments', function (Blueprint $table) {
        $table->dropColumn('snap_token'); // Menghapus kolom snap_token
    });
}
}
