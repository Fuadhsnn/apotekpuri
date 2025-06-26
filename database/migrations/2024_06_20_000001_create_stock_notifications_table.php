<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('stock_notifications')) {
            Schema::create('stock_notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('obat_id');
                $table->integer('current_stock');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }
};
