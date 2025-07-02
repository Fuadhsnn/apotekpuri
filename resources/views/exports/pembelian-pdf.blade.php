<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Pembelian</title>
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
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PEMBELIAN OBAT</h1>
        <p>Tanggal Cetak: {{ $tanggal }}</p>
        @if($dari_tanggal && $sampai_tanggal)
            <p>Periode: {{ $dari_tanggal }} - {{ $sampai_tanggal }}</p>
        @endif
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Faktur</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th>Status</th>
                <th>Total</th>
                <th>Jumlah Item</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; $grandTotal = 0; @endphp
            @foreach($pembelians as $pembelian)
                @php 
                    $grandTotal += $pembelian->total_harga;
                    $tanggal = $pembelian->tanggal_pembelian instanceof \Carbon\Carbon 
                        ? $pembelian->tanggal_pembelian->format('d/m/Y') 
                        : date('d/m/Y', strtotime($pembelian->tanggal_pembelian));
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $pembelian->nomor_faktur }}</td>
                    <td>{{ $tanggal }}</td>
                    <td>{{ $pembelian->supplier->nama_supplier ?? '-' }}</td>
                    <td>{{ $pembelian->status_pembayaran === 'lunas' ? 'Lunas' : 'Belum Lunas' }}</td>
                    <td class="text-right">Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $pembelian->pembelianDetails->count() }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">Total</th>
                <th class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>Dicetak oleh: Admin</p>
    </div>
</body>
</html>