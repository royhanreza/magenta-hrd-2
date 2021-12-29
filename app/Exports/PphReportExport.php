<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Controllers\web\ReportController;
use App\Models\Career;
use App\Models\FinalPayslip;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;

class PphReportExport extends DefaultValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder, WithColumnFormatting
{
  /**
   * @return \Illuminate\Support\Collection
   */
  protected $request;
  protected $month;
  protected $year;

  function __construct($request, $month, $year)
  {
    $this->request = $request;
    $this->month = $month;
    $this->year = $year;
  }

  public function view(): View
  {
    $pph = new ReportController;
    $data = $pph->generatePph($this->request, $this->month, $this->year);

    return view('report.pph.excel', [
      // 'pph' => $this->generatePph($this->request),
      'pph' => $data['pph'],
      'month' => $data['month'],
      'year' => $data['year'],
    ]);
  }

  public function columnFormats(): array
  {
    return [
      'E' => '#,##0.00_-',
      'F' => '#,##0.00_-',
      'G' => '#,##0.00_-',
      'H' => '#,##0.00_-',
      'I' => '#,##0.00_-',
      'J' => '#,##0.00_-',
      'K' => '#,##0.00_-',
      'L' => '#,##0.00_-',
      'M' => '#,##0.00_-',
      'N' => '#,##0.00_-',
      'O' => '#,##0.00_-',
      'P' => '#,##0.00_-',
      'Q' => '#,##0.00_-',
      // 'R' => '#,##0.00_-',
      'S' => '#,##0.00_-',
      'T' => '#,##0.00_-',
      'U' => '#,##0.00_-',
      'W' => '#,##0.00_-',
      'X' => '#,##0.00_-',
      'Y' => '#,##0.00_-',
    ];
  }

  public function bindValue(Cell $cell, $value)
  {
    if ($cell->getColumn() == 'C') {
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
}
