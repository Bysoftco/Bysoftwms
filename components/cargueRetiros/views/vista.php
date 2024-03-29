<?php
require_once(COMPONENTS_PATH.'cargueRetiros/model/cargueRetiros.php');

class cargueRetirosVista {
  var $template;
  var $datos;
	
  function cargueRetirosVista() {
    $this->template = new HTML_Template_IT(COMPONENTS_PATH);
    $this->datos = new cargueRetirosModelo();
  }
  
	function filtrocargueRetiros($arreglo) {
    $this->template->loadTemplateFile('cargueRetiros/views/tmpl/filtrocargueRetiros.php');
    $this->template->setVariable('COMODIN', '');
        
    $this->template->show();
	}
  	
  function validaDatos($arreglo) {
    $this->template->loadTemplateFile('cargueRetiros/views/tmpl/validaDatos.php');
    $this->template->setVariable('COMODIN', '');

    $n = 0;
    //Mostrar registros con novedades    
    foreach($arreglo['datos'] as $value) {
      $n++;
      if($value['F']!="" && $value['F']!='observacion') {
        $this->template->setCurrentBlock("ROW");
        $this->template->setVariable('n', $n);
        $this->template->setVariable('inventario_entrada', $value['A']);
        $this->template->setVariable('fecha', $value['B']);
        $this->template->setVariable('cod_maestro', $value['C']);
        $this->template->setVariable('cantidad_naci', $value['D']);
        $this->template->setVariable('cantidad_nonac', $value['E']);
        $this->template->setVariable('observacion', $value['F']);
        $this->template->parseCurrentBlock("ROW");
      }
    }
    
    $this->template->show();
  }

  function imprimeListadoExistencias($arreglo) {
    $this->template->loadTemplateFile('existencias/views/tmpl/verListadoExistencias.php');
    $this->template->setVariable('COMODIN', '');

    $n = 1; $tpiezas = $tpeso = $tvalor = $tpiezas_nal = $tpiezas_ext = 0;
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('n', $n);
      $this->template->setVariable('doc_cliente', $value['documento']);
      $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
      $this->template->setVariable('orden', $value['orden']);
      $this->template->setVariable('doc_transporte', $value['doc_tte']);
      $this->template->setVariable('manifiesto', $value['manifiesto']);
      $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
      $this->template->setVariable('modelo', $value['modelo']);
      $this->template->setVariable('fecha_ingreso', $value['fecha']);
      $this->template->setVariable('nombre_ubicacion', isset($value['nombre_ubicacion'])?$value['nombre_ubicacion']:'POR ASIGNAR');
      $this->template->setVariable('tipo_ingreso', $value['ingreso']);
      $this->template->setVariable('piezas', number_format($value['cantidad'],2));
      $this->template->setVariable('peso', number_format($value['peso'],2));
      $this->template->setVariable('valor', number_format($value['valor'],2));
      $this->template->setVariable('piezas_nal', number_format($value['c_nal'],2));
      $this->template->setVariable('piezas_ext', number_format($value['c_ext'],2));
      //Acumula Totales
      $tpiezas += $value['cantidad']; $tpeso += $value['peso']; $tvalor += $value['valor'];
      $tpiezas_nal += $value['c_nal']; $tpiezas_ext += $value['c_ext']; $n++;
      $this->template->parseCurrentBlock("ROW");
    }
    $this->template->setVariable('total_piezas', number_format($tpiezas,2));
    $this->template->setVariable('total_peso', number_format($tpeso,2));
    $this->template->setVariable('total_valor', number_format($tvalor,2));
    $this->template->setVariable('total_piezas_nal', number_format($tpiezas_nal,2));
    $this->template->setVariable('total_piezas_ext', number_format($tpiezas_ext,2));
    
    $this->template->show();
  }
}
?>