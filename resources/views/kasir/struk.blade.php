<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian - {{ $penjualan->nomor_nota }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 0;
            padding: 0;
            width: 80mm;
            /* Lebar struk standar */
        }

        .receipt {
            padding: 5mm;
        }

        .header {
            text-align: center;
            margin-bottom: 5mm;
        }

        .title {
            font-size: 14px;
            font-weight: bold;
        }

        .info {
            margin: 3mm 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2mm;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 3mm 0;
        }

        .item {
            margin-bottom: 2mm;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
        }

        .total {
            font-weight: bold;
            margin-top: 3mm;
        }

        .footer {
            text-align: center;
            margin-top: 8mm;
        }

        .no-print {
            text-align: center;
            margin-top: 20mm;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="header">
            <div class="title">APOTEK PURI PASIR PUTIH</div>
            <div>Ruko Villagio Residence JL. Pasir Putih Kel. Pasir Putih Kec. Sawangan, Kota Depok, Jawa Barat</div>
            <div>Telp: 082311323121</div>
        </div>

        <div class="info">
            <div class="info-row">
                <div>No. Nota:</div>
                <div>{{ $penjualan->nomor_nota }}</div>
            </div>
            <div class="info-row">
                <div>Tanggal:</div>
                <div>{{ $penjualan->tanggal_penjualan->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div>Kasir:</div>
                <div>{{ $penjualan->user->name }}</div>
            </div>
            @if ($penjualan->nama_pelanggan)
                <div class="info-row">
                    <div>Pelanggan:</div>
                    <div>{{ $penjualan->nama_pelanggan }}</div>
                </div>
            @endif
            @if ($penjualan->nama_dokter)
                <div class="info-row">
                    <div>Dokter:</div>
                    <div>{{ $penjualan->nama_dokter }}</div>
                </div>
            @endif
            @if ($penjualan->nomor_resep)
                <div class="info-row">
                    <div>No. Resep:</div>
                    <div>{{ $penjualan->nomor_resep }}</div>
                </div>
            @endif
        </div>

        <div class="divider"></div>

        <div class="items">
            @foreach ($penjualan->penjualanDetails as $detail)
                <div class="item">
                    <div>
                        {{ $detail->obat ? $detail->obat->nama_obat : $detail->keterangan }}
                    </div>
                    <div class="item-row">
                        <div>{{ $detail->jumlah }} x {{ number_format($detail->harga_jual, 0, ',', '.') }}</div>
                        <div>{{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="divider"></div>

        <div class="total">
            <div class="info-row">
                <div>Total:</div>
                <div>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</div>
            </div>
            <div class="info-row">
                <div>Metode Pembayaran:</div>
                <div>{{ $penjualan->metode_pembayaran }}</div>
            </div>
            @if ($penjualan->metode_pembayaran == 'Tunai')
                <div class="info-row">
                    <div>Bayar:</div>
                    <div>Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</div>
                </div>
                <div class="info-row">
                    <div>Kembalian:</div>
                    <div>Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</div>
                </div>
            @endif
        </div>

        <div class="footer">
            <div>Terima Kasih Atas Kunjungan Anda</div>
            <div>Semoga Lekas Sembuh</div>
        </div>
    </div>

    <div class="no-print">
        <button onclick="window.print()"
            style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Cetak Struk
        </button>
        <button onclick="window.close()"
            style="padding: 10px 20px; margin-left: 10px; background: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Tutup
        </button>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            // Uncomment the line below to automatically print when page loads
            // window.print();
        };
    </script>
</body>

</html>
