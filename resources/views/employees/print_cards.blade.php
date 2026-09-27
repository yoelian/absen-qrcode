<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Massal - AbsenPro</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f0f0f0;
            margin: 0;
            padding: 20px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .print-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: flex-start;
        }

        .id-card {
            width: 86mm;
            height: 54mm;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            page-break-inside: avoid;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .card-header {
            background: #0284c7; /* Primary Blue */
            color: #fff;
            padding: 2px 5px;
            display: flex;
            align-items: center;
            height: 14mm;
            border-bottom: 2px solid #0369a1;
        }

        .card-header .logo-container {
            width: 12mm;
            text-align: left;
            display: flex;
            align-items: center;
        }

        .card-header img {
            max-height: 12mm;
            max-width: 12mm;
            object-fit: contain;
        }

        .card-header .text-container {
            flex: 1;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .card-header .kop-pemerintah {
            font-size: 5px;
            line-height: 1;
            margin-bottom: 1px;
            text-transform: uppercase;
        }

        .card-header .kop-dinas {
            font-size: 6px;
            font-weight: bold;
            line-height: 1.1;
            margin-bottom: 1px;
            text-transform: uppercase;
        }

        .card-header h4 {
            margin: 0;
            font-size: 9px;
            font-weight: bold;
            line-height: 1.1;
            text-transform: uppercase;
        }
        
        .card-header .address {
            font-size: 5px;
            font-weight: normal;
            line-height: 1.1;
            margin-top: 1px;
        }

        .card-header .spacer {
            width: 12mm;
        }

        .card-body {
            display: flex;
            flex: 1;
            padding: 5px;
            gap: 8px;
        }

        .photo-area {
            width: 20mm;
            height: 25mm;
            border: 1px solid #ddd;
            border-radius: 3px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
        }

        .photo-area img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .info-area p {
            margin: 0 0 2px 0;
            font-size: 9px;
            color: #333;
        }

        .info-area p strong {
            display: inline-block;
            width: 14mm;
            color: #111;
        }
        
        .emp-name {
            font-size: 11px !important;
            font-weight: bold;
            color: #000 !important;
            margin-bottom: 4px !important;
            text-transform: uppercase;
        }

        .card-footer {
            text-align: center;
            padding: 3px 5px 6px 5px;
            background: #fff;
        }

        .barcode-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .barcode-container svg {
            height: 10mm;
            max-width: 100%;
        }

        .barcode-text {
            font-size: 8px;
            margin-top: 1px;
            letter-spacing: 2px;
            font-weight: bold;
        }

        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-print {
            background: #10b981;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .btn-print:hover {
            background: #059669;
        }

        .btn-back {
            background: #64748b;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }

        .btn-back:hover {
            background: #475569;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
            .no-print {
                display: none;
            }
            .id-card {
                box-shadow: none;
                border: 1px dashed #ccc; /* Helper for cutting */
            }
            @page {
                size: A4;
                margin: 10mm;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="javascript:history.back()" class="btn-back">⬅ Kembali</a>
        <button onclick="window.print()" class="btn-print">🖨️ Cetak Sekarang (Ctrl+P)</button>
        <p style="color: #666; font-size: 14px; margin-top: 10px;">Tips: Pastikan margin diset ke "None" atau "Minimum" dan matikan "Headers and Footers" pada setting print Chrome.</p>
    </div>

    <div class="print-container">
        @foreach($employees as $employee)
            <div class="id-card">
                <!-- Header -->
                <div class="card-header">
                    <div class="logo-container">
                        @if($schoolLogo)
                            <img src="{{ route('avatar.serve', $schoolLogo) }}" alt="Logo">
                        @endif
                    </div>
                    <div class="text-container">
                        @if($kopPemerintah)<div class="kop-pemerintah">{{ $kopPemerintah }}</div>@endif
                        @if($kopDinas)<div class="kop-dinas">{{ $kopDinas }}</div>@endif
                        <h4>{{ $schoolName }}</h4>
                        <div class="address">{{ Str::limit($schoolAddress, 60) }}</div>
                    </div>
                    <div class="spacer"></div>
                </div>

                <!-- Body -->
                <div class="card-body">
                    <div class="photo-area">
                        @if($employee->photo)
                            <img src="{{ route('avatar.serve', $employee->photo) }}" alt="Photo">
                        @else
                            <!-- Placeholder avatar -->
                            <svg viewBox="0 0 24 24" fill="#ccc" style="width: 80%; height: 80%;">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="info-area">
                        <p class="emp-name">{{ $employee->name }}</p>
                        <p><strong>Nomor</strong> : {{ $employee->code }}</p>
                        <p><strong>Status</strong> : {{ $employee->category->name ?? '-' }}</p>
                        <p><strong>Kelas</strong>  : {{ $employee->position->name ?? '-' }}</p>
                    </div>
                </div>

                <!-- Footer (Barcode) -->
                <div class="card-footer">
                    <div class="barcode-container">
                        <!-- C128 adalah tipe barcode standar yang dibaca semua scanner -->
                        {!! DNS1D::getBarcodeSVG($employee->code, 'C128', 1.5, 33, 'black', true) !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        // Otomatis muncul dialog print jika diinginkan
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
