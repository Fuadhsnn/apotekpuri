<?php

/**
 * Script untuk mengimpor data obat dari SatuSehat API secara otomatis
 * 
 * Cara penggunaan:
 * php import_medications.php [jumlah] [kategori1,kategori2,...]
 * 
 * Contoh:
 * php import_medications.php 100 antibiotik,vitamin
 */

// Bootstrap Laravel application
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Parse arguments
$count = $argv[1] ?? 100;
$categories = isset($argv[2]) ? explode(',', $argv[2]) : [];

// Build command
$command = "satusehat:import-medications {$count}";

if (!empty($categories)) {
    foreach ($categories as $category) {
        $command .= " --category={$category}";
    }
}

// Run command
$status = $kernel->call($command);

$kernel->terminate(null, $status);

exit($status);