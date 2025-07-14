<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <title>Laporan Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #000;
            padding-bottom: 8px;
        }

        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .header p {
            margin: 2px 0;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 3px;
            text-align: left;
            font-size: 9px;
        }

        th {
            background-color: #e0e0e0;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 15px;
            text-align: right;
            font-size: 8px;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN PEMBELIAN OBAT</h1>
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
                <th style="width: 20%;">No. Faktur</th>
                <th class="text-center" style="width: 12%;">Tanggal</th>
                <th style="width: 23%;">Supplier</th>
                <th class="text-center" style="width: 15%;">Status</th>
                <th class="text-right" style="width: 15%;">Total</th>
                <th class="text-center" style="width: 10%;">Item</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembelians as $index => $pembelian)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $pembelian['nomor_faktur'] }}</td>
                    <td class="text-center">{{ $pembelian['tanggal_pembelian'] }}</td>
                    <td>{{ $pembelian['nama_supplier'] }}</td>
                    <td class="text-center">{{ $pembelian['status_pembayaran'] }}</td>
                    <td class="text-right">Rp {{ number_format($pembelian['total_harga'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ $pembelian['jumlah_item'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <th colspan="5" class="text-right">Total:</th>
                <th class="text-right">Rp {{ number_format($total_keseluruhan, 0, ',', '.') }}</th>
                <th class="text-center">{{ $total_transaksi }} Transaksi</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>

</html>