<?php 
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'template/model/template.php';
require_once COMPONENTS_PATH . 'template/views/vista.php';

class template {
	var $vista;
	var $datos;
	
	function template() {
		$this->vista = new TemplateVista();
		$this->datos = new TemplateModelo();
	}	
	
	function armar_header() {
		$this->vista->armar_header();
	}
	
	function armar_footer() {
		$this->vista->armar_footer();
	}
}
?>