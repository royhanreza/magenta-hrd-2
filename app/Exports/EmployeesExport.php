<?php

namespace App\Exports;

// use App\Employee;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EmployeesExport extends DefaultValueBinder implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithCustomValueBinder, WithEvents, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Employee::all();
    }

    public function map($employee): array
    {
        return [
            $employee->first_name,
            $employee->id,
            $employee->employee_id,
            ($employee->gender == 'male') ? 'Laki-laki' : 'Wanita',
            strtoupper($employee->citizenship),
            $employee->citizenship == 'wni' ? 'Indonesia' : $employee->citizenship_country,
            strtoupper($employee->identity_type),
            $employee->identity_number,
            $employee->identity_expire_date,
            $employee->place_of_birth,
            $employee->date_of_birth,
            ucfirst($employee->marital_status),
            ucfirst($employee->religion),
            $employee->blood_type,
            $employee->last_education,
            $employee->last_education_name,
            $employee->study_program,
            ucfirst($employee->work_placement),
            $employee->type,
            $employee->email,
            $employee->contact_number,
            $employee->address,
            $employee->emergency_contact_name,
            $employee->emergency_contact_relation,
            $employee->emergency_contact_number,
            $employee->bank_account_name,
            $employee->bank_account_owner,
            $employee->bank_account_number,
            $employee->bank_account_branch,
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() == 'H' || $cell->getColumn() == 'U' || $cell->getColumn() == 'Y' || $cell->getColumn() == 'AB') {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // if (is_numeric($value)) {
        //     $cell->setValueExplicit($value, DataType::TYPE_STRING);

        //     return true;
        // }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function headings(): array
    {
        return [
            'Nama',
            'ID User',
            'ID Employee',
            'Jenis Kelamin',
            'Kewarganegaraan',
            'Negara',
            'Identitas Diri',
            'No. Identitas',
            'Tanggal Akhir Berlaku Identitas',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Status Perkawinan',
            'Agama',
            'Golongan Darah',
            'Pendidikan Terakhir',
            'Nama Institusi Pendidikan',
            'Jurusan / Program Studi',
            'Work Placement',
            'Tipe Pegawai',
            'Email',
            'No. HP',
            'Alamat',
            'Nama Kontak Darurat',
            'Hubungan Kontak Darurat',
            'Telepon Darurat',
            'Nama Bank',
            'Nama Pemegang Rekening',
            'No Rekening',
            'Kantor Cabang Bank',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => '48dbfb',
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $workSheet = $event->sheet->getDelegate();
                $workSheet->freezePane('B1'); // freezing here
            },
        ];
    }
}
