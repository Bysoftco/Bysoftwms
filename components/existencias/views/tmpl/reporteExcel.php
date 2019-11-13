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
    $this->objPHPExcel->getActiveSheet()->setCellValue("D1", "Arribo");
    $this->objPHPExcel->getActiveSheet()->setCellValue("E1", "Documento TTE");
    //$this->objPHPExcel->getActiveSheet()->setCellValue("E1", "Manifiesto");
    $this->objPHPExcel->getActiveSheet()->setCellValue("F1", "FMMI");
    $this->objPHPExcel->getActiveSheet()->setCellValue("G1", "Consecutivo");
    $this->objPHPExcel->getActiveSheet()->setCellValue("H1", "Código Referencia");
    $this->objPHPExcel->getActiveSheet()->setCellValue("I1", "Referencia");
    $this->objPHPExcel->getActiveSheet()->setCellValue("J1", "Fecha Ing.");
    $this->objPHPExcel->getActiveSheet()->setCellValue("K1", "Ubicacion");
    //$this->objPHPExcel->getActiveSheet()->setCellValue("J1", "Tipo Ingreso");
    $this->objPHPExcel->getActiveSheet()->setCellValue("L1", "Piezas");
    $this->objPHPExcel->getActiveSheet()->setCellValue("M1", "Peso");
    $this->objPHPExcel->getActiveSheet()->setCellValue("N1", "Valor");
    $this->objPHPExcel->getActiveSheet()->setCellValue("O1", "FMMN");
    $this->objPHPExcel->getActiveSheet()->setCellValue("P1", "Piezas Nal.");
    $this->objPHPExcel->getActiveSheet()->setCellValue("Q1", "Piezas Ext.");
    
    // Inicializamos variables de totales y fila
    $i = $n = 1; $tpiezas = $tpeso = $tvalor = $tpiezas_nal = $tpiezas_ext = 0;
    foreach($arreglo['datos'] as $value) {
      $i++;
      // Mostramos información de registro en cada fila
      $this->objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, $n)
                  ->setCellValue('B'.$i, '['.$value['documento'].'] '.$value['nombre_cliente'])
                  ->setCellValue('C'.$i, $value['orden'])
                  ->setCellValue('D'.$i, $value['arribo'])
                  ->setCellValue('E'.$i, $value['doc_tte'])
                  //->setCellValue('E'.$i, $value['manifiesto'])
                  ->setCellValue('F'.$i, $value['fmmi'])
                  ->setCellValue('G'.$i, $value['consecutivo'])
                  ->setCellValue('H'.$i, $value['codigo_ref'])
                  ->setCellValue('I'.$i, $value['nombre_referencia'])
                  ->setCellValue('J'.$i, $value['fecha'])
                  ->setCellValue('K'.$i, isset($value['nombre_ubicacion'])?$value['nombre_ubicacion']:'POR ASIGNAR')
                  //->setCellValue('J'.$i, $value['ingreso'])
                  ->setCellValue('L'.$i, number_format($value['cantidad'],2))
                  ->setCellValue('M'.$i, number_format($value['peso'],2))
                  ->setCellValue('N'.$i, number_format($value['valor'],2))
                  ->setCellValue('O'.$i, $value['fmmn'])
                  ->setCellValue('P'.$i, number_format($value['c_nal'],2))
                  ->setCellValue('Q'.$i, number_format($value['c_ext'],2));
      // Acumula Totales
      $tpiezas += $value['cantidad']; $tpeso += $value['peso']; $tvalor += $value['valor'];
      $tpiezas_nal += $value['c_nal']; $tpiezas_ext += $value['c_ext']; $n++;
    }
    $i++;
    $this->objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$i, 'T O T A L E S')
          ->setCellValue('L'.$i, number_format($tpiezas,2))
          ->setCellValue('M'.$i, number_format($tpeso,2))
          ->setCellValue('N'.$i, number_format($tvalor,2))
          ->setCellValue('P'.$i, number_format($tpiezas_nal,2))
          ->setCellValue('Q'.$i, number_format($tpiezas_ext,2));
    
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