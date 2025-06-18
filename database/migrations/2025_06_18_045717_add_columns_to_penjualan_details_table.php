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
        Schema::table('penjualan_details', function (Blueprint $table) {
            // Tambahkan kolom harga
            $table->decimal('harga', 10, 2)->after('jumlah');
            
            // Tambahkan kolom keterangan
            $table->text('keterangan')->nullable()->after('subtotal');
            
            // Ubah kolom obat_id menjadi nullable
            $table->foreignId('obat_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualan_details', function (Blueprint $table) {
            $table->dropColumn(['harga', 'keterangan']);
            $table->foreignId('obat_id')->nullable(false)->change();
        });
    }
};
