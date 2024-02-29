<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};

class reporteExcel {
  var $objExcel;
  var $hojaActiva;
  
  function reporteExcel() {
    $this->objExcel = new Spreadsheet();
    $this->hojaActiva = $this->objExcel->getActiveSheet();
    $this->hojaActiva->setTitle("Mercancías Rechazadas");
  }
  
  function generarExcel($arreglo) {
    // Colocamos encabezados
    $this->hojaActiva->setCellValue("A1", "No.");
    $this->hojaActiva->setCellValue("B1", "Cliente");
    $this->hojaActiva->setCellValue("C1", "Orden");
    $this->hojaActiva->setCellValue("D1", "Documento TTE");
    $this->hojaActiva->setCellValue("E1", "Referencia");
    $this->hojaActiva->setCellValue("F1", "Nombre Referencia");
    $this->hojaActiva->setCellValue("G1", "M/L/C");
    $this->hojaActiva->setCellValue("H1", "Fecha Rechazo");
    $this->hojaActiva->setCellValue("I1", "Ubicación");
    $this->hojaActiva->setCellValue("J1", "Tipo Rechazo");
    $this->hojaActiva->setCellValue("K1", "Piezas Nal.");
    $this->hojaActiva->setCellValue("L1", "Peso Nal.");
    $this->hojaActiva->setCellValue("M1", "Piezas Ext.");
    $this->hojaActiva->setCellValue("N1", "Peso Ext.");
    // Inicializamos variables de totales y fila
    $i = $n = 1; $tpiezas_nal = $tpeso_nal = $tpiezas_ext = $tpeso_ext = 0;
    foreach($arreglo['datos'] as $value) {
      $i++;
      // Mostramos información de registro en cada fila
      $this->hojaActiva->setCellValue('A'.$i, $n);
      $this->hojaActiva->setCellValue('B'.$i, '['.$value['numero_documento'].'] '.$value['razon_social']);
      $this->hojaActiva->setCellValue('C'.$i, $value['orden']);
      $this->hojaActiva->setCellValue('D'.$i, $value['doc_tte']);
      $this->hojaActiva->setCellValue('E'.$i, $value['codigo_ref']);
      $this->hojaActiva->setCellValue('F'.$i, $value['nombre_referencia']);
      $this->hojaActiva->setCellValue('G'.$i, $value['modelo']);
      $this->hojaActiva->setCellValue('H'.$i, date_format(new DateTime($value['fecha_rechazo']),'Y-m-d'));
      $this->hojaActiva->setCellValue('I'.$i, isset($value['nombre_ubicacion'])?$value['nombre_ubicacion']:'POR ASIGNAR');
      $this->hojaActiva->setCellValue('J'.$i, $value['tipo_rechazo']);
      $this->hojaActiva->setCellValue('K'.$i, number_format(abs($value['tc_nal']),2,".",","));
      $this->hojaActiva->setCellValue('L'.$i, number_format(abs($value['tp_nal']),2,".",","));
      $this->hojaActiva->setCellValue('M'.$i, number_format(abs($value['tc_ext']),2,".",","));
      $this->hojaActiva->setCellValue('N'.$i, number_format(abs($value['tp_ext']),2,".",","));
      // Acumula Totales
      $tpiezas_nal += $value['tc_nal']; $tpiezas_ext += $value['tc_ext'];
      $tpeso_nal += $value['tp_nal']; $tpeso_ext += $value['tp_ext']; $n++;
    }
    $i++;
    $this->hojaActiva->setCellValue('A'.$i, 'T O T A L E S');
    $this->hojaActiva->setCellValue('K'.$i, number_format(abs($tpiezas_nal),2));
    $this->hojaActiva->setCellValue('L'.$i, number_format(abs($tpeso_nal),2));
    $this->hojaActiva->setCellValue('M'.$i, number_format(abs($tpiezas_ext),2));
    $this->hojaActiva->setCellValue('N'.$i, number_format(abs($tpeso_ext),2));

    // Ajuste automático de las columnas
    foreach (range('A','N') as $col) {    
      $this->hojaActiva->getColumnDimension($col)->setAutoSize(true);
    }

    $f = Date("Y-m-d-His");
    $fecha01 = "Mercancias_Rechazadas_".$f.".xlsx";
    
    // Redirecciona la salida al navegador del cliente
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.basename($fecha01).'"');
    header('Cache-Control: max-age=0');

    $objWriter = IOFactory::createWriter($this->objExcel, 'Xlsx');
    $objWriter->save('php://output');
  }
}
?>