<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Kehadiran Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 8px; /* Ukuran sangat kecil agar muat 40 kolom */
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        .header td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 18px;
        }
        .header p {
            margin: 3px 0 0 0;
            font-size: 10px;
        }
        .subtitle {
            text-align: center;
            margin-bottom: 15px;
        }
        .subtitle h2 {
            margin: 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 2px;
            text-align: center;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .text-left {
            text-align: left;
        }
        /* Warna Status */
        .status-h { color: #166534; }
        .status-t { color: #92400e; }
        .status-i { color: #3730a3; }
        .status-s { color: #6b21a8; }
        .status-a { color: #991b1b; }
        .status-c { color: #9a3412; }
        .status-dl { color: #00695c; }
        .status-null { color: #9ca3af; }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td style="width: 10%; text-align: left;">
                    @if(isset($schoolLogo) && $schoolLogo && file_exists(storage_path('app/public/' . $schoolLogo)))
                        @php
                            $type = pathinfo(storage_path('app/public/' . $schoolLogo), PATHINFO_EXTENSION);
                            $data = file_get_contents(storage_path('app/public/' . $schoolLogo));
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        @endphp
                        <img src="{{ $base64 }}" style="max-height: 60px;">
                    @endif
                </td>
                <td style="width: 80%; text-align: center;">
                    @if(isset($kopPemerintah) && $kopPemerintah)
                        <h1 style="font-size: 16px; margin-bottom: 2px;">{{ strtoupper($kopPemerintah) }}</h1>
                    @endif
                    @if(isset($kopDinas) && $kopDinas)
                        <h1 style="font-size: 16px; margin-bottom: 2px;">{{ strtoupper($kopDinas) }}</h1>
                    @endif
                    <h1 style="font-size: 20px; font-weight: bold; margin-bottom: 5px;">{{ isset($schoolName) ? strtoupper($schoolName) : 'SMAN 1 BENTIAN BESAR' }}</h1>
                    <p style="font-size: 11px;">{{ isset($schoolAddress) ? $schoolAddress : 'Alamat Sekolah Belum Diatur' }}</p>
                    @if(isset($kopKontak) && $kopKontak)
                        <p style="font-size: 10px;">{{ $kopKontak }}</p>
                    @endif
                </td>
                <td style="width: 10%;"></td>
            </tr>
        </table>
    </div>

    <div class="subtitle">
        <h2>REKAPITULASI KEHADIRAN BULANAN</h2>
        <p style="font-size: 10px; margin-top: 2px;">Bulan: {{ \Carbon\Carbon::createFromFormat('m', $month)->translatedFormat('F') }} | Tahun: {{ $year }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" class="text-left" style="width: 12%;">Nama / Kategori</th>
                <th colspan="{{ $daysInMonth }}">Tanggal</th>
                <th colspan="8">Total Rekap</th>
            </tr>
            <tr>
                @for($d = 1; $d <= $daysInMonth; $d++)
                    <th>{{ $d }}</th>
                @endfor
                <th>H</th>
                <th>T</th>
                <th>I</th>
                <th>S</th>
                <th>A</th>
                <th>C</th>
                <th>DL</th>
                <th>TAP</th>
            </tr>
        </thead>
        <tbody>
            @forelse($grid as $row)
                <tr>
                    <td class="text-left">
                        <strong>{{ $row['employee']->name }}</strong><br>
                        <span style="font-size: 7px;">{{ strtoupper($row['employee']->category->name ?? '-') }}</span>
                    </td>
                    
                    @for($d = 1; $d <= $daysInMonth; $d++)
                        @php
                            $status = $row['days'][$d];
                            $cls = 'status-null';
                            if ($status === 'H') $cls = 'status-h';
                            elseif ($status === 'T') $cls = 'status-t';
                            elseif ($status === 'I') $cls = 'status-i';
                            elseif ($status === 'S') $cls = 'status-s';
                            elseif ($status === 'A') $cls = 'status-a';
                            elseif ($status === 'C') $cls = 'status-c';
                            elseif ($status === 'DL') $cls = 'status-dl';
                        @endphp
                        <td class="{{ $cls }}"><strong>{{ $status }}</strong></td>
                    @endfor
                    
                    <td><strong>{{ $row['summary']['H'] }}</strong></td>
                    <td><strong>{{ $row['summary']['T'] }}</strong></td>
                    <td><strong>{{ $row['summary']['I'] }}</strong></td>
                    <td><strong>{{ $row['summary']['S'] }}</strong></td>
                    <td><strong>{{ $row['summary']['A'] }}</strong></td>
                    <td><strong>{{ $row['summary']['C'] }}</strong></td>
                    <td><strong>{{ $row['summary']['DL'] ?? 0 }}</strong></td>
                    <td><strong>{{ $row['summary']['TAP'] }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $daysInMonth + 8 }}">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table style="width: 100%; margin-top: 30px; border: none;">
        <tr>
            <td style="width: 70%; border: none;"></td>
            <td style="width: 30%; border: none; text-align: center;">
                <p style="margin-bottom: 60px; font-size: 10px;">Mengetahui,<br>Kepala Sekolah,</p>
                <p style="margin: 0; font-weight: bold; text-decoration: underline; font-size: 10px;">{{ isset($headmasterName) && $headmasterName ? $headmasterName : '(............................................)' }}</p>
                <p style="margin: 0; font-size: 10px;">NIP. {{ isset($headmasterNip) && $headmasterNip ? $headmasterNip : '-' }}</p>
            </td>
        </tr>
    </table>

</body>
</html>
