<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once(COMPONENTS_PATH.'felectronica/model/felectronica.php');
require_once(COMPONENTS_PATH.'felectronica/views/vista.php');
require_once(COMPONENTS_PATH.'felectronica/views/tmpl/reporteExcel.php');

class felectronica {
  var $vista;
  var $datos;

  function felectronica() {
    $this->vista = new felectronicaVista();
    $this->datos = new felectronicaModelo();
  }

  function filtroFacturas($arreglo) {
    $this->vista->filtroFacturas($arreglo);
  }

  function listadoFacturas($arreglo) {
    $arreglo['datos'] = $this->datos->listadoFacturas($arreglo);
    
    $this->vista->listadoFacturas($arreglo);
  }

	function verFactura($arreglo) {
    // Encabezado de la Factura
		$arreglo['datosFactura'] = $this->datos->infoFactura($arreglo);
    // Detalle de la Factura
    $arreglo['detalleFactura'] = $this->datos->detalleFactura($arreglo['datosFactura']['codigo']);
    
		$this->vista->verFactura($arreglo);
	}
  
  function exportarExcel($arreglo) {
    $arreglo['datos'] = $this->datos->retornarInfoOrdenes($arreglo);
    
    $datosExcel = new reporteExcel();
    $datosExcel->generarExcel($arreglo);
  }
  
  function findCliente($arreglo) {
    $arreglo[q] = strtolower($_GET["q"]);
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