<?php
use Carbon\Carbon;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENJUALAN OBAT</h1>
        @if(isset($dari_tanggal) && isset($sampai_tanggal))
            <p>Periode: {{ $dari_tanggal }} - {{ $sampai_tanggal }}</p>
        @endif
        <p>Tanggal Cetak: {{ $tanggal }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Nota</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Metode Pembayaran</th>
                <th>Jumlah Item</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualans as $index => $penjualan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $penjualan->nomor_nota }}</td>
                <td>{{ $penjualan->tanggal_penjualan instanceof \Carbon\Carbon ? $penjualan->tanggal_penjualan->format('d/m/Y') : $penjualan->tanggal_penjualan }}</td>
                <td>{{ $penjualan->nama_pelanggan ?? 'Pelanggan' }}</td>
                <td>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                <td>{{ $penjualan->metode_pembayaran }}</td>
                <td>{{ $penjualan->penjualanDetails->count() }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" style="text-align: right">Total:</th>
                <th>Rp {{ number_format($penjualans->sum('total_harga'), 0, ',', '.') }}</th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>