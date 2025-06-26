<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeleteObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua data dari tabel obats
        // PERHATIAN: Ini akan menghapus semua data obat tanpa mempertimbangkan relasi
        // Jika ada relasi dengan tabel lain, ini bisa menyebabkan error foreign key constraint
        $this->command->info('Menghapus semua data obat...');
        
        // Cek apakah ada relasi dengan tabel lain
        $obatsWithRelations = DB::table('pembelian_details')->pluck('obat_id')
            ->union(DB::table('penjualan_details')->pluck('obat_id'))
            ->union(DB::table('stock_notifications')->pluck('obat_id'))
            ->toArray();
            
        if (count($obatsWithRelations) > 0) {
            $this->command->warn('PERINGATAN: Ditemukan ' . count($obatsWithRelations) . ' obat yang memiliki relasi dengan tabel lain.');
            $this->command->warn('Menghapus obat-obat ini dapat menyebabkan error foreign key constraint.');
            
            if ($this->command->confirm('Apakah Anda ingin melanjutkan menghapus obat yang tidak memiliki relasi saja?', true)) {
                // Hapus obat yang tidak memiliki relasi
                $deleted = DB::table('obats')->whereNotIn('id', $obatsWithRelations)->delete();
                $this->command->info($deleted . ' data obat berhasil dihapus.');
                $this->command->info(count($obatsWithRelations) . ' data obat tidak dihapus karena memiliki relasi.');
            } else if ($this->command->confirm('Apakah Anda ingin menghapus SEMUA data obat (termasuk yang memiliki relasi)?', false)) {
                // Nonaktifkan foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                
                // Hapus semua data dari tabel obats
                $deleted = DB::table('obats')->delete();
                
                // Aktifkan kembali foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                
                $this->command->info($deleted . ' data obat berhasil dihapus.');
                $this->command->warn('PERINGATAN: Foreign key constraints dinonaktifkan sementara untuk menghapus data.');
                $this->command->warn('Hal ini dapat menyebabkan inkonsistensi data jika ada tabel lain yang mereferensikan data obat.');
            } else {
                $this->command->info('Operasi dibatalkan. Tidak ada data yang dihapus.');
            }
        } else {
            // Tidak ada relasi, aman untuk menghapus semua data
            $deleted = DB::table('obats')->delete();
            $this->command->info($deleted . ' data obat berhasil dihapus.');
        }
    }
}