<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->string('metode_pembayaran')->after('total_harga')->default('cash');
            $table->foreignId('user_id')->after('tanggal_penjualan')
                  ->constrained('users')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['metode_pembayaran', 'user_id']);
        });
    }
};