<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            // Tambahkan kolom nama_dokter setelah nama_pelanggan
            $table->string('nama_dokter')->nullable()->after('nama_pelanggan');
            
            // Tambahkan kolom nomor_resep setelah nama_dokter
            $table->string('nomor_resep')->nullable()->after('nama_dokter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropColumn(['nama_dokter', 'nomor_resep']);
        });
    }
};