<?php
class OcupacionVista {
  var $template;

	function OcupacionVista() {
		$this->template = new HTML_Template_IT();
	}


  function filtroOcupacion($arreglo) {
		$this->template->loadTemplateFile(COMPONENTS_PATH . 'ocupacion/views/tmpl/filtroOcupacion.php');
		$this->template->setVariable('COMODIN', '');
    
		$this->template->show();
	}

  function listadoOcupacion($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'ocupacion/views/tmpl/listadoOcupacion.php' );
    $this->template->setVariable('COMODIN', '');
    
    //Visualiza Listado Solicitado
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('nombre_ubicacion', $value['nombre_ubicacion']);
      $this->template->setVariable('doc_cliente', $value['documento']);
      $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
      $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
      $this->template->setVariable('doc_transporte', $value['doc_tte']);
      $this->template->setVariable('orden', $value['orden']);
      $this->template->parseCurrentBlock("ROW");
    }
    
    $this->template->show();
  }

  function imprimeListadoOcupacion($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'ocupacion/views/tmpl/verListadoOcupacion.php' );
    $this->template->setVariable('COMODIN', '');

    //Visualiza Ocupaciones Solicitadas
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('nombre_ubicacion', $value['nombre_ubicacion']);
      $this->template->setVariable('doc_cliente', $value['documento']);
      $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
      $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
      $this->template->setVariable('doc_transporte', $value['doc_tte']);
      $this->template->setVariable('orden', $value['orden']);
      $this->template->parseCurrentBlock("ROW");
    }
    
    $this->template->show();
  }
}
?>