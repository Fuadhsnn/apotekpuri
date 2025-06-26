<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ObatSeeder extends Seeder
{
    public function run()
    {
        $currentYear = date('y');
        $obatData = [
            // 1. Analgesik
            [
                'kode_obat' => 'ANFAR-' . $currentYear . '-001',
                'nama_obat' => 'Paracetamol 500mg',
                'deskripsi' => 'Pereda nyeri dan penurun demam',
                'kategori' => 'analgesik',
                'jenis_obat' => 'obat_bebas',
                'stok' => 500,
                'harga_beli' => 2000,
                'harga_jual' => 3500,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 2. Analgesik
            [
                'kode_obat' => 'ANMED-' . $currentYear . '-002',
                'nama_obat' => 'Ibuprofen 200mg',
                'deskripsi' => 'Antiinflamasi nonsteroid',
                'kategori' => 'analgesik',
                'jenis_obat' => 'obat_bebas',
                'stok' => 450,
                'harga_beli' => 3000,
                'harga_jual' => 5000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(3)->format('Y-m-d')
            ],

            // 3. Antibiotik
            [
                'kode_obat' => 'ABKAL-' . $currentYear . '-003',
                'nama_obat' => 'Amoxicillin 500mg',
                'deskripsi' => 'Antibiotik untuk infeksi bakteri',
                'kategori' => 'antibiotik',
                'jenis_obat' => 'obat_keras',
                'stok' => 300,
                'harga_beli' => 5000,
                'harga_jual' => 8500,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 4. Antibiotik
            [
                'kode_obat' => 'ABTEM-' . $currentYear . '-004',
                'nama_obat' => 'Cefixime 200mg',
                'deskripsi' => 'Antibiotik spektrum luas',
                'kategori' => 'antibiotik',
                'jenis_obat' => 'obat_keras',
                'stok' => 250,
                'harga_beli' => 8000,
                'harga_jual' => 12000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->addMonths(6)->format('Y-m-d')
            ],

            // 5. Vitamin
            [
                'kode_obat' => 'VIKIM-' . $currentYear . '-005',
                'nama_obat' => 'Vitamin C 500mg',
                'deskripsi' => 'Suplemen vitamin C',
                'kategori' => 'vitamin',
                'jenis_obat' => 'obat_bebas',
                'stok' => 600,
                'harga_beli' => 3000,
                'harga_jual' => 5000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(3)->format('Y-m-d')
            ],

            // 6. Vitamin
            [
                'kode_obat' => 'VIDEX-' . $currentYear . '-006',
                'nama_obat' => 'Vitamin B Complex',
                'deskripsi' => 'Kombinasi vitamin B',
                'kategori' => 'vitamin',
                'jenis_obat' => 'obat_bebas',
                'stok' => 400,
                'harga_beli' => 5000,
                'harga_jual' => 8000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 7. Topikal
            [
                'kode_obat' => 'TOINT-' . $currentYear . '-007',
                'nama_obat' => 'Clotrimazole 1% Krim',
                'deskripsi' => 'Antijamur untuk kulit',
                'kategori' => 'topikal',
                'jenis_obat' => 'obat_bebas',
                'stok' => 350,
                'harga_beli' => 8000,
                'harga_jual' => 12000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 8. Topikal
            [
                'kode_obat' => 'TOGRA-' . $currentYear . '-008',
                'nama_obat' => 'Gentamicin 0.1% Krim',
                'deskripsi' => 'Antibiotik topikal',
                'kategori' => 'topikal',
                'jenis_obat' => 'obat_bebas_terbatas',
                'stok' => 280,
                'harga_beli' => 10000,
                'harga_jual' => 15000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->addMonths(6)->format('Y-m-d')
            ],

            // 9. Gastrointestinal
            [
                'kode_obat' => 'GASAN-' . $currentYear . '-009',
                'nama_obat' => 'Omeprazole 20mg',
                'deskripsi' => 'Obat maag',
                'kategori' => 'gastro',
                'jenis_obat' => 'obat_bebas_terbatas',
                'stok' => 400,
                'harga_beli' => 7000,
                'harga_jual' => 11000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 10. Gastrointestinal
            [
                'kode_obat' => 'GAPHA-' . $currentYear . '-010',
                'nama_obat' => 'Loperamide 2mg',
                'deskripsi' => 'Obat diare',
                'kategori' => 'gastro',
                'jenis_obat' => 'obat_bebas',
                'stok' => 320,
                'harga_beli' => 5000,
                'harga_jual' => 8000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->addMonths(9)->format('Y-m-d')
            ],

            // 11. Respirasi
            [
                'kode_obat' => 'REBER-' . $currentYear . '-011',
                'nama_obat' => 'Salbutamol 2mg',
                'deskripsi' => 'Obat asma',
                'kategori' => 'respirasi',
                'jenis_obat' => 'obat_keras',
                'stok' => 200,
                'harga_beli' => 4500,
                'harga_jual' => 7500,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->format('Y-m-d')
            ],

            // 12. Respirasi
            [
                'kode_obat' => 'REHEX-' . $currentYear . '-012',
                'nama_obat' => 'CTM 4mg',
                'deskripsi' => 'Antihistamin untuk alergi',
                'kategori' => 'respirasi',
                'jenis_obat' => 'obat_bebas_terbatas',
                'stok' => 380,
                'harga_beli' => 2000,
                'harga_jual' => 4000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 13. Herbal
            [
                'kode_obat' => 'HESOH-' . $currentYear . '-013',
                'nama_obat' => 'Tolak Angin Cair',
                'deskripsi' => 'Obat herbal masuk angin',
                'kategori' => 'herbal',
                'jenis_obat' => 'obat_bebas',
                'stok' => 500,
                'harga_beli' => 10000,
                'harga_jual' => 15000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 14. Herbal
            [
                'kode_obat' => 'HEPYR-' . $currentYear . '-014',
                'nama_obat' => 'Antangin JRG',
                'deskripsi' => 'Herbal untuk stamina',
                'kategori' => 'herbal',
                'jenis_obat' => 'obat_bebas',
                'stok' => 420,
                'harga_beli' => 12000,
                'harga_jual' => 18000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(3)->format('Y-m-d')
            ],

            // 15. Suplemen
            [
                'kode_obat' => 'SUIKA-' . $currentYear . '-015',
                'nama_obat' => 'Zinc 20mg',
                'deskripsi' => 'Suplemen zinc',
                'kategori' => 'suplemen',
                'jenis_obat' => 'obat_bebas',
                'stok' => 350,
                'harga_beli' => 12000,
                'harga_jual' => 18000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 16. Suplemen
            [
                'kode_obat' => 'SUERE-' . $currentYear . '-016',
                'nama_obat' => 'Calcium 500mg',
                'deskripsi' => 'Suplemen kalsium',
                'kategori' => 'suplemen',
                'jenis_obat' => 'obat_bebas',
                'stok' => 300,
                'harga_beli' => 15000,
                'harga_jual' => 22000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 17. Kardio
            [
                'kode_obat' => 'KANOV-' . $currentYear . '-017',
                'nama_obat' => 'Amlodipine 5mg',
                'deskripsi' => 'Obat hipertensi',
                'kategori' => 'kardio',
                'jenis_obat' => 'obat_keras',
                'stok' => 180,
                'harga_beli' => 5000,
                'harga_jual' => 8500,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->format('Y-m-d')
            ],

            // 18. Kardio
            [
                'kode_obat' => 'KASAN-' . $currentYear . '-018',
                'nama_obat' => 'Simvastatin 20mg',
                'deskripsi' => 'Obat kolesterol',
                'kategori' => 'kardio',
                'jenis_obat' => 'obat_keras',
                'stok' => 200,
                'harga_beli' => 8000,
                'harga_jual' => 13000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->addMonths(6)->format('Y-m-d')
            ],

            // 19. Endokrin
            [
                'kode_obat' => 'ENIND-' . $currentYear . '-019',
                'nama_obat' => 'Metformin 500mg',
                'deskripsi' => 'Obat diabetes',
                'kategori' => 'endokrin',
                'jenis_obat' => 'obat_keras',
                'stok' => 250,
                'harga_beli' => 6000,
                'harga_jual' => 10000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 20. Endokrin
            [
                'kode_obat' => 'ENKIM-' . $currentYear . '-020',
                'nama_obat' => 'Glibenclamide 5mg',
                'deskripsi' => 'Obat diabetes tipe 2',
                'kategori' => 'endokrin',
                'jenis_obat' => 'obat_keras',
                'stok' => 220,
                'harga_beli' => 4000,
                'harga_jual' => 7000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->addMonths(9)->format('Y-m-d')
            ],

            // 21. Oftalmologi
            [
                'kode_obat' => 'OFSEH-' . $currentYear . '-021',
                'nama_obat' => 'Tetes Mata Chloramphenicol',
                'deskripsi' => 'Antibiotik mata',
                'kategori' => 'oftalmologi',
                'jenis_obat' => 'obat_keras',
                'stok' => 150,
                'harga_beli' => 8000,
                'harga_jual' => 13000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->format('Y-m-d')
            ],

            // 22. Oftalmologi
            [
                'kode_obat' => 'OFMED-' . $currentYear . '-022',
                'nama_obat' => 'Tetes Mata Artificial Tears',
                'deskripsi' => 'Pelembab mata',
                'kategori' => 'oftalmologi',
                'jenis_obat' => 'obat_bebas',
                'stok' => 300,
                'harga_beli' => 15000,
                'harga_jual' => 22000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 23. Dermatologi
            [
                'kode_obat' => 'DEFAR-' . $currentYear . '-023',
                'nama_obat' => 'Hydrocortisone 1% Krim',
                'deskripsi' => 'Krim untuk gatal-gatal',
                'kategori' => 'dermatologi',
                'jenis_obat' => 'obat_bebas_terbatas',
                'stok' => 280,
                'harga_beli' => 9000,
                'harga_jual' => 14000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->addMonths(6)->format('Y-m-d')
            ],

            // 24. Dermatologi
            [
                'kode_obat' => 'DEKAL-' . $currentYear . '-024',
                'nama_obat' => 'Caladine Lotion',
                'deskripsi' => 'Obat gatal dan iritasi kulit',
                'kategori' => 'dermatologi',
                'jenis_obat' => 'obat_bebas',
                'stok' => 350,
                'harga_beli' => 12000,
                'harga_jual' => 18000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 25. Antiviral
            [
                'kode_obat' => 'AVTEM-' . $currentYear . '-025',
                'nama_obat' => 'Acyclovir 200mg',
                'deskripsi' => 'Obat herpes',
                'kategori' => 'antiviral',
                'jenis_obat' => 'obat_keras',
                'stok' => 180,
                'harga_beli' => 15000,
                'harga_jual' => 22000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->format('Y-m-d')
            ],

            // 26. Analgesik
            [
                'kode_obat' => 'ANBRO-' . $currentYear . '-026',
                'nama_obat' => 'Asam Mefenamat 500mg',
                'deskripsi' => 'Pereda nyeri haid dan sakit gigi',
                'kategori' => 'analgesik',
                'jenis_obat' => 'obat_bebas_terbatas',
                'stok' => 400,
                'harga_beli' => 3500,
                'harga_jual' => 6000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 27. Analgesik
            [
                'kode_obat' => 'ANPON-' . $currentYear . '-027',
                'nama_obat' => 'Diklofenak Sodium 50mg',
                'deskripsi' => 'Antiinflamasi untuk nyeri sendi',
                'kategori' => 'analgesik',
                'jenis_obat' => 'obat_bebas_terbatas',
                'stok' => 320,
                'harga_beli' => 4000,
                'harga_jual' => 7000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->addMonths(6)->format('Y-m-d')
            ],

            // 28. Antibiotik
            [
                'kode_obat' => 'ABFAR-' . $currentYear . '-028',
                'nama_obat' => 'Ciprofloxacin 500mg',
                'deskripsi' => 'Antibiotik fluoroquinolone',
                'kategori' => 'antibiotik',
                'jenis_obat' => 'obat_keras',
                'stok' => 280,
                'harga_beli' => 7000,
                'harga_jual' => 11000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 29. Antibiotik
            [
                'kode_obat' => 'ABKIM-' . $currentYear . '-029',
                'nama_obat' => 'Azithromycin 500mg',
                'deskripsi' => 'Antibiotik makrolida',
                'kategori' => 'antibiotik',
                'jenis_obat' => 'obat_keras',
                'stok' => 220,
                'harga_beli' => 9000,
                'harga_jual' => 14000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->format('Y-m-d')
            ],

            // 30. Vitamin
            [
                'kode_obat' => 'VIFAR-' . $currentYear . '-030',
                'nama_obat' => 'Vitamin D3 1000 IU',
                'deskripsi' => 'Suplemen vitamin D',
                'kategori' => 'vitamin',
                'jenis_obat' => 'obat_bebas',
                'stok' => 450,
                'harga_beli' => 8000,
                'harga_jual' => 12000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(3)->format('Y-m-d')
            ],

            // 31. Vitamin
            [
                'kode_obat' => 'VIMED-' . $currentYear . '-031',
                'nama_obat' => 'Multivitamin + Mineral',
                'deskripsi' => 'Kombinasi vitamin dan mineral',
                'kategori' => 'vitamin',
                'jenis_obat' => 'obat_bebas',
                'stok' => 500,
                'harga_beli' => 10000,
                'harga_jual' => 15000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 32. Topikal
            [
                'kode_obat' => 'TOFAR-' . $currentYear . '-032',
                'nama_obat' => 'Miconazole 2% Krim',
                'deskripsi' => 'Antijamur untuk panu dan kurap',
                'kategori' => 'topikal',
                'jenis_obat' => 'obat_bebas',
                'stok' => 380,
                'harga_beli' => 9000,
                'harga_jual' => 14000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 33. Topikal
            [
                'kode_obat' => 'TOKIM-' . $currentYear . '-033',
                'nama_obat' => 'Betamethasone 0.1% Krim',
                'deskripsi' => 'Kortikosteroid topikal',
                'kategori' => 'topikal',
                'jenis_obat' => 'obat_keras',
                'stok' => 250,
                'harga_beli' => 11000,
                'harga_jual' => 17000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->format('Y-m-d')
            ],

            // 34. Gastrointestinal
            [
                'kode_obat' => 'GAFAR-' . $currentYear . '-034',
                'nama_obat' => 'Ranitidine 150mg',
                'deskripsi' => 'Antasida dan antiulkus',
                'kategori' => 'gastro',
                'jenis_obat' => 'obat_bebas_terbatas',
                'stok' => 350,
                'harga_beli' => 6000,
                'harga_jual' => 10000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 35. Gastrointestinal
            [
                'kode_obat' => 'GAMED-' . $currentYear . '-035',
                'nama_obat' => 'Domperidone 10mg',
                'deskripsi' => 'Obat mual dan muntah',
                'kategori' => 'gastro',
                'jenis_obat' => 'obat_bebas_terbatas',
                'stok' => 300,
                'harga_beli' => 5000,
                'harga_jual' => 8000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->addMonths(6)->format('Y-m-d')
            ],

            // 36. Respirasi
            [
                'kode_obat' => 'REFAR-' . $currentYear . '-036',
                'nama_obat' => 'Ambroxol 30mg',
                'deskripsi' => 'Ekspektoran untuk batuk berdahak',
                'kategori' => 'respirasi',
                'jenis_obat' => 'obat_bebas',
                'stok' => 420,
                'harga_beli' => 4000,
                'harga_jual' => 7000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 37. Respirasi
            [
                'kode_obat' => 'REKIM-' . $currentYear . '-037',
                'nama_obat' => 'Dextromethorphan 15mg',
                'deskripsi' => 'Antitusif untuk batuk kering',
                'kategori' => 'respirasi',
                'jenis_obat' => 'obat_bebas',
                'stok' => 380,
                'harga_beli' => 3000,
                'harga_jual' => 5000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->addMonths(9)->format('Y-m-d')
            ],

            // 38. Herbal
            [
                'kode_obat' => 'HEFAR-' . $currentYear . '-038',
                'nama_obat' => 'Kapsul Temulawak',
                'deskripsi' => 'Herbal untuk nafsu makan',
                'kategori' => 'herbal',
                'jenis_obat' => 'obat_bebas',
                'stok' => 450,
                'harga_beli' => 8000,
                'harga_jual' => 12000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 39. Herbal
            [
                'kode_obat' => 'HEMED-' . $currentYear . '-039',
                'nama_obat' => 'Madu TJ Murni 250ml',
                'deskripsi' => 'Madu herbal untuk stamina',
                'kategori' => 'herbal',
                'jenis_obat' => 'obat_bebas',
                'stok' => 300,
                'harga_beli' => 25000,
                'harga_jual' => 35000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(3)->format('Y-m-d')
            ],

            // 40. Suplemen
            [
                'kode_obat' => 'SUFAR-' . $currentYear . '-040',
                'nama_obat' => 'Omega-3 1000mg',
                'deskripsi' => 'Suplemen asam lemak esensial',
                'kategori' => 'suplemen',
                'jenis_obat' => 'obat_bebas',
                'stok' => 280,
                'harga_beli' => 20000,
                'harga_jual' => 30000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 41. Suplemen
            [
                'kode_obat' => 'SUKIM-' . $currentYear . '-041',
                'nama_obat' => 'Magnesium 250mg',
                'deskripsi' => 'Suplemen magnesium',
                'kategori' => 'suplemen',
                'jenis_obat' => 'obat_bebas',
                'stok' => 320,
                'harga_beli' => 15000,
                'harga_jual' => 22000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->addMonths(6)->format('Y-m-d')
            ],

            // 42. Kardio
            [
                'kode_obat' => 'KAFAR-' . $currentYear . '-042',
                'nama_obat' => 'Captopril 25mg',
                'deskripsi' => 'Obat hipertensi golongan ACE inhibitor',
                'kategori' => 'kardio',
                'jenis_obat' => 'obat_keras',
                'stok' => 200,
                'harga_beli' => 4000,
                'harga_jual' => 7000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->format('Y-m-d')
            ],

            // 43. Kardio
            [
                'kode_obat' => 'KAMED-' . $currentYear . '-043',
                'nama_obat' => 'Atorvastatin 20mg',
                'deskripsi' => 'Obat penurun kolesterol',
                'kategori' => 'kardio',
                'jenis_obat' => 'obat_keras',
                'stok' => 180,
                'harga_beli' => 10000,
                'harga_jual' => 16000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(2)->format('Y-m-d')
            ],

            // 44. Endokrin
            [
                'kode_obat' => 'ENFAR-' . $currentYear . '-044',
                'nama_obat' => 'Gliclazide 80mg',
                'deskripsi' => 'Obat diabetes oral',
                'kategori' => 'endokrin',
                'jenis_obat' => 'obat_keras',
                'stok' => 210,
                'harga_beli' => 5000,
                'harga_jual' => 8500,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->addMonths(6)->format('Y-m-d')
            ],

            // 45. Endokrin
            [
                'kode_obat' => 'ENMED-' . $currentYear . '-045',
                'nama_obat' => 'Insulin Regular 100IU/ml',
                'deskripsi' => 'Insulin untuk diabetes tipe 1',
                'kategori' => 'endokrin',
                'jenis_obat' => 'obat_keras',
                'stok' => 150,
                'harga_beli' => 120000,
                'harga_jual' => 150000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->format('Y-m-d')
            ],

            // 46. Oftalmologi
            [
                'kode_obat' => 'OFFAR-' . $currentYear . '-046',
                'nama_obat' => 'Tetes Mata Dexamethasone',
                'deskripsi' => 'Antiradang untuk mata',
                'kategori' => 'oftalmologi',
                'jenis_obat' => 'obat_keras',
                'stok' => 180,
                'harga_beli' => 12000,
                'harga_jual' => 18000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->format('Y-m-d')
            ],

            // 47. Oftalmologi
            [
                'kode_obat' => 'OFKIM-' . $currentYear . '-047',
                'nama_obat' => 'Tetes Mata Cendo Xitrol',
                'deskripsi' => 'Antibiotik dan antiradang mata',
                'kategori' => 'oftalmologi',
                'jenis_obat' => 'obat_keras',
                'stok' => 160,
                'harga_beli' => 15000,
                'harga_jual' => 22000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->addMonths(6)->format('Y-m-d')
            ],

            // 48. Dermatologi
            [
                'kode_obat' => 'DEFAR-' . $currentYear . '-048',
                'nama_obat' => 'Mupirocin 2% Salep',
                'deskripsi' => 'Antibiotik topikal untuk infeksi kulit',
                'kategori' => 'dermatologi',
                'jenis_obat' => 'obat_keras',
                'stok' => 220,
                'harga_beli' => 18000,
                'harga_jual' => 25000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->format('Y-m-d')
            ],

            // 49. Dermatologi
            [
                'kode_obat' => 'DEMED-' . $currentYear . '-049',
                'nama_obat' => 'Scabimite 5% Krim',
                'deskripsi' => 'Obat skabies/kudis',
                'kategori' => 'dermatologi',
                'jenis_obat' => 'obat_keras',
                'stok' => 190,
                'harga_beli' => 20000,
                'harga_jual' => 30000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->addMonths(6)->format('Y-m-d')
            ],

            // 50. Antiviral
            [
                'kode_obat' => 'AVFAR-' . $currentYear . '-050',
                'nama_obat' => 'Oseltamivir 75mg',
                'deskripsi' => 'Obat antivirus influenza',
                'kategori' => 'antiviral',
                'jenis_obat' => 'obat_keras',
                'stok' => 150,
                'harga_beli' => 25000,
                'harga_jual' => 35000,
                'tanggal_kadaluarsa' => Carbon::now()->addYears(1)->format('Y-m-d')
            ]

        ];

        DB::table('obats')->insert($obatData);
    }
}
