<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};

class reporteExcel {
  var $objExcel;
  var $hojaActiva;
  
  function reporteExcel() {
    $this->objExcel = new Spreadsheet();
    $this->hojaActiva = $this->objExcel->getActiveSheet();
    $this->hojaActiva->setTitle("Existencias");
  }
  
  function generarExcel($arreglo) {
    // Colocamos encabezados
    $this->hojaActiva->setCellValue("A1", "No.");
    $this->hojaActiva->setCellValue("B1", "Cliente");
    $this->hojaActiva->setCellValue("C1", "Orden");
    $this->hojaActiva->setCellValue("D1", "Arribo");
    $this->hojaActiva->setCellValue("E1", "Documento TTE");
    $this->hojaActiva->setCellValue("F1", "FMMI");
    $this->hojaActiva->setCellValue("G1", "Consecutivo");
    $this->hojaActiva->setCellValue("H1", "Código Referencia");
    $this->hojaActiva->setCellValue("I1", "Referencia");
    $this->hojaActiva->setCellValue("J1", "M/L/C");
    $this->hojaActiva->setCellValue("K1", "Fecha Ing.");
    $this->hojaActiva->setCellValue("L1", "Ubicación");
    $this->hojaActiva->setCellValue("M1", "Piezas");
    $this->hojaActiva->setCellValue("N1", "Peso");
    $this->hojaActiva->setCellValue("O1", "Valor");
    $this->hojaActiva->setCellValue("P1", "FMMN");
    $this->hojaActiva->setCellValue("Q1", "UN");
    $this->hojaActiva->setCellValue("R1", "Piezas Nal/Nzo");
    $this->hojaActiva->setCellValue("S1", "Piezas Ext.");
    // Inicializamos variables de totales y fila
    $i = $n = 1; $tpiezas = $tpeso = $tvalor = $tpiezas_nal = $tpiezas_ext = 0;
    foreach($arreglo['datos'] as $value) {
      $i++;
      // Mostramos información de registro en cada fila
      $this->hojaActiva->setCellValue('A'.$i, $n);
      $this->hojaActiva->setCellValue('B'.$i, '['.$value['documento'].'] '.$value['nombre_cliente']);
      $this->hojaActiva->setCellValue('C'.$i, $value['orden']);
      $this->hojaActiva->setCellValue('D'.$i, $value['arribo']);
      $this->hojaActiva->setCellValue('E'.$i, $value['doc_tte']);
      $this->hojaActiva->setCellValue('F'.$i, $value['fmm']);
      $this->hojaActiva->setCellValue('G'.$i, $value['codigo']);
      $this->hojaActiva->setCellValue('H'.$i, $value['codigo_ref']);
      $this->hojaActiva->setCellValue('I'.$i, $value['nombre_referencia']);
      $this->hojaActiva->setCellValue('J'.$i, $value['modelo']);
      $this->hojaActiva->setCellValue('K'.$i, $value['fecha']);
      $this->hojaActiva->setCellValue('L'.$i, isset($value['nombre_ubicacion'])?$value['nombre_ubicacion']:'POR ASIGNAR');
      $this->hojaActiva->setCellValue('M'.$i, number_format($value['cantidad'],2));
      $this->hojaActiva->setCellValue('N'.$i, number_format($value['peso'],2));
      $this->hojaActiva->setCellValue('O'.$i, number_format($value['valor'],2));
      $this->hojaActiva->setCellValue('P'.$i, $value['fmmn']);
      $this->hojaActiva->setCellValue('Q'.$i, $value['ubic']);
      $this->hojaActiva->setCellValue('R'.$i, number_format($value['c_nal'],2));
      $this->hojaActiva->setCellValue('S'.$i, number_format($value['c_ext'],2));
      // Acumula Totales
      $tpiezas += $value['cantidad']; $tpeso += $value['peso'];
      $tvalor += $value['valor'];
      $tpiezas_nal += $value['c_nal']; $tpiezas_ext += $value['c_ext']; $n++;
    }
    $i++;
    $this->hojaActiva->setCellValue('A'.$i, 'T O T A L E S');
    $this->hojaActiva->setCellValue('M'.$i, number_format($tpiezas,2));
    $this->hojaActiva->setCellValue('N'.$i, number_format($tpeso,2));
    $this->hojaActiva->setCellValue('O'.$i, number_format($tvalor,2));
    $this->hojaActiva->setCellValue('R'.$i, number_format($tpiezas_nal,2));
    $this->hojaActiva->setCellValue('S'.$i, number_format($tpiezas_ext,2));

    // Ajuste automático de las columnas
    foreach (range('A','S') as $col) {    
      $this->hojaActiva->getColumnDimension($col)->setAutoSize(true);
    }
    /*foreach ($this->hojaActiva->getColumnIterator() as $col) {
       $this->hojaActiva->getColumnDimension($col->getColumnIndex())->setAutoSize(true);
    }*/

    // Colocamos los bordes a toda la hoja
    /*$estilo = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN
        )
      )
    );

    $this->hojaActiva->getActiveSheet()
      ->getStyle('A1:S'.$i)->applyFromArray($estilo);
    unset($estilo);
 
    // Colocamos letra negrita a los títulos de la primera fila
    $this->hojaActiva->getActiveSheet()
      ->getStyle("A1:S1")->getFont()->setBold( true );*/
    
    $f = Date("Y-m-d-His");
    $fecha01 = "Reporte_".$f.".xlsx";
    
    // Redirecciona la salida al navegador del cliente
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.basename($fecha01).'"');
    header('Cache-Control: max-age=0');

    $objWriter = IOFactory::createWriter($this->objExcel, 'Xlsx');
    $objWriter->save('php://output');
  }
}
?>