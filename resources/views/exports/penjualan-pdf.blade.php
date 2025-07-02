<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 6px;
            text-align: left;
            font-size: 11px;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENJUALAN OBAT</h1>
        @if(isset($dari_tanggal) && isset($sampai_tanggal) && $dari_tanggal && $sampai_tanggal)
            <p>Periode: {{ $dari_tanggal }} - {{ $sampai_tanggal }}</p>
        @endif
        <p>Tanggal Cetak: {{ $tanggal }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th style="width: 15%;">No. Nota</th>
                <th class="text-center" style="width: 12%;">Tanggal</th>
                <th style="width: 20%;">Pelanggan</th>
                <th class="text-right" style="width: 15%;">Total</th>
                <th class="text-center" style="width: 15%;">Metode Bayar</th>
                <th class="text-center" style="width: 10%;">Jml Item</th>
            </tr>
        </thead>
        <tbody>
            @php $totalKeseluruhan = 0; @endphp
            @foreach($penjualans as $index => $penjualan)
            @php $totalKeseluruhan += $penjualan->total_harga; @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $penjualan->nomor_nota ?? '-' }}</td>
                <td class="text-center">
                    @if($penjualan->tanggal_penjualan)
                        @if($penjualan->tanggal_penjualan instanceof \Carbon\Carbon)
                            {{ $penjualan->tanggal_penjualan->format('d/m/Y') }}
                        @else
                            {{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d/m/Y') }}
                        @endif
                    @else
                        -
                    @endif
                </td>
                <td>{{ $penjualan->nama_pelanggan ?? 'Pelanggan' }}</td>
                <td class="text-right">Rp {{ number_format($penjualan->total_harga ?? 0, 0, ',', '.') }}</td>
                <td class="text-center">{{ $penjualan->metode_pembayaran ?? '-' }}</td>
                <td class="text-center">
                    @php
                        $totalJumlahItem = 0;
                        if ($penjualan->penjualanDetails) {
                            foreach ($penjualan->penjualanDetails as $detail) {
                                $totalJumlahItem += (int) ($detail->jumlah ?? 0);
                            }
                        }
                    @endphp
                    {{ $totalJumlahItem }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <th colspan="4" class="text-right">Total Keseluruhan:</th>
                <th class="text-right">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</th>
                <th colspan="2" class="text-center">{{ count($penjualans) }} Transaksi</th>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        <p>Apotek Puri Pasir Putih</p>
    </div>
</body>
</html>