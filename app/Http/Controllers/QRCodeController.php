<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    public function generateQRCode()
    {
        // URL para la vista de cliente
        $url = route('pelanggan.index');
        
        return view('qrcode', compact('url'));
    }
}