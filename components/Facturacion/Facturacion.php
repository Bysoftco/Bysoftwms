<?php 
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'Facturacion/model/DatosFacturacion.php';
require_once COMPONENTS_PATH . 'Facturacion/views/FacturacionVista.php';

class Facturacion{
	
	var $vista;
	var $datos;

	function Facturacion(){
		$this->vista = new FacturacionVista();
		$this->datos = new FacturacionModelo();
	}
	
	function funcionprincipal($arreglo){
		if(!isset($arreglo['pagina']) || empty($arreglo['pagina'])){
			$arreglo['pagina']=1;
		}
		$arreglo['infoDatos']=$this->datos->listarFacturas($arreglo);
		$this->vista->listadoFacturas($arreglo);
	}
}
?>