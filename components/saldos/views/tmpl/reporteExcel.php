<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};

class reporteExcel {
  var $objExcel;
  var $hojaActiva;
  
  function reporteExcel() {
    $this->objExcel = new Spreadsheet();
    $this->hojaActiva = $this->objExcel->getActiveSheet();
    $this->hojaActiva->setTitle("Saldos Mercancía");
  }
  
  function generarExcel($arreglo) {
    // Colocamos encabezados
    $this->hojaActiva->setCellValue("A1", "No.");
    $this->hojaActiva->setCellValue("B1", "Cliente");
    $this->hojaActiva->setCellValue("C1", "Orden");
    $this->hojaActiva->setCellValue("D1", "Documento TTE");
    $this->hojaActiva->setCellValue("E1", "Código Referencia");
    $this->hojaActiva->setCellValue("F1", "Referencia");
    $this->hojaActiva->setCellValue("G1", "U.Comercial");
    $this->hojaActiva->setCellValue("H1", "Fecha Expiración");
    $this->hojaActiva->setCellValue("I1", "Piezas Ing.");
    $this->hojaActiva->setCellValue("J1", "Piezas Nal.");
    $this->hojaActiva->setCellValue("K1", "Piezas Ext.");  
    $this->hojaActiva->setCellValue("L1", "Retiro Ext");
    $this->hojaActiva->setCellValue("M1", "Retiro Nal");
    $this->hojaActiva->setCellValue("N1", "Saldo");
    // Inicializamos variables de totales y fila
    $i = $n = 1; $tpiezas = $tpiezas_nal = $tpiezas_ext = 0;
    $tret_ext = $tret_nal = 0;
    foreach($arreglo['datos'] as $value) {
      $i++;
      // Mostramos información de registro en cada fila
      $this->hojaActiva->setCellValue('A'.$i, $n);
      $this->hojaActiva->setCellValue('B'.$i, '['.$value['documento'].'] '.$value['nombre_cliente']);
      $this->hojaActiva->setCellValue('C'.$i, $value['orden']);
      $this->hojaActiva->setCellValue('D'.$i, $value['doc_tte']);
      $this->hojaActiva->setCellValue('E'.$i, $value['codigo_ref']);
      $this->hojaActiva->setCellValue('F'.$i, $value['nombre_referencia']);
      $this->hojaActiva->setCellValue('G'.$i, $value['ucomercial']);
      $this->hojaActiva->setCellValue('H'.$i, $value['fecha_expira']);
      $this->hojaActiva->setCellValue('I'.$i, number_format(abs($value['cantidad']),2));
      $this->hojaActiva->setCellValue('J'.$i, number_format(abs($value['c_nal']),2));
      $this->hojaActiva->setCellValue('K'.$i, number_format(abs($value['c_ext']),2));
      $this->hojaActiva->setCellValue('L'.$i, number_format(abs($value['c_ret_ext']),2));
      $this->hojaActiva->setCellValue('M'.$i, number_format(abs($value['c_ret_nal']),2));
      $this->hojaActiva->setCellValue('N'.$i, number_format(abs($value['c_nal']+$value['c_ext']),2));
      //Acumulamos Totales
      $tpiezas += abs($value['cantidad']); $tpiezas_nal += abs($value['c_nal']);
      $tpiezas_ext += abs($value['c_ext']); $n++;
  	  $tret_ext += abs($value['c_ret_ext']);
  	  $tret_nal += abs($value['c_ret_nal']);
    }
    $i++;
    $this->hojaActiva->setCellValue('A'.$i, 'T O T A L E S');
    $this->hojaActiva->setCellValue('I'.$i, number_format($tpiezas,2));
    $this->hojaActiva->setCellValue('J'.$i, number_format($tpiezas_nal,2));
    $this->hojaActiva->setCellValue('K'.$i, number_format($tpiezas_ext,2));   
		$this->hojaActiva->setCellValue('L'.$i, number_format($tret_ext,2));
		$this->hojaActiva->setCellValue('M'.$i, number_format($tret_nal,2));
		$this->hojaActiva->setCellValue('N'.$i, number_format($tpiezas_nal+$tpiezas_ext,2));

    // Ajuste automático de las columnas
    foreach (range('A','N') as $col) {    
      $this->hojaActiva->getColumnDimension($col)->setAutoSize(true);
    }

    $f = Date("Y-m-d-His");;
    $fecha01 = "Saldos_Mercancia_".$f.".xlsx";
    
    // Redirecciona la salida al navegador del cliente
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.basename($fecha01).'"');
    header('Cache-Control: max-age=0');

    $objWriter = IOFactory::createWriter($this->objExcel, 'Xlsx');
    $objWriter->save('php://output');
  }
}
?>