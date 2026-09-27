<table>
    <thead>
        <tr>
            <td colspan="8" style="font-size: 14px; font-weight: bold; text-align: center;">
                LAPORAN DETAIL ABSENSI {{ isset($posName) && $posName !== 'Semua' ? '- ' . strtoupper($posName) : '' }}
            </td>
        </tr>
        <tr>
            <td colspan="8" style="font-weight: bold; text-align: center;">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            </td>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 12px; background-color: #f3f4f6;">Tanggal</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 15px; background-color: #f3f4f6;">Nomor Induk</th>
            <th style="font-weight: bold; text-align: left; border: 1px solid #000000; width: 30px; background-color: #f3f4f6;">Nama Lengkap</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 15px; background-color: #f3f4f6;">Kategori</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 20px; background-color: #f3f4f6;">Kelas / Jabatan</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 12px; background-color: #f3f4f6;">Jam Masuk</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 12px; background-color: #f3f4f6;">Jam Keluar</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 15px; background-color: #f3f4f6;">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($attendances as $attendance)
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $attendance->employee->code }}</td>
                <td style="text-align: left; border: 1px solid #000000;">{{ str_replace(',', '', $attendance->employee->name) }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ strtoupper($attendance->employee->category->name ?? '-') }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $attendance->employee->position->name ?? '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ strtoupper($attendance->status) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align: center; border: 1px solid #000000;">Tidak ada data.</td>
            </tr>
        @endforelse
    </tbody>
</table>
