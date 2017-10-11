<?php 
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'dcosteardo/model/dcosteardo.php';
require_once COMPONENTS_PATH . 'dcosteardo/views/vista.php';

class dcosteardo {
	var $vista;
	var $datos;
	
	function dcosteardo() {
		$this->vista = new DCosteardoVista();
		$this->datos = new DCosteardoModelo();
	}
	
	function listadoDetallec($arreglo) {
		if(!isset($arreglo['pagina']) || empty($arreglo['pagina'])){
			$arreglo['pagina'] = 1;
		}
		$arreglo['datosCostear'] = $this->datos->listadoDetallec($arreglo);
		$this->vista->listadoDetallec($arreglo);
	}
   
	function agregarDetallec($arreglo) {
		$this->vista->agregarDetallec($arreglo);
	}
	
	function editarDetallec($arreglo) {
    $datosDetallec = $this->datos->datosDetallec($arreglo);
    $arreglo['datosDetallec'] = $datosDetallec[0];

		$this->vista->agregarDetallec($arreglo);
	}
	
	function registrar($arreglo) {
		$this->datos->registrar($arreglo);
		$this->listadoDetallec($arreglo);
	}
		
	function eliminarDetallec($arreglo) {
		$this->datos->eliminarDetallec($arreglo);
		$this->listadoDetallec($arreglo);
	}
    
  function findServicio($arreglo) {
    $arreglo[q] = strtolower($_GET["q"]);
    $unaConsulta = $this->datos->findServicio($arreglo);
    $Existe = count($unaConsulta); 

    foreach($unaConsulta as $value) {
			$nombre = trim($value['nombre']);
      $naturaleza = $value['naturaleza'];
      $codigo = $value['codigo'];
      $valor = $value['tarifa_plena_cif'];
			echo "$nombre|$naturaleza|$codigo|$valor\n";
    }
    if($Existe == 0) echo "No hay Resultados|0\n";
  }
}
?>