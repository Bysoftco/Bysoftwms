<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once(COMPONENTS_PATH.'existencias/model/existencias.php');
require_once(COMPONENTS_PATH.'existencias/views/vista.php');
require_once(COMPONENTS_PATH.'existencias/views/tmpl/reporteExcel.php');

class existencias {
  var $vista;
  var $datos;

  function existencias() {
    $this->vista = new ExistenciasVista();
    $this->datos = new ExistenciasModelo();
  }

	function filtroExistencias($arreglo) {
		$this->vista->filtroExistencias($arreglo);
	}
	
  function listadoExistencias($arreglo) {
    $arreglo['datos'] = $this->datos->listadoExistencias($arreglo);
    
    $this->vista->listadoExistencias($arreglo);
  }
  
  function imprimeListadoExistencias($arreglo) {
    $arreglo['datos'] = $this->datos->listadoExistencias($arreglo);
    
    $this->vista->imprimeListadoExistencias($arreglo);
  }
  
  function exportarExcel($arreglo) {
    $arreglo['datos'] = $this->datos->listadoExistencias($arreglo);
    
    $datosExcel = new reporteExcel();
    $datosExcel->generarExcel($arreglo);
  }
    
  function findCliente($arreglo) {
    $arreglo['q'] = strtolower($_GET["q"]);
    $unaConsulta = $this->datos->findCliente($arreglo);
    $Existe = count($unaConsulta);

    if($Existe == 0) echo "No hay Resultados|0\n";
    else {
      foreach($unaConsulta as $value) {
        $nombre = trim($value['razon_social']);
        $nit = $value['numero_documento'];
        echo "$nombre|$nit\n";
      }
    }  
  }
}
?>