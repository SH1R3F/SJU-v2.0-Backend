<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelController extends Controller
{
  
    public function create($name, $cells)
    {
      
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setRightToLeft(true);

      foreach($cells as $cell_key => $cell_value) {
          $sheet->setCellValue($cell_key, $cell_value);
      }

      $writer = new Xlsx($spreadsheet);
      touch(public_path("excel/{$name}.xlsx"));
      $writer->save(public_path("excel/{$name}.xlsx"));

      return response()->json([
        'excel' => asset("excel/{$name}.xlsx")
      ]);
    }

}
