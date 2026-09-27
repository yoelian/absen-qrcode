<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Histori Absensi - {{ $employee->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
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
            font-size: 11px;
        }
        .subtitle {
            text-align: center;
            margin-bottom: 15px;
        }
        .subtitle h2 {
            margin: 0;
            font-size: 14px;
        }
        .profile-table {
            width: 100%;
            margin-bottom: 20px;
            border: none;
        }
        .profile-table td {
            border: none;
            padding: 3px 5px;
            font-size: 11px;
        }
        .summary-box {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .summary-table {
            width: 100%;
            border: none;
        }
        .summary-table td {
            border: none;
            padding: 5px;
            font-weight: bold;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        .data-table th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .text-left {
            text-align: left !important;
        }
        .footer {
            margin-top: 30px;
            width: 100%;
        }
        .footer-table {
            width: 100%;
            border: none;
        }
        .footer-table td {
            border: none;
            text-align: center;
            width: 50%;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td style="width: 15%; text-align: left;">
                    @if(isset($schoolLogo) && $schoolLogo && file_exists(storage_path('app/public/' . $schoolLogo)))
                        @php
                            $type = pathinfo(storage_path('app/public/' . $schoolLogo), PATHINFO_EXTENSION);
                            $data = file_get_contents(storage_path('app/public/' . $schoolLogo));
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        @endphp
                        <img src="{{ $base64 }}" style="max-height: 95px; max-width: 95px; width: auto; object-fit: contain;">
                    @endif
                </td>
                <td style="width: 70%; text-align: center;">
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
                <td style="width: 15%;"></td>
            </tr>
        </table>
    </div>

    <div class="subtitle">
        <h2>LAPORAN HISTORI ABSENSI INDIVIDU</h2>
        <p style="font-size: 11px; margin-top: 3px;">
            Periode: 
            @if($period == 'day') Hari Ini ({{ \Carbon\Carbon::today()->translatedFormat('d F Y') }})
            @elseif($period == 'week') Minggu Ini
            @elseif($period == 'month') Bulan Ini ({{ \Carbon\Carbon::today()->translatedFormat('F Y') }})
            @else Semua Waktu
            @endif
        </p>
    </div>

    <table class="profile-table">
        <tr>
            <td style="width: 15%; font-weight: bold;">Nama</td>
            <td style="width: 35%;">: {{ $employee->name }}</td>
            <td style="width: 15%; font-weight: bold;">Kategori</td>
            <td style="width: 35%;">: {{ strtoupper($employee->category->name ?? '-') }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Nomor Induk</td>
            <td>: {{ $employee->code }}</td>
            <td style="font-weight: bold;">Kelas / Jabatan</td>
            <td>: {{ strtoupper($employee->position->name ?? '-') }}</td>
        </tr>
    </table>

    <div class="summary-box">
        <div style="font-size: 12px; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Ringkasan Kehadiran</div>
        <table class="summary-table">
            <tr>
                <td style="color: #166534;">Hadir: {{ $summary['hadir'] }}</td>
                <td style="color: #3730a3;">Izin: {{ $summary['izin'] }}</td>
                <td style="color: #6b21a8;">Sakit: {{ $summary['sakit'] }}</td>
                <td style="color: #991b1b;">Alpa: {{ $summary['alpha'] }}</td>
            </tr>
            <tr>
                <td style="color: #92400e;">(Terlambat: {{ $summary['terlambat'] }})</td>
                <td style="color: #00695c;">Dinas Luar: {{ $summary['dl'] }}</td>
                <td style="color: #9a3412;">Cuti: {{ $summary['cuti'] }}</td>
                <td style="color: #d97706;">(Lupa Pulang/TAP: {{ $summary['tap'] }})</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;" class="text-left">Hari, Tanggal</th>
                <th style="width: 20%;">Jam Masuk</th>
                <th style="width: 20%;">Jam Pulang</th>
                <th style="width: 25%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendancesList as $index => $att)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ \Carbon\Carbon::parse($att->date)->translatedFormat('l, d F Y') }}</td>
                    <td>{{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('H:i') : '-' }}</td>
                    <td>{{ $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('H:i') : '-' }}</td>
                    <td>{{ strtoupper($att->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 20px; color: #666;">Tidak ada data kehadiran pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td></td>
                <td>
                    Bentian Besar, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    Mengetahui,<br>
                    <br><br><br><br>
                    <strong>{{ $headmasterName ?? '_______________________' }}</strong><br>
                    NIP. {{ $headmasterNip ?? '_______________________' }}
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
