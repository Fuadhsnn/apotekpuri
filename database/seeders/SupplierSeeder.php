<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar nama perusahaan farmasi di Indonesia
        $namaPerusahaan = [
            'Kimia Farma', 'Kalbe Farma', 'Sanbe Farma', 'Dexa Medica', 'Pharos Indonesia',
            'Tempo Scan Pacific', 'Novell Pharmaceutical', 'Bernofarm', 'Soho Global Health', 'Interbat',
            'Darya-Varia Laboratoria', 'Phapros', 'Ikapharmindo Putramas', 'Indofarma', 'Erela',
            'Pyridam Farma', 'Ifars', 'Hexpharm Jaya', 'Gracia Pharmindo', 'Ferron Par Pharmaceuticals'
        ];
        
        // Daftar kota besar di Indonesia
        $kota = [
            'Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang', 
            'Makassar', 'Palembang', 'Yogyakarta', 'Denpasar', 'Balikpapan'
        ];
        
        // Daftar jalan (fiktif)
        $jalan = [
            'Jl. Industri Raya', 'Jl. Farmasi Utama', 'Jl. Kesehatan', 'Jl. Medika', 'Jl. Obat Sejahtera',
            'Jl. Kimia Dasar', 'Jl. Laboratorium', 'Jl. Produksi', 'Jl. Distribusi', 'Jl. Manufaktur'
        ];
        
        // Hapus data supplier yang ada (jika tidak memiliki relasi)
        $suppliersWithRelations = DB::table('pembelians')->pluck('supplier_id')->toArray();
        DB::table('suppliers')->whereNotIn('id', $suppliersWithRelations)->delete();
        
        // Generate 20 data supplier
        $this->command->info('Mulai menyemai 20 data supplier...');
        $bar = $this->command->getOutput()->createProgressBar(20);
        $bar->start();
        
        for ($i = 0; $i < 20; $i++) {
            $namaSupplier = $namaPerusahaan[$i % count($namaPerusahaan)];
            $kotaSupplier = $kota[array_rand($kota)];
            $jalanSupplier = $jalan[array_rand($jalan)];
            $nomorJalan = rand(1, 200);
            
            // Format alamat
            $alamat = "{$jalanSupplier} No. {$nomorJalan}, {$kotaSupplier}";
            
            // Format telepon (08xx-xxxx-xxxx)
            $telepon = '08' . rand(10, 99) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999);
            
            // Format email
            $emailDomain = strtolower(str_replace(' ', '', $namaSupplier)) . '.co.id';
            $email = 'info@' . $emailDomain;
            
            // Keterangan
            $keterangan = "Supplier produk farmasi dan alat kesehatan. Melayani pembelian grosir dan eceran.";
            
            // Simpan supplier ke database
            Supplier::create([
                'nama_supplier' => $namaSupplier,
                'alamat' => $alamat,
                'telepon' => $telepon,
                'email' => $email,
                'keterangan' => $keterangan
            ]);
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->command->info('\n20 data supplier berhasil ditambahkan!');
    }
}