<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once(COMPONENTS_PATH.'salidas/model/salidas.php');
require_once(COMPONENTS_PATH.'salidas/views/vista.php');
require_once(COMPONENTS_PATH.'salidas/views/tmpl/reporteExcel.php');

class salidas {
  var $vista;
  var $datos;

  function salidas() {
    $this->vista = new SalidasVista();
    $this->datos = new SalidasModelo();
  }

	function filtroSalidas($arreglo) {
		$this->vista->filtroSalidas($arreglo);
	}
	
  function listadoSalidas($arreglo) {
    $arreglo['datos'] = $this->datos->listadoSalidas($arreglo);
    
    $this->vista->listadoSalidas($arreglo);
  }
  
  function imprimeListadoSalidas($arreglo) {
    $arreglo['datos'] = $this->datos->listadoSalidas($arreglo);
    
    $this->vista->imprimeListadoSalidas($arreglo);
  }
  
  function exportarExcel($arreglo) {
    $arreglo['datos'] = $this->datos->listadoSalidas($arreglo);
    
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