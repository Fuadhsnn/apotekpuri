<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header p {
            margin: 3px 0;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px;
            text-align: left;
            font-size: 10px;
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
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN TES PENJUALAN OBAT</h1>
        <p>Apotek Puri Pasir Putih</p>
        @if (isset($dari_tanggal) && isset($sampai_tanggal) && $dari_tanggal && $sampai_tanggal)
            <p>Periode: {{ $dari_tanggal }} - {{ $sampai_tanggal }}</p>
        @endif
        <p>Tanggal Cetak: {{ $tanggal }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th style="width: 18%;">No. Nota</th>
                <th class="text-center" style="width: 12%;">Tanggal</th>
                <th style="width: 25%;">Pelanggan</th>
                <th class="text-right" style="width: 15%;">Total</th>
                <th class="text-center" style="width: 15%;">Metode</th>
                <th class="text-center" style="width: 10%;">Item</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualans as $index => $penjualan)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $penjualan['nomor_nota'] }}</td>
                    <td class="text-center">{{ $penjualan['tanggal_penjualan'] }}</td>
                    <td>{{ $penjualan['nama_pelanggan'] }}</td>
                    <td class="text-right">Rp {{ number_format($penjualan['total_harga'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ $penjualan['metode_pembayaran'] }}</td>
                    <td class="text-center">{{ $penjualan['jumlah_item'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <th colspan="4" class="text-right">Total Keseluruhan:</th>
                <th class="text-right">Rp {{ number_format($total_keseluruhan, 0, ',', '.') }}</th>
                <th class="text-center">{{ $total_transaksi }} Transaksi</th>
                <th class="text-center">
                    @php
                        $totalItemKeseluruhan = 0;
                        foreach ($penjualans as $penjualan) {
                            $totalItemKeseluruhan += $penjualan['jumlah_item'];
                        }
                    @endphp
                    {{ $totalItemKeseluruhan }} Item
                </th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        <p>Sistem Apotek Puri Pasir Putih</p>
    </div>
</body>

</html>
