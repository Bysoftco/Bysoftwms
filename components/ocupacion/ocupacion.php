<?php 
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'ocupacion/model/ocupacion.php';
require_once COMPONENTS_PATH . 'ocupacion/views/vista.php';

class ocupacion {
	var $vista;
	var $datos;
	
	function ocupacion() {
		$this->vista = new OcupacionVista();
		$this->datos = new OcupacionModelo();
	}
	
  function filtroOcupacion($arreglo) {
		$this->vista->filtroOcupacion($arreglo);
	}
  
  function listadoOcupacion($arreglo) {
    $arreglo['datos'] = $this->datos->listadoOcupacion($arreglo);
    
    $this->vista->listadoOcupacion($arreglo);
  }
	
  function imprimeListadoOcupacion($arreglo) {
    $arreglo['datos'] = $this->datos->listadoOcupacion($arreglo);
    
    $this->vista->imprimeListadoOcupacion($arreglo);
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
  
  function findOcupacion($arreglo) {
    $arreglo['q'] = strtolower($_GET["q"]);
    $unaConsulta = $this->datos->findOcupacion($arreglo);
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
 //control
    foreach($unaConsulta as $value) {
			$codigo = $value['codigo'];
			$codref = trim($value['codigo_ref']);
      $nombre = '['.trim($value['codigo_ref']).'] ' .trim($value['nombre']);
			echo "$nombre|$codigo|$codref\n";
    }
    if($Existe == 0) echo "No hay Resultados|-1\n";
  }
  
  function Eliminar($arreglo) {
    $this->datos->Eliminar($arreglo);
    $this->listadoOcupacion($arreglo);
  }
}
?>