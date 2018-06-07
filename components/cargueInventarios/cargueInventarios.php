<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once(COMPONENTS_PATH.'cargueInventarios/model/cargueInventarios.php');
require_once(COMPONENTS_PATH.'cargueInventarios/views/vista.php');
require_once(COMPONENTS_PATH.'cargueInventarios/views/tmpl/reporteExcel.php');

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

  function cargarPlantilla($arreglo) {
		$ruta = "integrado/_files/";
		foreach($_FILES as $key) {
    	if($key['error'] == UPLOAD_ERR_OK ) {//Verificamos si se subio correctamente
				$nombre = $key['name']; //Definimos el nombre del archivo en el servidor
      	$temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
      	//move_uploaded_file($temporal, $ruta . $nombre); //Movemos el archivo temporal a la ruta especificada
        copy($temporal, $ruta . $nombre);
				$estado = true;
			} else {
				echo $key['error']; //Si no se cargo mostramos el error
				$estado = false;
			}
		}
		if($estado) {
			$mensaje = $nombre;
		} else {
			$mensaje = "Error al Cargar la Plantilla.";
		}
		echo $mensaje;
  }
  
	function procesarPlantilla($arreglo) {
    //Recibe información validada
    $arreglo['datos'] = $this->datos->validaDatos($arreglo);
    //Verifica si hay novedades a mostrar
    $haynovedad = $this->verificaDatos($arreglo);
    if($haynovedad) {
      //Elimina el archivo Excel con novedades
      unlink($arreglo['nombrefile']);
      $this->vista->validaDatos($arreglo);
    } else {
  		//Carga inventarios en la tabla inventario_entradas
		  $this->datos->procesarPlantilla($arreglo);
    }
	}
	
  function verificaDatos($arreglo) {
    $novedad = false;
    foreach($arreglo['datos'] as $value) {
      if($value['O']!="" && $value['O']!='observacion') $novedad = true;
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