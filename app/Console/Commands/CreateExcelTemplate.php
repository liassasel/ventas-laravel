<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CreateExcelTemplate extends Command
{
    protected $signature = 'excel:create-template';

    protected $description = 'Create an Excel template for product import';

    public function handle()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'codigo', 'nombre', 'descripcion', 'precio', 'moneda', 
            'serial', 'modelo', 'marca', 'color', 'categoria', 'tienda'
        ];

        foreach ($headers as $index => $header) {
            $sheet->setCellValueByColumnAndRow($index + 1, 1, $header);
        }

        $sheet->getStyle('A1:K1')->getFont()->setBold(true);

        $sampleData = [
            'PROD001', 'Laptop HP Pavilion', 'Laptop HP Pavilion 15.6" Intel Core i5', 
            2500, 'PEN', 'SN123456789', 'Pavilion 15-dk1056la', 'HP', 'Negro', 
            'Laptops', 'Numero de tienda'
        ];

        foreach ($sampleData as $index => $value) {
            $sheet->setCellValueByColumnAndRow($index + 1, 2, $value);
        }

        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = storage_path('app/template_productos.xlsx');
        $writer->save($filename);

        $this->info("Template creado exitosamente en: $filename");
    }
}

