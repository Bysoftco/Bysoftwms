<?php
require_once(CLASSES_PATH.'PHPExcel.php');

class reporteExcel {
  var $objPHPExcel;
  var $objWriter;
  
  function reporteExcel() {
    $this->objPHPExcel = new PHPExcel();
  }
  
  function generarExcel($arreglo) {
    // Colocamos encabezados
    $this->objPHPExcel->getActiveSheet()->setCellValue("A1", "No.");
    $this->objPHPExcel->getActiveSheet()->setCellValue("B1", "Cliente");
    $this->objPHPExcel->getActiveSheet()->setCellValue("C1", "Orden");
    $this->objPHPExcel->getActiveSheet()->setCellValue("D1", "Documento TTE");
    $this->objPHPExcel->getActiveSheet()->setCellValue("E1", "Código Referencia");
    $this->objPHPExcel->getActiveSheet()->setCellValue("F1", "Referencia");
    $this->objPHPExcel->getActiveSheet()->setCellValue("G1", "U.Comercial");
    $this->objPHPExcel->getActiveSheet()->setCellValue("H1", "Fecha Expiración");
    $this->objPHPExcel->getActiveSheet()->setCellValue("I1", "Piezas Ing.");
    $this->objPHPExcel->getActiveSheet()->setCellValue("J1", "Piezas Nal.");
    $this->objPHPExcel->getActiveSheet()->setCellValue("K1", "Piezas Ext.");
    $this->objPHPExcel->getActiveSheet()->setCellValue("L1", "Saldo");
    
    // Inicializamos variables de totales y fila
    $i = $n = 1; $tpiezas = $tpiezas_nal = $tpiezas_ext = 0;
    foreach($arreglo['datos'] as $value) {
      $i++;
      // Mostramos información de registro en cada fila
      $this->objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i, $n)
        ->setCellValue('B'.$i, '['.$value['documento'].'] '.$value['nombre_cliente'])
        ->setCellValue('C'.$i, $value['orden'])
        ->setCellValue('D'.$i, $value['doc_tte'])
        ->setCellValue('E'.$i, $value['codigo_ref'])
        ->setCellValue('F'.$i, $value['nombre_referencia'])
        ->setCellValue('G'.$i, $value['ucomercial'])
        ->setCellValue('H'.$i, $value['fecha_expira'])
        ->setCellValue('I'.$i, number_format($value['cantidad'],2))
        ->setCellValue('J'.$i, number_format($value['c_nal'],2))
        ->setCellValue('K'.$i, number_format($value['c_ext'],2))
        ->setCellValue('L'.$i, number_format($value['c_nal']+$value['c_ext'],2));
      // Acumula Totales
      $tpiezas += $value['cantidad']; $tpiezas_nal += $value['c_nal'];
      $tpiezas_ext += $value['c_ext']; $n++;
    }
    $i++;
    $this->objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$i, 'T O T A L E S')
          ->setCellValue('I'.$i, number_format($tpiezas,2))
          ->setCellValue('J'.$i, number_format($tpiezas_nal,2))
          ->setCellValue('K'.$i, number_format($tpiezas_ext,2))
          ->setCellValue('L'.$i, number_format($tpiezas_nal+$tpiezas_ext,2));
    
    $this->objPHPExcel->getActiveSheet()->setTitle('Reporte');
    
    $f = Date("Y-m-d-His");;
    $fecha01 = "Reporte".$f.".xlsx";
    
    // Redirecciona la salida al navegador del cliente
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . basename($fecha01) . '"');
    header('Cache-Control: max-age=0');

    $this->objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
    $this->objWriter->save('php://output');
  }
}
?>