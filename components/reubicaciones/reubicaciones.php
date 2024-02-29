<?php 
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'reubicaciones/model/reubicaciones.php';
require_once COMPONENTS_PATH . 'reubicaciones/views/vista.php';

class reubicaciones {
	var $vista;
	var $datos;
	
	function reubicaciones() {
		$this->vista = new ReubicacionesVista();
		$this->datos = new ReubicacionesModelo();
	}
	
  function filtroReubicaciones($arreglo) {
		$this->vista->filtroReubicaciones($arreglo);
	}
  
  function listadoReubicaciones($arreglo) {
    $arreglo['datos'] = $this->datos->listadoReubicaciones($arreglo);
    
    $this->vista->listadoReubicaciones($arreglo);
  }
	
  function Reubicar($arreglo) {
    //Procedimiento de Reubicación
    $this->datos->Reubicar($arreglo);
    
    $this->listadoReubicaciones($arreglo);
  }

  function Agregar($arreglo) {
    //Procedimiento de Agregar
    $this->datos->Agregar($arreglo);

    $this->listadoReubicaciones($arreglo);
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
  
  function findReubicaciones($arreglo) {
    $arreglo['q'] = strtolower($_GET["q"]);
    $unaConsulta = $this->datos->findReubicaciones($arreglo);
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
      $codref = trim($value['codigo_ref']);
      $nombre = '['.trim($value['codigo_ref']).'] ' .trim($value['nombre']);
			echo "$nombre|$codigo|$codref\n";
    }
    if($Existe == 0) echo "No hay Resultados|-1\n";
  }
}
?>