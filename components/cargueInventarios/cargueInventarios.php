<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once(COMPONENTS_PATH.'cargueInventarios/model/cargueInventarios.php');
require_once(COMPONENTS_PATH.'cargueInventarios/views/vista.php');

class cargueInventarios {
  var $vista;
  var $datos;

  function cargueInventarios() {
    $this->vista = new cargueInventariosVista();
    $this->datos = new cargueInventariosModelo();
  }

	function filtrocargueInventarios($arreglo) {
		$this->vista->filtrocargueInventarios($arreglo);
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
        //Cargar inventarios en la tabla inventario_entradas
        $msj = $this->datos->cargarInventarios($arreglo);
      }
    } else {
      $msj = $arreglo['datos'];
    }
    echo $msj;
  }
	
  function verificaDatos($arreglo) {
    $novedad = false;
    foreach($arreglo['datos'] as $value) {
      if($value['N']!="" && $value['N']!='observacion') $novedad = true;
    }

    return $novedad;
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