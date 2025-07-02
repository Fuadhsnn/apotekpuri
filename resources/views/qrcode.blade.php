<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Apotek Puri</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Apotek Puri Pasir Putih</h1>
            <p class="text-gray-600 mt-2">Scan QR Code untuk melihat katalog obat</p>
        </div>
        
        <div class="flex justify-center mb-6">
            <div id="qrcode" class="border-4 border-gray-200 p-2 rounded-lg"></div>
        </div>
        
        <div class="text-center">
            <p class="text-sm text-gray-500 mb-2">atau kunjungi link berikut:</p>
            <a href="{{ $url }}" class="text-blue-600 hover:underline break-all">{{ $url }}</a>
        </div>
        
        <div class="mt-8 text-center">
            <a href="{{ route('kasir.index') }}" class="text-sm text-gray-600 hover:text-gray-800">&larr; Kembali ke Kasir</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Generate QR code
            var typeNumber = 0;
            var errorCorrectionLevel = 'L';
            var qr = qrcode(typeNumber, errorCorrectionLevel);
            qr.addData('{{ $url }}');
            qr.make();
            
            // Display QR code
            document.getElementById('qrcode').innerHTML = qr.createImgTag(8);
        });
    </script>
</body>
</html>