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
    $this->objPHPExcel->getActiveSheet()->setCellValue("A1", "SO:WHOUT");
    $this->objPHPExcel->getActiveSheet()->setCellValue("B1", "DOCUMENTO TTE");
    $this->objPHPExcel->getActiveSheet()->setCellValue("C1", "PARTIDA");
    $this->objPHPExcel->getActiveSheet()->setCellValue("D1", "REFERENCIA");
    $this->objPHPExcel->getActiveSheet()->setCellValue("E1", "NOMBRE COMERCIAL");
    $this->objPHPExcel->getActiveSheet()->setCellValue("F1", "COLOR");
    $this->objPHPExcel->getActiveSheet()->setCellValue("G1", "ANCHO");
    $this->objPHPExcel->getActiveSheet()->setCellValue("H1", "No. ROLLO");
    $this->objPHPExcel->getActiveSheet()->setCellValue("I1", "PIEZAS/METRO");
    $this->objPHPExcel->getActiveSheet()->setCellValue("J1", "G/M2");
    $this->objPHPExcel->getActiveSheet()->setCellValue("K1", "LIGAMENTO");
    $this->objPHPExcel->getActiveSheet()->setCellValue("L1", "ACABADO");
    $this->objPHPExcel->getActiveSheet()->setCellValue("M1", "TEJIDO");
    $this->objPHPExcel->getActiveSheet()->setCellValue("N1", "PESO POR ROLLO");
    $this->objPHPExcel->getActiveSheet()->setCellValue("O1", "PESO TOTAL");
    $this->objPHPExcel->getActiveSheet()->setCellValue("P1", "PESO ESTIBA");
    $this->objPHPExcel->getActiveSheet()->setCellValue("Q1", "PESO NETO");
    $this->objPHPExcel->getActiveSheet()->setCellValue("R1", "M2");
    $this->objPHPExcel->getActiveSheet()->setCellValue("S1", "VALOR");
    $this->objPHPExcel->getActiveSheet()->setCellValue("T1", "FOB");
    $this->objPHPExcel->getActiveSheet()->setCellValue("U1", "FLETE");
    $this->objPHPExcel->getActiveSheet()->setCellValue("V1", "SEGURO");
    $this->objPHPExcel->getActiveSheet()->setCellValue("W1", "CIF USD");
    $this->objPHPExcel->getActiveSheet()->setCellValue("X1", "CIF COP");
    $this->objPHPExcel->getActiveSheet()->setCellValue("Y1", "ARANCEL");
    $this->objPHPExcel->getActiveSheet()->setCellValue("Z1", "IVA");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AA1", "TOTAL");
    
    // Inicializamos variables de fila
    $i = 1;
    foreach($arreglo['datos'] as $value) {
      $i++;
      $vrarancel = $value['valor'] * $value['arancel'] / 100;
      $vriva = $value['valor'] * $value['iva'] / 100;
      // Mostramos información de registro en cada fila
      $this->objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('B'.$i, $value['doc_tte'])
                  ->setCellValue('C'.$i, $value['partida'])
                  ->setCellValue('D'.$i, $value['codigo_ref'])
                  ->setCellValue('E'.$i, $value['nombre_referencia'])
                  ->setCellValue('G'.$i, $value['ancho'])
                  ->setCellValue('I'.$i, number_format($value['piezas'],2))
                  ->setCellValue('O'.$i, number_format($value['peso'],2))
                  ->setCellValue('S'.$i, number_format($value['valor'],2))
                  ->setCellValue('T'.$i, number_format($value['fob'],2))
                  ->setCellValue('U'.$i, number_format($value['fletes'],2))
                  ->setCellValue('W'.$i, ($value['trm']==0)?0:number_format($value['valor']/$value['trm'],2))
                  ->setCellValue('X'.$i, number_format($value['valor'],2))
                  ->setCellValue('Y'.$i, number_format($vrarancel,2))
                  ->setCellValue('Z'.$i, number_format($vriva,2))
                  ->setCellValue('AA'.$i, number_format($vrarancel+$vriva,2));
    }

    // Ajuste automático de las columnas
    for($col = 'A'; $col !== 'AB'; $col++) {
      $this->objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
    }

    // Colocamos los bordes a toda la hoja
    $estilo = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN
        )
      )
    );

    $this->objPHPExcel->getActiveSheet()
      ->getStyle('A1:AA'.$i)->applyFromArray($estilo);
    unset($estilo);
 
    // Colocamos letra negrita a los títulos de la primera fila
    $this->objPHPExcel->getActiveSheet()
      ->getStyle("A1:AA1")->getFont()->setBold( true );

    $this->objPHPExcel->getActiveSheet()->setTitle('ReporteLevantes');
    
    $f = Date("Y-m-d-His");;
    $fecha01 = "ReporteLevantes_".$f.".xlsx";
    
    // Redirecciona la salida al navegador del cliente
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . basename($fecha01) . '"');
    header('Cache-Control: max-age=0');

    $this->objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
    $this->objWriter->save('php://output');
  }
}
?>