<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            // Cek apakah kolom sudah ada sebelum menambahkan
            if (!Schema::hasColumn('penjualans', 'metode_pembayaran')) {
                $table->string('metode_pembayaran', 255)->default('tunai')->after('total_harga');
            }

            if (!Schema::hasColumn('penjualans', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('tanggal_penjualan');
            }
        });
    }
};
