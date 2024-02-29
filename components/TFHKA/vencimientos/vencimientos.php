<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once(COMPONENTS_PATH.'vencimientos/model/vencimientos.php');
require_once(COMPONENTS_PATH.'vencimientos/views/vista.php');
require_once(COMPONENTS_PATH.'vencimientos/views/tmpl/reporteExcel.php');

class vencimientos {
  var $vista;
  var $datos;

  function vencimientos() {
    $this->vista = new VencimientosVista();
    $this->datos = new VencimientosModelo();
  }
  
  function filtroVencimientos($arreglo) {
		$this->vista->filtroVencimientos($arreglo);
	}

  function listadoVencimientos($arreglo) {
    $arreglo['datos'] = $this->datos->listadoVencimientos($arreglo);
    
    $this->vista->listadoVencimientos($arreglo);
  }
  
  function imprimeListadoVencimientos($arreglo) {
    $arreglo['datos'] = $this->datos->listadoVencimientos($arreglo);
    
    $this->vista->imprimeListadoVencimientos($arreglo);
  }
  
  function exportarExcel($arreglo) {
    $arreglo['datos'] = $this->datos->listadoVencimientos($arreglo);
    
    $datosExcel = new reporteExcel();
    $datosExcel->generarExcel($arreglo);
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
}
?>