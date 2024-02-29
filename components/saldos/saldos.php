<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once(COMPONENTS_PATH.'saldos/model/saldos.php');
require_once(COMPONENTS_PATH.'saldos/views/vista.php');
require_once(COMPONENTS_PATH.'saldos/views/tmpl/reporteExcel.php');

class saldos {
  var $vista;
  var $datos;

  function saldos() {
    $this->vista = new SaldosVista();
    $this->datos = new SaldosModelo();
  }

	function filtroSaldos($arreglo) {
		$this->vista->filtroSaldos($arreglo);
	}
	
  function listadoSaldos($arreglo) {
    $arreglo['datos'] = $this->datos->listadoSaldos($arreglo);
    
    $this->vista->listadoSaldos($arreglo);
  }
  
  function imprimeListadoSaldos($arreglo) {
    $arreglo['datos'] = $this->datos->listadoSaldos($arreglo);
    
    $this->vista->imprimeListadoSaldos($arreglo);
  }
  
  function exportarExcel($arreglo) {
    $arreglo['datos'] = $this->datos->listadoSaldos($arreglo);
    
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