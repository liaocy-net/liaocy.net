<?php

namespace App\Http\Controllers;

require_once(__DIR__ . "/../../../vendor/autoload.php");

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use \App\Models\ForeignShipping;

class ForeignShippingController extends Controller
{
    public function downloadMyXLSX(Request $request){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('国際送料');

        // ヘッダー
        $sheet->setCellValue('A1', '重量(KG)');
        $sheet->setCellValue('B1', '費用(USD)');

        $foreignShippings = ForeignShipping::orderBy('weight_kg', 'asc')->get();

        foreach($foreignShippings as $i => $foreignShipping){
            $sheet->setCellValueExplicit('A' . ($i + 2), $foreignShipping->weight_kg, DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('B' . ($i + 2), $foreignShipping->usd_fee, DataType::TYPE_NUMERIC);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="kokusai.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
