<table>
    <thead>
        <tr>
            <td colspan="{{ count($dates) + 9 }}" style="font-size: 14px; font-weight: bold; text-align: center;">
                REKAPITULASI KEHADIRAN {{ isset($posName) && $posName !== 'Semua' ? '- ' . strtoupper($posName) : '' }}
            </td>
        </tr>
        <tr>
            <td colspan="{{ count($dates) + 9 }}" style="font-weight: bold; text-align: center;">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            </td>
        </tr>
        <tr></tr>
        <tr>
            <th rowspan="2" style="font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; width: 25px;">Nama / Kategori</th>
            <th colspan="{{ count($dates) }}" style="font-weight: bold; text-align: center; border: 1px solid #000000;">Tanggal</th>
            <th colspan="8" style="font-weight: bold; text-align: center; border: 1px solid #000000;">Total Rekap</th>
        </tr>
        <tr>
            @foreach($dates as $dateStr)
                <th style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 4px;">{{ \Carbon\Carbon::parse($dateStr)->format('d') }}</th>
            @endforeach
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 4px; color: #166534; background-color: #dcfce7;">H</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 4px; color: #92400e; background-color: #fef3c7;">T</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 4px; color: #3730a3; background-color: #e0e7ff;">I</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 4px; color: #6b21a8; background-color: #f3e8ff;">S</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 4px; color: #991b1b; background-color: #fee2e2;">A</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 4px; color: #9a3412; background-color: #ffedd5;">C</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 4px; color: #00695c; background-color: #e0f2f1;">DL</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 5px; color: #475569; background-color: #f1f5f9;">TAP</th>
        </tr>
    </thead>
    <tbody>
        @foreach($grid as $row)
            <tr>
                <td style="border: 1px solid #000000;">
                    {{ str_replace(',', '', $row['employee']->name) }} ({{ strtoupper($row['employee']->category->name ?? '-') }})
                </td>
                
                @foreach($dates as $dateStr)
                    @php
                        $status = $row['days'][$dateStr];
                        $color = '#000000';
                        if ($status === 'H') $color = '#166534';
                        elseif ($status === 'T') $color = '#92400e';
                        elseif ($status === 'I') $color = '#3730a3';
                        elseif ($status === 'S') $color = '#6b21a8';
                        elseif ($status === 'C') $color = '#9a3412';
                        elseif ($status === 'A') $color = '#991b1b';
                        elseif ($status === 'DL') $color = '#00695c';
                        elseif ($status === '-') $color = '#cbd5e1';
                    @endphp
                    <td style="text-align: center; border: 1px solid #000000; color: {{ $color }};">
                        {{ $status }}
                    </td>
                @endforeach
                
                <td style="text-align: center; border: 1px solid #000000; font-weight: bold;">{{ $row['summary']['H'] }}</td>
                <td style="text-align: center; border: 1px solid #000000; font-weight: bold;">{{ $row['summary']['T'] }}</td>
                <td style="text-align: center; border: 1px solid #000000; font-weight: bold;">{{ $row['summary']['I'] }}</td>
                <td style="text-align: center; border: 1px solid #000000; font-weight: bold;">{{ $row['summary']['S'] }}</td>
                <td style="text-align: center; border: 1px solid #000000; font-weight: bold;">{{ $row['summary']['A'] }}</td>
                <td style="text-align: center; border: 1px solid #000000; font-weight: bold;">{{ $row['summary']['C'] }}</td>
                <td style="text-align: center; border: 1px solid #000000; font-weight: bold;">{{ $row['summary']['DL'] ?? 0 }}</td>
                <td style="text-align: center; border: 1px solid #000000; font-weight: bold;">{{ $row['summary']['TAP'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
