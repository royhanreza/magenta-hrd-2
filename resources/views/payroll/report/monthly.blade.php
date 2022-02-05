<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Status</th>
            <th>Bagian (Jabatan)</th>
            <th>Gaji</th>
            <th>Tunjangan Jabatan</th>
            <th>Insentif Kehadiran</th>
            <!--<th>Cuti Diuangkan</th>-->
            <th>Bayar Kasbon</th>
            <th>Kelebihan Cuti</th>
            <th>Total</th>
            <th>Awal Piutang</th>
            <th>Sisa Piutang</th>
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
            <td data-format="#,##0_-">{{ $employee->position_allowance }}</td>
            <td data-format="#,##0_-">{{ $employee->attendance_allowance }}</td>
            <!--<td>-</td>-->
            <td data-format="#,##0_-">{{ $employee->loan }}</td>
            <td data-format="#,##0_-">{{ $employee->excess_leave }}</td>
            <td data-format="#,##0_-">{{ $employee->total }}</td>
            <!--<td data-format="#,##0_-">{{ $employee->loan_balance + $employee->loan }}</td>-->
            <td data-format="#,##0_-">{{ $employee->total_loan }}</td>
            <td data-format="#,##0_-">{{ $employee->loan_balance }}</td>
            <td>{{ \Carbon\Carbon::parse($employee->start_work_date)->isoFormat('LL') }}</td>
            <td>{{ $employee->bank_account_number !== null ? '(' . $employee->bank_account_number . ')' : '' }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="5"><strong>TOTAL</strong></td>
            <td data-format="#,##0_-"><strong>{{ collect($employees)->sum('basic_salary') }}</strong></td>
            <td data-format="#,##0_-"><strong>{{ collect($employees)->sum('position_allowance') }}</strong></td>
            <td data-format="#,##0_-"><strong>{{ collect($employees)->sum('attendance_allowance') }}</strong></td>
            <td data-format="#,##0_-"><strong>{{ collect($employees)->sum('loan') }}</strong></td>
            <td data-format="#,##0_-"><strong>{{ collect($employees)->sum('excess_leave') }}</strong></td>
            <td data-format="#,##0_-"><strong>{{ collect($employees)->sum('total') }}</strong></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>