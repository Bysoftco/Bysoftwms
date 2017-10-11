<?php
class UbicacionesVista {
  var $template;

	function UbicacionesVista() {
		$this->template = new HTML_Template_IT();
	}


  function filtroUbicaciones($arreglo) {
		$this->template->loadTemplateFile(COMPONENTS_PATH . 'ubicaciones/views/tmpl/filtroUbicaciones.php');
		$this->template->setVariable('COMODIN', '');
    
		$this->template->show();
	}

  function listadoUbicaciones($arreglo) {
    $ruta = !$arreglo['todos'] ? 'ubicaciones/views/tmpl/listadoUbicaciones.php' : 'ubicaciones/views/tmpl/soloUbicaciones.php';
    $this->template->loadTemplateFile( COMPONENTS_PATH . $ruta );
    $this->template->setVariable('COMODIN', '');
    
    //Valida visualización solo Ubicaciones
    if(!$arreglo['todos']) {
      $tpiezas = $tpeso = 0;
      foreach($arreglo['datos'] as $value) {
        $this->template->setCurrentBlock("ROW");
        $this->template->setVariable('nombre_ubicacion', $value['nombre_ubicacion']);
        $this->template->setVariable('doc_cliente', $value['documento']);
        $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
        $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
        $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
        $this->template->setVariable('doc_transporte', $value['doc_tte']);
        $this->template->setVariable('orden', $value['orden']);
        $this->template->setVariable('piezas', number_format($value['cantidad'],2));
        $this->template->setVariable('peso', number_format($value['peso'],2));
        //Acumula Totales
        $tpiezas += $value['cantidad']; $tpeso += $value['peso'];
        $this->template->parseCurrentBlock("ROW");
      }
      $this->template->setVariable('total_piezas', number_format($tpiezas,2));
      $this->template->setVariable('total_peso', number_format($tpeso,2));      
    } else {
      foreach($arreglo['datos'] as $value) {
        $this->template->setCurrentBlock("ROW");
        $this->template->setVariable('nombre_ubicacion', $value['nombre_ubicacion']);
        $this->template->parseCurrentBlock("ROW");
      }      
    }
    
    $this->template->show();
  }

  function imprimeListadoUbicaciones($arreglo) {
    $ruta = !$arreglo['todos'] ? 'ubicaciones/views/tmpl/verListadoUbicaciones.php' : 'ubicaciones/views/tmpl/verSoloUbicaciones.php';
    $this->template->loadTemplateFile( COMPONENTS_PATH . $ruta );
    $this->template->setVariable('COMODIN', '');

    //Valida visualización solo Ubicaciones
    if(!$arreglo['todos']) {
      $tpiezas = $tpeso = 0;
      foreach($arreglo['datos'] as $value) {
        $this->template->setCurrentBlock("ROW");
        $this->template->setVariable('nombre_ubicacion', isset($value['nombre_ubicacion'])?$value['nombre_ubicacion']:'POR ASIGNAR');
        $this->template->setVariable('doc_cliente', $value['documento']);
        $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
        $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
        $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
        $this->template->setVariable('doc_transporte', $value['doc_tte']);
        $this->template->setVariable('orden', $value['orden']);
        $this->template->setVariable('piezas', number_format($value['cantidad'],2));
        $this->template->setVariable('peso', number_format($value['peso'],2));
        //Acumula Totales
        $tpiezas += $value['cantidad']; $tpeso += $value['peso'];
        $this->template->parseCurrentBlock("ROW");
      }
      $this->template->setVariable('total_piezas', number_format($tpiezas,2));
      $this->template->setVariable('total_peso', number_format($tpeso,2));
    } else {
      foreach($arreglo['datos'] as $value) {
        $this->template->setCurrentBlock("ROW");
        $this->template->setVariable('nombre_ubicacion', $value['nombre_ubicacion']);
        $this->template->parseCurrentBlock("ROW");
      }
    }
    
    $this->template->show();
  }
}
?>