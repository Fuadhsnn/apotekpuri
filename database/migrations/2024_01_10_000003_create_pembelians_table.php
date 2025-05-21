<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_faktur')->unique();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict');
            $table->date('tanggal_pembelian');
            $table->decimal('total_harga', 12, 2);
            $table->enum('status_pembayaran', ['lunas', 'belum_lunas']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('pembelian_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelians')->onDelete('cascade');
            $table->foreignId('obat_id')->constrained('obats')->onDelete('restrict');
            $table->integer('jumlah');
            $table->decimal('harga_beli', 10, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembelian_details');
        Schema::dropIfExists('pembelians');
    }
};