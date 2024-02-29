<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once(COMPONENTS_PATH.'cargueRetiros/model/cargueRetiros.php');
require_once(COMPONENTS_PATH.'cargueRetiros/views/vista.php');

class cargueRetiros {
  var $vista;
  var $datos;

  function cargueRetiros() {
    $this->vista = new cargueRetirosVista();
    $this->datos = new cargueRetirosModelo();
  }

	function filtrocargueRetiros($arreglo) {
		$this->vista->filtrocargueRetiros($arreglo);
	}

	function procesarArchivo($arreglo) {
    $arreglo['rutaNombreArchivoEntrada'] = $_FILES['archivo_importar']['tmp_name'];
    //Recibe información validada
    $arreglo['datos'] = $this->datos->validaDatos($arreglo);
    //Verifica estructura de la plantilla
    if($arreglo['datos']!='Error en la Estructura de la Plantilla') {
      //Verifica si hay novedades a mostrar
      $haynovedad = $this->verificaDatos($arreglo);
      if($haynovedad) {
        $msj = $this->vista->validaDatos($arreglo);
      } else {
        //Cargar retiros en la tabla inventario_movimientos
        $msj = $this->datos->cargarRetiros($arreglo);
      }
    } else {
      $msj = $arreglo['datos'];
    }
    echo $msj;
  }
	
  function verificaDatos($arreglo) {
    $novedad = false;
    foreach($arreglo['datos'] as $value) {
      if($value['F']!="" && $value['F']!='observacion') $novedad = true;
    }

    return $novedad;
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