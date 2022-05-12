<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Status</th>
            <th>Bagian (Jabatan)</th>
            <th>Gaji</th>
            <th>Tanggal Mulai Kerja</th>
            <th>Rek Bank</th>
        </tr>
    </thead>
    <tbody>
        <?php $totalBasicSalary = 0; ?>
        <?php $totalPositionAllowance = 0; ?>
        <?php $totalAttendanceAllowance = 0; ?>
        <?php $totalAttendanceAllowance = 0; ?>
        @foreach($employees as $employee)
        <tr>
            <td>{{ $employee->first_name }}</td>
            @if($employee->npwp !== null)
            <td>{{ $employee->npwp->type }}</td>
            @else
            <td></td>
            @endif
            @if($employee->activeCareer !== null)
            @if($employee->activeCareer->designation !== null)
            <td>{{ $employee->activeCareer->designation->name }}</td>
            @else
            <td></td>
            @endif
            @else
            <td></td>
            @endif
            <td data-format="#,##0_-">{{ $employee->basic_salary }}</td>
            <td>{{ \Carbon\Carbon::parse($employee->start_work_date)->isoFormat('LL') }}</td>
            <td>{{ $employee->bank_account_number !== null ? '(' . $employee->bank_account_number . ')' : '' }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3"><strong>TOTAL</strong></td>
            <td data-format="#,##0_-"><strong>{{ collect($employees)->sum('basic_salary') }}</strong></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>