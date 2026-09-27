<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi Harian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            color: #666;
        }
        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }
        .bg-success { background-color: #28a745; }
        .bg-warning { background-color: #ffc107; color: #333; }
        .bg-info { background-color: #17a2b8; }
        .bg-secondary { background-color: #6c757d; }
        .footer {
            text-align: right;
            margin-top: 50px;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <table style="width: 100%; border-bottom: 2px solid #333; margin-bottom: 20px; padding-bottom: 10px; border-collapse: collapse; border: none;">
        <tr>
            <td style="width: 15%; text-align: center; border: none; padding: 0;">
                @if(isset($schoolLogo) && $schoolLogo && file_exists(storage_path('app/public/' . $schoolLogo)))
                    @php
                        $type = pathinfo(storage_path('app/public/' . $schoolLogo), PATHINFO_EXTENSION);
                        $data = file_get_contents(storage_path('app/public/' . $schoolLogo));
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    @endphp
                    <img src="{{ $base64 }}" alt="Logo" style="width: 80px;">
                @endif
            </td>
            <td style="width: 70%; text-align: center; border: none; padding: 0;">
                @if(isset($kopPemerintah) && $kopPemerintah)
                    <h1 style="font-size: 14px; margin: 0 0 2px 0; text-transform: uppercase;">{{ $kopPemerintah }}</h1>
                @endif
                @if(isset($kopDinas) && $kopDinas)
                    <h1 style="font-size: 14px; margin: 0 0 2px 0; text-transform: uppercase;">{{ $kopDinas }}</h1>
                @endif
                <h1 style="font-size: 18px; margin: 0 0 5px 0; text-transform: uppercase;">{{ $schoolName }}</h1>
                <p style="margin: 0; color: #666; font-size: 11px;">{{ isset($schoolAddress) ? $schoolAddress : 'Alamat Sekolah Belum Diatur' }}</p>
                @if(isset($kopKontak) && $kopKontak)
                    <p style="margin: 0; color: #666; font-size: 10px;">{{ $kopKontak }}</p>
                @endif
            </td>
            <td style="width: 15%; border: none; padding: 0;"></td>
        </tr>
    </table>

    <div class="title">
        Laporan Kehadiran Absensi <br>
        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
        @if(isset($catName) && $catName !== 'Semua')
            <br>Kategori: {{ strtoupper($catName) }}
        @endif
        @if(isset($posName) && $posName !== 'Semua')
            <br>Kelas/Jabatan: {{ strtoupper($posName) }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Tanggal</th>
                <th style="width: 12%;">Nomor Induk</th>
                <th>Nama Lengkap</th>
                <th style="width: 10%;">Kategori</th>
                <th style="width: 15%;">Kelas / Jabatan</th>
                <th style="width: 10%;">Masuk</th>
                <th style="width: 10%;">Pulang</th>
                <th style="width: 12%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $attendance)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                    <td><strong>{{ $attendance->employee->code }}</strong></td>
                    <td>{{ $attendance->employee->name }}</td>
                    <td>{{ $attendance->employee->category ? strtoupper($attendance->employee->category->name) : '-' }}</td>
                    <td>{{ $attendance->employee->position ? $attendance->employee->position->name : '-' }}</td>
                    <td>{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}</td>
                    <td>{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '-' }}</td>
                    <td>
                        @if($attendance->status === 'hadir')
                            <span class="badge bg-success">HADIR</span>
                        @elseif($attendance->status === 'terlambat')
                            <span class="badge bg-warning">TERLAMBAT</span>
                        @else
                            <span class="badge bg-secondary">{{ strtoupper($attendance->status) }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data kehadiran untuk kriteria ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table style="width: 100%; border: none; margin-top: 30px;">
        <tr>
            <td style="width: 70%; border: none;"></td>
            <td style="width: 30%; border: none; text-align: center;">
                Tanggal Cetak: {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}<br>
                Mengetahui,<br>
                Kepala Sekolah,<br><br><br><br>
                <strong><u>{{ isset($headmasterName) && $headmasterName ? $headmasterName : '(............................................)' }}</u></strong><br>
                NIP. {{ isset($headmasterNip) && $headmasterNip ? $headmasterNip : '-' }}
            </td>
        </tr>
    </table>

</body>
</html>
