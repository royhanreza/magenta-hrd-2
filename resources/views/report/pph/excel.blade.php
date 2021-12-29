<table>
  <thead>
    <tr>
      <td colspan="25" style="text-align: center;"><strong>Laporan PPh 21 {{ $month }} {{ $year }}</strong></td>
    </tr>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>NPWP</th>
      <th>Mulai Kerja</th>
      <th>Gapok</th>
      <th>Insentif</th>
      <th>Honor Mingguan</th>
      <th>JKK</th>
      <th>JKM</th>
      <th>T. Pajak</th>
      <th>Total Gaji</th>
      <th>Bruto</th>
      <th>Biaya Jabatan</th>
      <th>JHT</th>
      <th>JP</th>
      <th>Total JHT JP</th>
      <th>Netto</th>
      <th>Marital</th>
      <th>PTKP</th>
      <th>PKP</th>
      <th>Tarif PPh</th>
      <th>Non NPWP</th>
      <th>PPh/Tahun</th>
      <th>PPh/Bulan</th>
      <th>Net</th>
    </tr>
  </thead>
  <tbody>
    @foreach($pph as $pph)
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $pph['employee']['first_name'] }}</td>
      <td>{{ $pph['employee']['npwp']['number'] }}</td>
      <td>{{ $month }} {{ $year }}</td>
      <td>{{ $pph['basic_salary'] }}</td>
      <td>{{ $pph['incentive'] }}</td>
      <td>{{ $pph['weekly_salary'] }}</td>
      <td>{{ $pph['jkk_company'] }}</td>
      <td>{{ $pph['jkm_company'] }}</td>
      <td>-</td>
      <td>{{ $pph['total_salary'] }}</td>
      <td>{{ $pph['bruto'] }}</td>
      <td>{{ $pph['position_allowance'] }}</td>
      <td>{{ $pph['jht_company'] }}</td>
      <td>{{ $pph['jp_company'] }}</td>
      <td>{{ $pph['total_jht_jp'] }}</td>
      <td>{{ $pph['netto'] }}</td>
      <td>{{ $pph['marital'] }}</td>
      <td>{{ $pph['ptkp'] }}</td>
      <td>{{ $pph['pkp'] }}</td>
      <td>{{ $pph['tarif_pph'] }}</td>
      <td>{{ $pph['non_npwp'] * 100 }}%</td>
      <td>{{ $pph['pph_per_year'] }}</td>
      <td>{{ $pph['pph_per_month'] }}</td>
      <td>{{ $pph['net'] }}</td>
    </tr>
    @endforeach
  </tbody>
</table>