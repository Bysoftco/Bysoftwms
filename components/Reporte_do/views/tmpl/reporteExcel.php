<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};

class reporteExcel {
  var $objExcel;
  var $hojaActiva;
  
  function reporteExcel() {
    $this->objExcel = new Spreadsheet();
    $this->hojaActiva = $this->objExcel->getActiveSheet();
    $this->hojaActiva->setTitle("Órdenes Ingresos");
  }
  
  function generarExcel($arreglo) {
    // Colocamos encabezados
    $this->hojaActiva->setCellValue("A1", "No.");
    $this->hojaActiva->setCellValue("B1", "Orden");
    $this->hojaActiva->setCellValue("C1", "Documento Transporte");
    $this->hojaActiva->setCellValue("D1", "Cliente");
    $this->hojaActiva->setCellValue("E1", "Modelo/Lote/Cosecha");
    $this->hojaActiva->setCellValue("F1", "Controles");
    $this->hojaActiva->setCellValue("G1", "Piezas Iniciales");
    $this->hojaActiva->setCellValue("H1", "Peso Inicial");    
    // Inicializamos variables de totales y fila
    $i = $n = 1;
    foreach($arreglo['datos'] as $value) {
      $i++;
      // Mostramos información de registro en cada fila
      $this->hojaActiva->setCellValue('A'.$i,$n);
      $this->hojaActiva->setCellValue('B'.$i,$value['orden']);
      $this->hojaActiva->setCellValue('C'.$i,$value['doc_tte']);
      $this->hojaActiva->setCellValue('D'.$i,'['.$value['doc_cliente'].'] '.$value['nombre_cliente']);
      $this->hojaActiva->setCellValue('E'.$i,$value['modelo']);
      $this->hojaActiva->setCellValue('F'.$i,$value['controles']);
      $this->hojaActiva->setCellValue('G'.$i,number_format($value['piezas'],2));
      $this->hojaActiva->setCellValue('H'.$i,number_format($value['peso'],2));
      $n++;
    }

    // Ajuste automático de las columnas
    foreach (range('A','H') as $col) {    
      $this->hojaActiva->getColumnDimension($col)->setAutoSize(true);
    }
    
    $f = Date("Y-m-d-His");;
    $fecha01 = "Ordenes_Ingresos_".$f.".xlsx";
    
    // Redirecciona la salida al navegador del cliente
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'.basename($fecha01).'"');
    header('Cache-Control: max-age=0');

    $objWriter = IOFactory::createWriter($this->objExcel, 'Xlsx');
    $objWriter->save('php://output');
  }
}
?>