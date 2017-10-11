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
    $this->objPHPExcel->getActiveSheet()->setCellValue("B1", "Orden");
    $this->objPHPExcel->getActiveSheet()->setCellValue("C1", "Documento Transporte");
    $this->objPHPExcel->getActiveSheet()->setCellValue("D1", "Cliente");
    $this->objPHPExcel->getActiveSheet()->setCellValue("E1", "Modelo/Lote/Cosecha");
    $this->objPHPExcel->getActiveSheet()->setCellValue("F1", "Controles");
    $this->objPHPExcel->getActiveSheet()->setCellValue("G1", "Piezas Iniciales");
    $this->objPHPExcel->getActiveSheet()->setCellValue("H1", "Peso Inicial");
    
    // Inicializamos variables de totales y fila
    $i = $n = 1;
    foreach($arreglo['datos'] as $value) {
      $i++;
      // Mostramos información de registro en cada fila
      $this->objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, $n)
                  ->setCellValue('B'.$i, $value['orden'])
                  ->setCellValue('C'.$i, $value['doc_tte'])
                  ->setCellValue('D'.$i, '['.$value['doc_cliente'].'] '.$value['nombre_cliente'])
                  ->setCellValue('E'.$i, $value['modelo'])
                  ->setCellValue('F'.$i, $value['controles'])
                  ->setCellValue('G'.$i, number_format($value['piezas'],2))
                  ->setCellValue('H'.$i, number_format($value['peso'],2));

      $n++;
    }
    
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