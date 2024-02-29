<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once(COMPONENTS_PATH.'cargueReferencias/model/cargueReferencias.php');
require_once(COMPONENTS_PATH.'cargueReferencias/views/vista.php');

class cargueReferencias {
  var $vista;
  var $datos;

  function cargueReferencias() {
    $this->vista = new cargueReferenciasVista();
    $this->datos = new cargueReferenciasModelo();
  }

	function filtrocargueReferencias($arreglo) {
		$this->vista->filtrocargueReferencias($arreglo);
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
        //Cargar referencias en la tabla referencias
        $msj = $this->datos->cargarReferencias($arreglo);
      }
    } else {
      $msj = $arreglo['datos'];
    }
    echo $msj;
  }
	
  function verificaDatos($arreglo) {
    $novedad = false;
    foreach($arreglo['datos'] as $value) {
      if($value['D']!="") $novedad = true;
    }

    return $novedad;
  }
}
?>