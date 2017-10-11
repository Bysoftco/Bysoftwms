<?php
class ReubicacionesVista {
  var $template;

	function ReubicacionesVista() {
		$this->template = new HTML_Template_IT();
	}


  function filtroReubicaciones($arreglo) {
		$this->template->loadTemplateFile(COMPONENTS_PATH . 'reubicaciones/views/tmpl/filtroReubicaciones.php');
		$this->template->setVariable('COMODIN', '');
    
		$this->template->show();
	}

  function listadoReubicaciones($arreglo) {
    $this->template->loadTemplateFile(COMPONENTS_PATH . 'reubicaciones/views/tmpl/listadoReubicaciones.php');
    $this->template->setVariable('COMODIN', '');

    //Conservación datos del filtro
    $this->template->setVariable('nitr',$arreglo[nitr]);
    $this->template->setVariable('doctter',$arreglo[doctter]);
    $this->template->setVariable('doasignador',$arreglo[doasignador]);
    $this->template->setVariable('ubicacionr',$arreglo[ubicacionr]);
    $this->template->setVariable('referenciar',$arreglo[referenciar]);
    //Inicializa el número de registro
    $n = 0; $tr = count($arreglo['datos']); //Total de Registros
    //Visualiza Listado Solicitado
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('n', ++$n);
      $this->template->setVariable('nombre_ubicacion', $value['nombre_ubicacion']);
      $this->template->setVariable('codigo', $value['codigo']);
      $this->template->setVariable('doc_cliente', $value['documento']);
      $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
      $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
      $this->template->setVariable('doc_transporte', $value['doc_tte']);
      $this->template->setVariable('orden', $value['orden']);
      $this->template->setVariable('fecha_reubica', $value['fecha_reubica']=='0000-00-00'?'':$value['fecha_reubica']);
      $this->template->parseCurrentBlock("ROW");
    }
    $this->template->setVariable('tr',$tr);
        
    $this->template->show();
  }

  function imprimeListadoOcupacion($arreglo) {
    $ruta = !$arreglo['todos'] ? 'ocupacion/views/tmpl/verListadoOcupacion.php' : 'ocupacion/views/tmpl/verSoloOcupacion.php';
    $this->template->loadTemplateFile( COMPONENTS_PATH . $ruta );
    $this->template->setVariable('COMODIN', '');

    //Valida visualización solo Ubicaciones
    if(!$arreglo['todos']) {
      foreach($arreglo['datos'] as $value) {
        $this->template->setCurrentBlock("ROW");
        $this->template->setVariable('nombre_ubicacion', isset($value['nombre_ubicacion'])?$value['nombre_ubicacion']:'POR ASIGNAR');
        $this->template->setVariable('doc_cliente', $value['documento']);
        $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
        $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
        $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
        $this->template->setVariable('doc_transporte', $value['doc_tte']);
        $this->template->setVariable('orden', $value['orden']);
        $this->template->setVariable('rango', $value['rango']);
        $this->template->setVariable('inicio', $value['nombre_ubicacion']);
        $this->template->setVariable('fin', $value['nombre_final']);
        $this->template->setVariable('numpos', $value['numpos']);
        $this->template->parseCurrentBlock("ROW");
      }
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