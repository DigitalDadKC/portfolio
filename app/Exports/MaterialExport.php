<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MaterialExport extends DefaultValueBinder implements FromView, WithColumnWidths, WithTitle, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $contract;
    public $materials;
    public $effective_date;

    public function __construct($contract, $materials, $effective_date)
    {
        $this->contract = $contract;
        $this->materials = $materials;
        $this->effective_date = $effective_date;
    }
    public function view(): View
    {
        return view('exports.material-export', [
            'contract' => $this->contract,
            'materials' => $this->modified_materials($this->materials),
            'effective_date' => $this->effective_date
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 40,
            'C' => 16,
            'D' => 20,
            'E' => 20,
            'F' => 30,
            'G' => 30
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getPageSetup()->setScale(70);
        $sheet->getStyle('A:G')->getFont()->setName('Cambria');
        $sheet->getStyle('A1:G4')->getFont()->setBold(true);
        $sheet->getStyle('A4:G4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('B2B2B2');
        $sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G2')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
        $sheet->getStyle(2)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
    }

    public function title(): string
    {
        return $this->contract['Name'] . ' . ' . $this->effective_date;
    }

    protected function modified_materials($materials)
    {
        foreach ($materials as $material) {
            if ($material->Discountable) {
                $material[$this->effective_date] = $material[$this->effective_date] * (1 - $this->contract['Discount']);
            }
        }
        return $materials;
    }
}
