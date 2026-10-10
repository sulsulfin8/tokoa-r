<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    @if(isset($format) && $format == 'print')
    <style>
        body {
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            padding: 40px 0;
            margin: 0;
            font-family: "Times New Roman", Times, serif;
        }
        .document-container {
            background-color: white;
            width: 21cm;
            min-height: 29.7cm;
            padding: 2cm;
            box-sizing: border-box;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>
    @else
    <style>
        body { font-family: "Times New Roman", Times, serif; }
    </style>
    @endif
</head>
<body>
    @if(isset($format) && $format == 'print')
    <div class="document-container">
    @endif

    @if(isset($format) && in_array($format, ['word', 'print']))
        @php
            $logoPath = storage_path('Logo.jpeg');
            if (!file_exists($logoPath)) {
                $logoPath = storage_path('app/public/Logo.jpeg');
            }
            $logoSrc = file_exists($logoPath) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath)) : '';

            if (!isset($ttdSrc)) {
                $sigData = \App\Services\LaporanSignatureService::getSignatureData('penjualan');
                $namaPemilik = $sigData['namaPemilik'];
                $tanggalCetak = $sigData['tanggalCetak'];
                $waktuCetak = $sigData['waktuCetak'];
                $ttdSrc = $sigData['ttdSrc'];
            }
        @endphp
        <!-- Kop Surat Resmi (Format 3 Kolom Simetris Agar Teks Judul 100% Rata Tengah) -->
        <table width="100%" style="border-collapse: collapse; border-bottom: 3px solid #000; margin-bottom: 15px;">
            <tr>
                <td width="18%" style="text-align: center; vertical-align: middle; padding-bottom: 8px;">
                    @if($logoSrc)
                    <img src="{{ $logoSrc }}" width="85" height="85" alt="Logo" style="display: inline-block;">
                    @endif
                </td>
                <td width="64%" style="text-align: center; vertical-align: middle; padding-bottom: 8px;">
                    <h1 style="margin: 0; font-size: 24px; font-weight: bold; letter-spacing: 1px;">TOKO A&amp;R</h1>
                    <p style="margin: 4px 0 0 0; font-size: 13px; font-style: italic;">Menyediakan Berbagai Macam Roti dan Kue Berkualitas</p>
                    <p style="margin: 3px 0 0 0; font-size: 11px;">Telp: 0812-3456-7890 | Email: admin@anrbakery.com</p>
                </td>
                <td width="18%" style="text-align: center; vertical-align: middle; padding-bottom: 8px;">
                    <!-- Spacer penyeimbang simetris agar teks kop tepat di tengah halaman -->
                    &nbsp;
                </td>
            </tr>
        </table>
        <h2 style="text-align: center; margin-top: 15px; margin-bottom: 5px; font-size: 18px; text-transform: uppercase;">Laporan Transaksi Penjualan Produk</h2>
        <p style="text-align: center; margin: 0 0 15px 0; font-size: 11px; color: #555;">Dicetak pada: {{ $tanggalCetak }} - {{ $waktuCetak }}</p>
    @else
        <h2 style="text-align: center;">Laporan Transaksi Penjualan Produk</h2>
        <h3 style="text-align: center;">TOKO A&amp;R</h3>
    @endif
    <br>
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>No</th>
                <th>No Penjualan</th>
                <th>Tanggal</th>
                <th>Rincian Produk (Qty)</th>
                <th>Total Pendapatan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualans as $index => $trx)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="text-align: center;">{{ $trx->no_penjualan }}</td>
                <td style="text-align: center;">{{ $trx->tanggal }}</td>
                <td>
                    <ul style="margin: 2px 0; padding-left: 18px;">
                        @foreach($trx->detailPenjualans as $detail)
                            <li>{{ $detail->produk->nama_produk ?? 'Produk Dihapus' }} (x{{ $detail->qty }})</li>
                        @endforeach
                    </ul>
                </td>
                <td style="text-align: right;">{{ is_numeric($trx->total_harga) ? number_format($trx->total_harga, 0, ',', '.') : $trx->total_harga }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    @if(isset($format) && in_array($format, ['word', 'print']))
    <!-- Bagian Tanda Tangan Pemilik Toko (Khusus Word & Cetak, Tidak di Excel) -->
    <br><br>
    <table width="100%" style="border-collapse: collapse; margin-top: 25px; page-break-inside: avoid;">
        <tr>
            <td width="60%">
                <!-- Ruang kosong di sebelah kiri agar stempel fisik dapat dibubuhkan langsung oleh pemilik toko -->
                &nbsp;
            </td>
            <td width="40%" style="text-align: center; vertical-align: top;">
                <div style="font-size: 12px; margin-bottom: 4px;">Anaiwoi, {{ $tanggalCetak }}</div>
                <div style="font-size: 13px; font-weight: bold; margin-bottom: 6px;">Pemilik Toko A&amp;R</div>
                
                @if(!empty($ttdSrc))
                <div style="height: 95px; text-align: center; margin: 4px 0;">
                    <img src="{{ $ttdSrc }}" style="max-height: 90px; max-width: 140px; display: inline-block;" alt="Tanda Tangan Pemilik Toko">
                </div>
                @else
                <div style="height: 95px;"></div>
                @endif

                <div style="font-size: 13px; font-weight: bold; text-decoration: underline; margin-top: 4px;">{{ $namaPemilik }}</div>
                <div style="font-size: 11px; color: #475569; margin-top: 2px;">Owner / Penanggung Jawab</div>
            </td>
        </tr>
    </table>
    @endif

    @if(isset($format) && $format == 'print')
    </div>
    @endif
</body>
</html>
