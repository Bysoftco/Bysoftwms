<?php
require_once(CLASSES_PATH.'PHPExcel.php');
echo CLASSES_PATH;
die();

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
    $this->objPHPExcel->getActiveSheet()->setCellValue("E1", "Referencia");
    $this->objPHPExcel->getActiveSheet()->setCellValue("F1", "Nombre Referencia");
    $this->objPHPExcel->getActiveSheet()->setCellValue("G1", "M/L/C");
    $this->objPHPExcel->getActiveSheet()->setCellValue("H1", "Fecha Rechazo");
    $this->objPHPExcel->getActiveSheet()->setCellValue("I1", "Ubicación");
    $this->objPHPExcel->getActiveSheet()->setCellValue("J1", "Tipo Rechazo");
    $this->objPHPExcel->getActiveSheet()->setCellValue("K1", "Piezas Nal.");
    $this->objPHPExcel->getActiveSheet()->setCellValue("L1", "Peso Nal.");
    $this->objPHPExcel->getActiveSheet()->setCellValue("M1", "Piezas Ext.");
    $this->objPHPExcel->getActiveSheet()->setCellValue("N1", "Peso Ext.");
    
    // Inicializamos variables de totales y fila
    $i = $n = 1; $tpiezas_nal = $tpeso_nal = $tpiezas_ext = $tpeso_ext = 0;
    foreach($arreglo['datos'] as $value) {
      $i++;
	  
	  
	  	//var_dump($arreglo['datos']);die();
      // Mostramos información de registro en cada fila
      $this->objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, $n)
                  ->setCellValue('B'.$i, '['.$value['numero_documento'].'] '.$value['razon_social'])
                  ->setCellValue('C'.$i, $value['orden'])
                  ->setCellValue('D'.$i, $value['doc_tte'])
                  ->setCellValue('E'.$i, $value['codigo_ref'])
                  ->setCellValue('F'.$i, $value['nombre_referencia'])
                  ->setCellValue('G'.$i, $value['modelo'])
                  ->setCellValue('H'.$i, date_format(new DateTime($value['fecha']),'Y-m-d'))
                  ->setCellValue('I'.$i, isset($value['nombre_ubicacion'])?$value['nombre_ubicacion']:'POR ASIGNAR')
                  ->setCellValue('J'.$i, $value['tipo_rechazo'])
                  ->setCellValue('K'.$i, number_format(abs($value['tc_nal']),2))
                  ->setCellValue('L'.$i, number_format(abs($value['tp_nal']),2))
                  ->setCellValue('M'.$i, number_format(abs($value['tc_ext']),2))
                  ->setCellValue('N'.$i, number_format(abs($value['tp_ext']),2));
      // Acumula Totales
      $tpiezas_nal += $value['tc_nal']; $tpiezas_ext += $value['tc_ext'];
      $tpeso_nal += $value['tp_nal']; $tpeso_ext += $value['tp_ext']; $n++;
    }
    $i++;
    $this->objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$i, 'T O T A L E S')
          ->setCellValue('K'.$i, number_format(abs($tpiezas_nal),2))
          ->setCellValue('L'.$i, number_format(abs($tpeso_nal),2))
          ->setCellValue('M'.$i, number_format(abs($tpiezas_ext),2))
          ->setCellValue('N'.$i, number_format(abs($tpeso_ext),2));
    
    $this->objPHPExcel->getActiveSheet()->setTitle('Rechazadas');
    
    $f = Date("Y-m-d-His");;
    $fecha01 = "Listado_Rechazadas_".$f.".xlsx";
    
    // Redirecciona la salida al navegador del cliente
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . basename($fecha01) . '"');
    header('Cache-Control: max-age=0');

    $this->objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
    $this->objWriter->save('php://output');
  }
}
?>