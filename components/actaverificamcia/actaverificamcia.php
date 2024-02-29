<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once(COMPONENTS_PATH.'actaverificamcia/model/actaverificamcia.php');
require_once(COMPONENTS_PATH.'actaverificamcia/views/vista.php');

class actaverificamcia {
  var $vista;
  var $datos;

  function actaverificamcia() {
    $this->vista = new actaverVista();
    $this->datos = new actaverModelo();
  }

	function verActa($arreglo) {
		$this->vista->verActa($arreglo);
	}
	
	function imprimirActa($arreglo) {
		$this->vista->imprimirActa($arreglo);
	}
} 
?>