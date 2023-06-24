<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\YahooJpCategory;

class YahooJpCategoryController extends Controller
{
    public function downloadYahooJpCategoryXLSX(Request $request){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Yahoo JP カテゴリ');

        // ヘッダー
        $sheet->setCellValue('A1', "YahooショッピングカテゴリID（半角数字のみ,10文字以内）");
        $sheet->setCellValue('B1', "ストアカテゴリのパス（カテゴリ名のコロン区切り）");

        $yahooJpCategories = YahooJpCategory::all();
        foreach ($yahooJpCategories as $index => $yahooJpCategory) {
            $sheet->setCellValue('A' . ($index + 2), $yahooJpCategory->product_category);
            $sheet->setCellValue('B' . ($index + 2), $yahooJpCategory->path);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="yahoo_jp_category.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

}
