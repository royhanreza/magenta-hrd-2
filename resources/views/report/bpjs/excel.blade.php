<table>
  <thead>
    <tr>
      <td colspan="20" style="text-align: center;"><strong>Laporan BPJS {{ $month }} {{ $year }}</strong></td>
    </tr>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>NPWP</th>
      <th>Gaji Pokok</th>
      <th>Gaji Pokok BPJS</th>
      <th>Tunjangan</th>
      <th>Insentif</th>
      <th>Total Gaji</th>
      <th>JKM (COM)</th>
      <th>JKK (COM)</th>
      <th>JHT (COM)</th>
      <th>JP (COM)</th>
      <th>BPJS Kesehatan (COM)</th>
      <th>Total Penambahan Gaji</th>
      <th>BPJS (JHT) (EMP)</th>
      <th>BPJS (Kesehatan) (EMP)</th>
      <th>BPJS (Pensiun) (EMP)</th>
      <th>Total Pengurangan Gaji</th>
      <th>Total Pembayaran Ke BPJS</th>
      <th>Gaji yang diterima</th>
    </tr>
  </thead>
  <tbody>
    @foreach($bpjs as $bpjs)
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $bpjs['employee']['first_name'] }}</td>
      <td>{{ $bpjs['employee']['npwp']['number'] }}</td>
      <td>{{ $bpjs['basic_salary'] }}</td>
      <td>{{ $bpjs['basic_salary_bpjs'] }}</td>
      <td>{{ $bpjs['allowances'] }}</td>
      <td>{{ $bpjs['incentive'] }}</td>
      <td>{{ $bpjs['total_salary'] }}</td>
      <td>{{ $bpjs['jkm_company'] }}</td>
      <td>{{ $bpjs['jkk_company'] }}</td>
      <td>{{ $bpjs['jht_company'] }}</td>
      <td>{{ $bpjs['jp_company'] }}</td>
      <td>{{ $bpjs['kesehatan_company'] }}</td>
      <td>{{ $bpjs['total_income_salary'] }}</td>
      <td>{{ $bpjs['jht_employee'] }}</td>
      <td>{{ $bpjs['kesehatan_employee'] }}</td>
      <td>{{ $bpjs['jp_employee'] }}</td>
      <td>{{ $bpjs['total_deduction_salary'] }}</td>
      <td>{{ $bpjs['total_bpjs'] }}</td>
      <td>{{ $bpjs['received_salary'] }}</td>
    </tr>
    @endforeach
  </tbody>
</table>