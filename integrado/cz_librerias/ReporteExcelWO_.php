<?php
require_once("./libraries/classes/PHPExcel.php");

class ReporteExcelWO {
  var $objPHPExcel;
  var $objWriter;
  
  function ReporteExcelWO() {
    $this->objPHPExcel = new PHPExcel();
  }
  
  function generarExcel($arreglo) {
    // Colocamos encabezados
    $this->objPHPExcel->getActiveSheet()->setCellValue("A1", "Encab: Empresa");
    $this->objPHPExcel->getActiveSheet()->setCellValue("B1", "Encab: Tipo Documento");
    $this->objPHPExcel->getActiveSheet()->setCellValue("C1", "Encab: Prefijo");
    $this->objPHPExcel->getActiveSheet()->setCellValue("D1", "Encab: Documento Número");
    $this->objPHPExcel->getActiveSheet()->setCellValue("E1", "Encab: Fecha");
    $this->objPHPExcel->getActiveSheet()->setCellValue("F1", "Encab: Tercero Interno");
    $this->objPHPExcel->getActiveSheet()->setCellValue("G1", "Encab: Tercero Externo");
    $this->objPHPExcel->getActiveSheet()->setCellValue("H1", "Encab: Nota");
    $this->objPHPExcel->getActiveSheet()->setCellValue("I1", "Encab: FormaPago");
    $this->objPHPExcel->getActiveSheet()->setCellValue("J1", "Encab: Fecha Entrega");
    $this->objPHPExcel->getActiveSheet()->setCellValue("K1", "Encab: Prefijo Documento Externo");
    $this->objPHPExcel->getActiveSheet()->setCellValue("L1", "Encab: Número_Documento_Externo");
    $this->objPHPExcel->getActiveSheet()->setCellValue("M1", "Encab: Verificado");
    $this->objPHPExcel->getActiveSheet()->setCellValue("N1", "Encab: Anulado");
    $this->objPHPExcel->getActiveSheet()->setCellValue("O1", "Encab: Personalizado 1");
    $this->objPHPExcel->getActiveSheet()->setCellValue("P1", "Encab: Personalizado 2");
    $this->objPHPExcel->getActiveSheet()->setCellValue("Q1", "Encab: Personalizado 3");
    $this->objPHPExcel->getActiveSheet()->setCellValue("R1", "Encab: Personalizado 4");
    $this->objPHPExcel->getActiveSheet()->setCellValue("S1", "Encab: Personalizado 5");
    $this->objPHPExcel->getActiveSheet()->setCellValue("T1", "Encab: Personalizado 6");
    $this->objPHPExcel->getActiveSheet()->setCellValue("U1", "Encab: Personalizado 7");
    $this->objPHPExcel->getActiveSheet()->setCellValue("V1", "Encab: Personalizado 8");
    $this->objPHPExcel->getActiveSheet()->setCellValue("W1", "Encab: Personalizado 9");
    $this->objPHPExcel->getActiveSheet()->setCellValue("X1", "Encab: Personalizado 10");
    $this->objPHPExcel->getActiveSheet()->setCellValue("Y1", "Encab: Personalizado 11");
    $this->objPHPExcel->getActiveSheet()->setCellValue("Z1", "Encab: Personalizado 12");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AA1", "Encab: Personalizado 13");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AB1", "Encab: Personalizado 14");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AC1", "Encab: Personalizado 15");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AD1", "Encab: Sucursal");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AE1", "Encab: Clasificación");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AF1", "Detalle: Producto");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AG1", "Detalle: Bodega");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AH1", "Detalle: UnidadDeMedida");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AI1", "Detalle: Cantidad");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AJ1", "Detalle: IVA");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AK1", "Detalle: Valor Unitario");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AL1", "Detalle: Descuento");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AM1", "Detalle: Vencimiento");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AN1", "Detalle: Nota");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AO1", "Detalle: Centro costos");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AP1", "Detalle: Personalizado1");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AQ1", "Detalle: Personalizado2");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AR1", "Detalle: Personalizado3");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AS1", "Detalle: Personalizado4");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AT1", "Detalle: Personalizado5");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AU1", "Detalle: Personalizado6");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AV1", "Detalle: Personalizado7");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AW1", "Detalle: Personalizado8");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AX1", "Detalle: Personalizado9");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AY1", "Detalle: Personalizado10");
    $this->objPHPExcel->getActiveSheet()->setCellValue("AZ1", "Detalle: Personalizado11");
    $this->objPHPExcel->getActiveSheet()->setCellValue("BA1", "Detalle: Personalizado12");
    $this->objPHPExcel->getActiveSheet()->setCellValue("BB1", "Detalle: Personalizado13");
    $this->objPHPExcel->getActiveSheet()->setCellValue("BC1", "Detalle: Personalizado14");
    $this->objPHPExcel->getActiveSheet()->setCellValue("BD1", "Detalle: Personalizado15");
    $this->objPHPExcel->getActiveSheet()->setCellValue("BE1", "Detalle: Código Centro Costos");

    $fila = 2;
    // Inicializamos variables
    for($i=0;$i<$arreglo[filas];$i++) {
      // Mostramos información de registro en cada fila
      $this->objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$fila, $arreglo['empresa'][$i])
                  ->setCellValue('B'.$fila, 'FV')
                  ->setCellValue('C'.$fila, $arreglo['prefijo'][$i])
                  ->setCellValue('D'.$fila, $arreglo['numero_oficial'][$i])
                  ->setCellValue('E'.$fila, $arreglo['fecha_factura'][$i])
                  ->setCellValue('F'.$fila, $arreglo['tercero_interno'][$i])
                  ->setCellValue('G'.$fila, $arreglo['tercero_externo'][$i])
                  ->setCellValue('H'.$fila, $arreglo['nota'][$i])
                  ->setCellValue('I'.$fila, $arreglo['formapago'][$i])
                  ->setCellValue('J'.$fila, $arreglo['fecha_factura'][$i]) // K y L en blanco
                  /*->setCellValue('K'.$i, '')
                  ->setCellValue('L'.$i, '')*/
                  ->setCellValue('M'.$fila, $arreglo['verificada'][$i])
                  ->setCellValue('N'.$fila, $arreglo['anulada'][$i]) // O hasta la AE en blanco
                  /*->setCellValue('O'.$i, '')
                  ->setCellValue('P'.$i, '')
                  ->setCellValue('Q'.$i, '')
                  ->setCellValue('R'.$i, '')
                  ->setCellValue('S'.$i, '')
                  ->setCellValue('T'.$i, '')
                  ->setCellValue('U'.$i, '')
                  ->setCellValue('V'.$i, '')
                  ->setCellValue('W'.$i, '')
                  ->setCellValue('X'.$i, '')
                  ->setCellValue('Y'.$i, '')
                  ->setCellValue('Z'.$i, '')
                  ->setCellValue('AA'.$i, '')
                  ->setCellValue('AB'.$i, '')
                  ->setCellValue('AC'.$i, '')
                  ->setCellValue('AD'.$i, '')
                  ->setCellValue('AE'.$i, '')*/
                  ->setCellValue('AF'.$fila, $arreglo['producto'][$i])
                  ->setCellValue('AG'.$fila, "Principal") // AH en blanco
                  /*->setCellValue('AH'.$i, '')*/
                  ->setCellValue('AI'.$fila, $arreglo['cantidad'][$i])
                  ->setCellValue('AJ'.$fila, $arreglo['iva'][$i])
                  ->setCellValue('AK'.$fila, $arreglo['valor_unitario'][$i])
                  ->setCellValue('AL'.$fila, $arreglo['descuento'][$i])
                  ->setCellValue('AM'.$fila, $arreglo['vencimiento'][$i]);
                  /*->setCellValue('AN'.$i, '')
                  ->setCellValue('AO'.$i, '')
                  ->setCellValue('AP'.$i, '')
                  ->setCellValue('AQ'.$i, '')
                  ->setCellValue('AR'.$i, '')
                  ->setCellValue('AS'.$i, '')
                  ->setCellValue('AT'.$i, '')
                  ->setCellValue('AU'.$i, '')
                  ->setCellValue('AV'.$i, '')
                  ->setCellValue('AW'.$i, '')
                  ->setCellValue('AX'.$i, '')
                  ->setCellValue('AY'.$i, '')
                  ->setCellValue('AZ'.$i, '')
                  ->setCellValue('BA'.$i, '')
                  ->setCellValue('BB'.$i, '')
                  ->setCellValue('BC'.$i, '')
                  ->setCellValue('BD'.$i, '')
                  ->setCellValue('BE'.$i, '');*/
      $fila++;            
    }

    // Ajuste automático de las columnas
    for($col = 'A'; $col !== 'BF'; $col++) {
      $this->objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
    }
         
    $this->objPHPExcel->getActiveSheet()->setTitle('Reporte');
    
    $fecha01 = $arreglo['titulo'].".xlsx";
    
    // Redirecciona la salida al navegador del cliente
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . basename($fecha01) . '"');
    header('Cache-Control: max-age=0');

    $this->objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
    $this->objWriter->save('php://output');
  }
}
?>