<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('obats', function (Blueprint $table) {
            $table->enum('jenis_obat', ['obat_bebas', 'obat_bebas_terbatas', 'obat_keras', 'narkotika'])
                  ->after('kategori')
                  ->default('obat_bebas');
        });
    }

    public function down()
    {
        Schema::table('obats', function (Blueprint $table) {
            $table->dropColumn('jenis_obat');
        });
    }
};