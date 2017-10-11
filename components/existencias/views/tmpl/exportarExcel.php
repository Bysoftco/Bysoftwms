<?php
require_once 'Spreadsheet/Excel/Writer.php';

class exportarExcel {

  function exportarExcel() { }
  
  function generarExcel($arreglo) {
    // Especificamos directamente el nombre del archivo
    $workbook = new Spreadsheet_Excel_Writer();

    // Enviamos encabezados HTTP
    $workbook->send('Listado_General_de_Existencias.xls');

    // Creación de la Hoja de Trabajo
    $worksheet =& $workbook->addWorksheet('Existencias');

    // Colocamos Nombre de las Columnas
    $worksheet->write(0, 0, 'No.');
    $worksheet->write(0, 1, 'Cliente');
    $worksheet->write(0, 2, 'Orden');
    $worksheet->write(0, 3, 'Documento TTE');
    $worksheet->write(0, 4, 'Manifiesto');
    $worksheet->write(0, 5, 'Referencia');
    $worksheet->write(0, 6, 'Fecha Ing.');
    $worksheet->write(0, 7, 'Ubicacion');
    $worksheet->write(0, 8, 'Tipo Ingreso');
    $worksheet->write(0, 9, 'Piezas');
    $worksheet->write(0, 10, 'Peso');
    $worksheet->write(0, 11, 'Valor');
    $worksheet->write(0, 12, 'Piezas Nal.');
    $worksheet->write(0, 13, 'Piezas Ext.');
    
    $n = 1; $tpiezas = $tpeso = $tvalor = $tpiezas_nal = $tpiezas_ext = 0;
    foreach($arreglo['datos'] as $value) {
      // Colocamos cada registro en una fila específica
      $worksheet->write($n, 0, $n);
      $worksheet->write($n, 1, '['.$value['documento'].'] '.$value['nombre_cliente']);
      $worksheet->write($n, 2, $value['orden']);
      $worksheet->write($n, 3, $value['doc_tte']);
      $worksheet->write($n, 4, $value['manifiesto']);
      $worksheet->write($n, 5, '['.$value['codigo_ref'].'] '.$value['nombre_referencia']);
      $worksheet->write($n, 6, $value['fecha']);
      $worksheet->write($n, 7, isset($value['nombre_ubicacion'])?$value['nombre_ubicacion']:'POR ASIGNAR');
      $worksheet->write($n, 8, $value['ingreso']);
      $worksheet->write($n, 9, number_format($value['cantidad'],2));
      $worksheet->write($n, 10, number_format($value['peso'],2));
      $worksheet->write($n, 11, number_format($value['valor'],2));
      $worksheet->write($n, 12, number_format($value['c_nal'],2));
      $worksheet->write($n, 13, number_format($value['c_ext'],2));      
      //Acumula Totales
      $tpiezas += $value['cantidad']; $tpeso += $value['peso']; $tvalor += $value['valor'];
      $tpiezas_nal += $value['c_nal']; $tpiezas_ext += $value['c_ext']; $n++;
    }
    $worksheet->write($n, 0, 'T O T A L E S');
    $worksheet->write($n, 9, number_format($tpiezas,2));
    $worksheet->write($n, 10, number_format($tpeso,2));
    $worksheet->write($n, 11, number_format($tvalor,2));
    $worksheet->write($n, 12, number_format($tpiezas_nal,2));
    $worksheet->write($n, 13, number_format($tpiezas_ext,2));
    
    // Cerramos explicitamente el Libro
    $workbook->close();
  }  
}
?>