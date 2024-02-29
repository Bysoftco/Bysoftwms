<?php 
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'ubicaciones/model/ubicaciones.php';
require_once COMPONENTS_PATH . 'ubicaciones/views/vista.php';

class ubicaciones {
	var $vista;
	var $datos;
	
	function ubicaciones() {
		$this->vista = new UbicacionesVista();
		$this->datos = new UbicacionesModelo();
	}
	
  function filtroUbicaciones($arreglo) {
		$this->vista->filtroUbicaciones($arreglo);
	}
  
  function listadoUbicaciones($arreglo) {
    $arreglo['datos'] = $this->datos->listadoUbicaciones($arreglo);
    
    $this->vista->listadoUbicaciones($arreglo);
  }
	
  function imprimeListadoUbicaciones($arreglo) {
    $arreglo['datos'] = $this->datos->listadoUbicaciones($arreglo);
    
    $this->vista->imprimeListadoUbicaciones($arreglo);
  }
  
  function findCliente($arreglo) {
    $arreglo['q'] = strtolower($_GET["q"]);
    $unaConsulta = $this->datos->findCliente($arreglo);
    $Existe = count($unaConsulta); 

    foreach($unaConsulta as $value) {
			$nombre = trim($value['razon_social']);
      $nit = $value['numero_documento'];
			echo "$nombre|$nit\n";
    }
    if($Existe == 0) echo "No hay Resultados|0\n";
  }
  
  function findDocumento($arreglo) {
    $arreglo['q'] = strtolower($_GET["q"]);
    $unaConsulta = $this->datos->findDocumento($arreglo);
    $Existe = count($unaConsulta);
    
    foreach($unaConsulta as $value) {
      $nombre = trim($value['doc_tte'])." [ORDEN] ".trim($value['do_asignado']);
      $doctte = trim($value['doc_tte']);
      $doasignado = $value['do_asignado'];
      echo "$nombre|$doctte|$doasignado\n";
    }
    if($Existe == 0) echo "No hay Resultados|-1\n";
  }
  
  function findUbicacion($arreglo) {
    $arreglo['q'] = strtolower($_GET["q"]);
    $unaConsulta = $this->datos->findUbicacion($arreglo);
    $Existe = count($unaConsulta); 

    foreach($unaConsulta as $value) {
			$codigo = $value['codigo'];
      $nombre = trim($value['nombre']);
			echo "$nombre|$codigo\n";
    }
    if($Existe == 0) echo "No hay Resultados|-1\n";
  }
  
  function findReferencia($arreglo) {
    $arreglo['q'] = strtolower($_GET["q"]);
    $unaConsulta = $this->datos->findReferencia($arreglo);
    $Existe = count($unaConsulta); 

    foreach($unaConsulta as $value) {
			$codigo = $value['codigo'];
      $nombre = trim($value['nombre']);
			echo "$nombre|$codigo\n";
    }
    if($Existe == 0) echo "No hay Resultados|-1\n";
  }
}
?>