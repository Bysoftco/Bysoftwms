<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once(COMPONENTS_PATH.'reportelevantes/model/reportelevantes.php');
require_once(COMPONENTS_PATH.'reportelevantes/views/vista.php');
require_once(COMPONENTS_PATH.'reportelevantes/views/tmpl/reporteExcel.php');

class reportelevantes {
  var $vista;
  var $datos;

  function reportelevantes() {
    $this->vista = new ReportelevantesVista();
    $this->datos = new ReportelevantesModelo();
  }

	function filtroReportelevantes($arreglo) {
		$this->vista->filtroReportelevantes($arreglo);
	}
	
  function listadoReportelevantes($arreglo) {
    $arreglo['datos'] = $this->datos->listadoReportelevantes($arreglo);
    
    $this->vista->listadoReportelevantes($arreglo);
  }
  
  function exportarExcel($arreglo) {
    $arreglo['datos'] = $this->datos->listadoReportelevantes($arreglo);
    
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

  function findDocumento($arreglo) {
    $arreglo['q'] = strtolower($_GET["q"]);
    $unaConsulta = $this->datos->findDocumento($arreglo);
    $Existe = count($unaConsulta);

    if($Existe == 0) echo "No hay Resultados|-1\n";
    else {
      foreach($unaConsulta as $value) {
        $nombre = trim($value['doc_tte'])." [ORDEN] ".trim($value['do_asignado']);
        $doctte = trim($value['doc_tte']);
        $doasignado = $value['do_asignado'];
        echo "$nombre|$doctte|$doasignado\n";
      }
    }
  }
  
  function findReferencia($arreglo) {
    $arreglo['q'] = strtolower($_GET["q"]);
    $unaConsulta = $this->datos->findReferencia($arreglo);
    $Existe = count($unaConsulta); 

    if($Existe == 0) echo "No hay Resultados|-1\n";
    else {
      foreach($unaConsulta as $value) {
        $codigo = $value['codigo'];
        $nombre = trim($value['nombre']);
        echo "$nombre|$codigo\n";
      }      
    }
  }
}
?>