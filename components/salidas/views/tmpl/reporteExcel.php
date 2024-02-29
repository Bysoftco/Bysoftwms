<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};

class reporteExcel {
  var $objExcel;
  var $hojaActiva;
  
  function reporteExcel() {
    $this->objExcel = new Spreadsheet();
    $this->hojaActiva = $this->objExcel->getActiveSheet();
    $this->hojaActiva->setTitle("Salida Mercancía");
  }
  
  function generarExcel($arreglo) {
    // Colocamos encabezados
    $this->hojaActiva->setCellValue("A1", "No.");
    $this->hojaActiva->setCellValue("B1", "Cliente");
    $this->hojaActiva->setCellValue("C1", "Orden");
    $this->hojaActiva->setCellValue("D1", "Arribo");
    $this->hojaActiva->setCellValue("E1", "Documento TTE");
    $this->hojaActiva->setCellValue("F1", "FMMI");
    $this->hojaActiva->setCellValue("G1", "Código Referencia");
    $this->hojaActiva->setCellValue("H1", "Referencia");
    $this->hojaActiva->setCellValue("I1", "M/L/C");
    $this->hojaActiva->setCellValue("J1", "Fecha Retiro");
    $this->hojaActiva->setCellValue("K1", "Destino");
    $this->hojaActiva->setCellValue("L1", "Piezas");
    $this->hojaActiva->setCellValue("M1", "Peso");
    $this->hojaActiva->setCellValue("N1", "Piezas Nal/Nzo");
    $this->hojaActiva->setCellValue("O1", "Piezas Ext.");
    // Inicializamos variables de totales y fila
    $i = $n = 1; $tpiezas = $tpeso = $tpiezas_nal = $tpiezas_ext = 0;
    foreach($arreglo['datos'] as $value) {
      $i++;
      //Cálculo cantidad y peso de retiros o salidas
      //Salidas de Mercancía - Cantidad
      $spiezas_nal = abs($value['c_ret_nal']) + abs($value['c_sptr_nal']) + abs($value['c_prtt_nal']) + abs($value['c_kit_nal']);
      $spiezas_ext = abs($value['c_ret_ext']) + abs($value['c_sptr_ext']) + abs($value['c_prtt_ext']) + abs($value['c_kit_ext']);
      $spiezas = $spiezas_nal + $spiezas_ext + abs($value['c_rpk']);
      //Salidas de Mercancía - Peso
      $speso_nal = abs($value['p_ret_nal']) + abs($value['p_sptr_nal']) + abs($value['p_prtt_nal']) + abs($value['p_kit_nal']);
      $speso_ext = abs($value['p_ret_ext']) + abs($value['p_sptr_ext']) + abs($value['p_prtt_ext']) + abs($value['p_kit_ext']);
      $speso = $speso_nal + $speso_ext + abs($value['p_rpk']);      
      // Mostramos información de registro en cada fila
      $this->hojaActiva->setCellValue('A'.$i, $n);
      $this->hojaActiva->setCellValue('B'.$i, '['.$value['documento'].'] '.$value['nombre_cliente']);
      $this->hojaActiva->setCellValue('C'.$i, $value['orden']);
      $this->hojaActiva->setCellValue('D'.$i, $value['arribo']);
      $this->hojaActiva->setCellValue('E'.$i, $value['doc_tte']);
      $this->hojaActiva->setCellValue('F'.$i, $value['fmm']);
      $this->hojaActiva->setCellValue('G'.$i, $value['codigo_ref']);
      $this->hojaActiva->setCellValue('H'.$i, $value['nombre_referencia']);
      $this->hojaActiva->setCellValue('I'.$i, $value['modelo']);
      $this->hojaActiva->setCellValue('J'.$i, $value['fecha']);
      $this->hojaActiva->setCellValue('K'.$i, isset($value['destino'])?$value['destino']:'POR ASIGNAR');
      $this->hojaActiva->setCellValue('L'.$i, number_format($spiezas,2));
      $this->hojaActiva->setCellValue('M'.$i, number_format($speso,2));
      $this->hojaActiva->setCellValue('N'.$i, number_format($spiezas_nal,2));
      $this->hojaActiva->setCellValue('O'.$i, number_format($spiezas_ext,2));
      // Acumula Totales
      $tpiezas += $spiezas; $tpeso += $speso;
      $tpiezas_nal += $spiezas_nal; $tpiezas_ext += $spiezas_ext; $n++;
    }
    $i++;
    $this->hojaActiva->setCellValue('A'.$i, 'T O T A L E S');
    $this->hojaActiva->setCellValue('L'.$i, number_format($tpiezas,2));
    $this->hojaActiva->setCellValue('M'.$i, number_format($tpeso,2));
    $this->hojaActiva->setCellValue('N'.$i, number_format($tpiezas_nal,2));
    $this->hojaActiva->setCellValue('O'.$i, number_format($tpiezas_ext,2));

    // Ajuste automático de las columnas
    foreach (range('A','O') as $col) {    
      $this->hojaActiva->getColumnDimension($col)->setAutoSize(true);
    }

    // Colocamos los bordes a toda la hoja
    /*$estilo = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN
        )
      )
    );

    $this->hojaActiva->getActiveSheet()
      ->getStyle('A1:O'.$i)->applyFromArray($estilo);
    unset($estilo);
 
    // Colocamos letra negrita a los títulos de la primera fila
    $this->hojaActiva->getActiveSheet()
      ->getStyle("A1:O1")->getFont()->setBold( true );*/
    
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