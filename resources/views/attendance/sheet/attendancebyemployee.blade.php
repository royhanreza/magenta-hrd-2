<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Jam Masuk</th>
            <th>Jam Keluar</th>
        </tr>
    </thead>
    <tbody>
        @foreach($period as $att)
        <tr>
            @php
            $explodedDate = explode("-", $att['date']);
            $day = $explodedDate[2];
            $month = $explodedDate[1];
            $year = $explodedDate[0];
            @endphp
            <td>{{ (int) $day }} {{ Helper::prettyMonth((int) $month - 1, "id") }} {{ $year }}</td>
            @if($att['attendance'] !== null)
            <td>
                @if($att['attendance']['status'] == 'present')
                <span>Hadir</span>
                @elseif($att['attendance']['status'] == 'sick')
                <span>Sakit</span>
                @elseif($att['attendance']['status'] == 'permission')
                <span>Izin</span>
                @elseif($att['attendance']['status'] == 'leave')
                <span>Cuti</span>
                @elseif($att['attendance']['status'] == 'pending')
                <span>Pending ({{ $att['attendance']['pending_category'] }})</span>
                @elseif($att['attendance']['status'] == 'rejected')
                <span>Rejected</span>
                @else
                <span>N/A</span>
                @endif
            </td>
            <td>{{ $att['attendance']['clock_in'] }}</td>
            <td>{{ $att['attendance']['clock_out'] }}</td>
            @else
            <td class="text-center"><span class="badge badge-light">N/A</span></td>
            <td class="text-center">-</td>
            <td class="text-center">-</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>