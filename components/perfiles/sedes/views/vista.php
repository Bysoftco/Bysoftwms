<?php
require_once COMPONENTS_PATH . 'sedes/model/sedes.php';

class SedesVista {
	
  function SedesVista() {
    $this->template = new HTML_Template_IT();
  }
	
	function cambiarSede($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'sedes/views/tmpl/cambiarSede.php' );
    $this->template->setVariable('COMODIN', '');
    $this->template->setVariable('select_sede', $arreglo['select_sede']);
    
    $this->template->show();
	}
}
?>