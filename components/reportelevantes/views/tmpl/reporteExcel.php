<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};

class reporteExcel {
  var $objExcel;
  var $hojaActiva;
  
  function reporteExcel() {
    $this->objExcel = new Spreadsheet();
    $this->hojaActiva = $this->objExcel->getActiveSheet();
    $this->hojaActiva->setTitle("Levantes");
  }
  
  function generarExcel($arreglo) {
    // Colocamos encabezados
    $this->hojaActiva->setCellValue("A1", "No.");
    $this->hojaActiva->setCellValue("B1", "Cliente");
    $this->hojaActiva->setCellValue("C1", "Orden");
    $this->hojaActiva->setCellValue("D1", "Documento TTE");
    $this->hojaActiva->setCellValue("E1", "Código Referencia");
    $this->hojaActiva->setCellValue("F1", "Referencia");
    $this->hojaActiva->setCellValue("G1", "Ancho");
    $this->hojaActiva->setCellValue("H1", "Fecha");
    $this->hojaActiva->setCellValue("I1", "Piezas");
    $this->hojaActiva->setCellValue("J1", "Peso");
    $this->hojaActiva->setCellValue("K1", "Valor");
    // Inicializamos variables de fila
    $i = $n = 1;$tpiezas = $tpeso = $tvalor = 0;
    foreach($arreglo['datos'] as $value) {
      $i++;
      // Mostramos información de registro en cada fila
      $this->hojaActiva->setCellValue('A'.$i, $n);
      $this->hojaActiva->setCellValue('B'.$i, '['.$value['documento'].'] '.$value['nombre_cliente']);
      $this->hojaActiva->setCellValue('C'.$i, $value['orden']);
      $this->hojaActiva->setCellValue('D'.$i, $value['doc_tte']);
      $this->hojaActiva->setCellValue('E'.$i, $value['codigo_ref']);
      $this->hojaActiva->setCellValue('F'.$i, $value['nombre_referencia']);
      $this->hojaActiva->setCellValue('G'.$i, $value['ancho']);
      $this->hojaActiva->setCellValue('H'.$i, $value['fecha']);
      $this->hojaActiva->setCellValue('I'.$i, number_format($value['piezas'],2));
      $this->hojaActiva->setCellValue('J'.$i, number_format($value['peso'],2));
      $this->hojaActiva->setCellValue('K'.$i, number_format($value['valor'],2));
      //Acumulamos Totales
      $tpiezas += $value['piezas']; $tpeso += $value['peso'];
      $tvalor += $value['valor']; $n++;
    }
    $i++;
    $this->hojaActiva->setCellValue('A'.$i, 'T O T A L E S');
    $this->hojaActiva->setCellValue('I'.$i, number_format($tpiezas,2));
    $this->hojaActiva->setCellValue('J'.$i, number_format($tpeso,2));
    $this->hojaActiva->setCellValue('K'.$i, number_format($tvalor,2));

    // Ajuste automático de las columnas
    foreach (range('A','K') as $col) {    
      $this->hojaActiva->getColumnDimension($col)->setAutoSize(true);
    }

    // Colocamos los bordes a toda la hoja
    /*$estilo = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN
        )
      )
    );

    $this->hojaActiva->getActiveSheet()
      ->getStyle('A1:AA'.$i)->applyFromArray($estilo);
    unset($estilo);
 
    // Colocamos letra negrita a los títulos de la primera fila
    $this->hojaActiva->getActiveSheet()
      ->getStyle("A1:AA1")->getFont()->setBold( true );*/
    
    $f = Date("Y-m-d-His");
    $fecha01 = "ReporteLevantes_".$f.".xlsx";
    
    // Redirecciona la salida al navegador del cliente
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.basename($fecha01).'"');
    header('Cache-Control: max-age=0');

    $objWriter = IOFactory::createWriter($this->objExcel, 'Xlsx');
    $objWriter->save('php://output');
  }
}
?>