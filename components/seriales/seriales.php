<?php 
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'seriales/model/seriales.php';
require_once COMPONENTS_PATH . 'seriales/views/vista.php';

class seriales {
	var $vista;
	var $datos;
	
	function seriales() {
		$this->vista = new SerialesVista();
		$this->datos = new SerialesModelo();
	}
	
	function listadoSeriales($arreglo) {
		if(!isset($arreglo['pagina']) || empty($arreglo['pagina'])){
			$arreglo['pagina']=1;
		}
		$arreglo['datosSerial'] = $this->datos->listadoSeriales($arreglo);
		$this->vista->listadoSeriales($arreglo);
	}
	
	function agregarSeriales($arreglo) {
		$lista_seriales = $this->datos->build_list("metodo_seriales", "codigo", "nombre");
    $arreglo['select_metodo'] = $this->datos->armSelect($lista_seriales,'Seleccione m&eacute;todo...');
		$this->vista->agregarSeriales($arreglo);
	}
	
	function consultaSerial($arreglo) {
		$this->vista->consultaSerial($arreglo);
	}
	
	function editar($arreglo){
		if(isset($arreglo['edClave'])){
			$this->datos->editarClave($arreglo);
		}
		if(isset($arreglo['id']) && !empty($arreglo['id'])){
        	$id = $arreglo['id'];
        	$arreglo['alerta_accion'] = 'Serial Editado Con &Eacute;xito';
	    }
		else{
	        $id = null;
	        $arreglo['alerta_accion'] = 'Serial Creado Con &Eacute;xito';
	    }
		recuperar_Post($this->datos);
		$this->datos->save($id);
		$this->listadoSeriales($arreglo);
	}
	
	function buscarTodos($arreglo) {
		$this->vista->buscarTodos($arreglo);			
	}
	
	function buscarDB($arreglo) {
		$numorden = $this->datos->buscarTodos($arreglo);
		if(count($numorden) != 0) {
			$arreglo['norden'] = $numorden[0];
    	$arreglo['norden'] = $arreglo['norden']['numorden'];
			echo $arreglo['norden'];
		} else echo "No Existe";
	}
	
	function eliminarSeriales($arreglo) {
		$this->datos->eliminarSeriales($arreglo);
		$this->listadoSeriales($arreglo);
	}
	
	function eliminarSerial($arreglo) {
		$this->datos->eliminarSerial($arreglo);
		$this->listadoSeriales($arreglo);
	}
	
	function verSerial($arreglo) {
		$this->vista->verSerial($arreglo);
	}
	
	function cargarArchivo($arreglo) {
		$ruta = "integrado/_files/";
		foreach ($_FILES as $key) {
    	if($key['error'] == UPLOAD_ERR_OK ) {//Verificamos si se subio correctamente
        $hora = getdate(time());
				$nombre = date("dmY").$hora["hours"].$hora["minutes"].".txt"; //Definimos el nombre del archivo en el servidor
      	$temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
      	move_uploaded_file($temporal, $ruta . $nombre); //Movemos el archivo temporal a la ruta especificada
				$estado = true;
			} else {
				echo $key['error']; //Si no se cargo mostramos el error
        $nombre = "Error";
				$estado = false;
			}
		}

    echo $ruta.$nombre;
	}
  
  function findSerial($arreglo) {
    $arreglo['q'] = strtolower($_GET["q"]);
    $unaConsulta = $this->datos->findSerial($arreglo);
    $Existe = count($unaConsulta); 

    foreach($unaConsulta as $value) {
			$serial = trim($value['serial']);
      $orden = $value['numorden'];
      
			echo "$serial|$orden\n";
    }
    if($Existe == 0) echo "No hay Resultados|0\n";
  }
	
	function cargarSeriales($arreglo) {
		//Carga los seriales contenido en el archivo a la base de datos
		$this->datos->cargarSeriales($arreglo);
		$this->listadoSeriales($arreglo);
	}
  
 	function buscarSerial($arreglo) {
		$ordenserial = $this->datos->buscarSerial($arreglo);
		if(count($ordenserial) != 0) {
      $serial = trim($ordenserial[0]['serial']);
    	$orden = $ordenserial[0]['numorden'];
      $doc_tte = $ordenserial[0]['doc_tte'];
      $ubicacion = $ordenserial[0]['nombre'];
			echo "$serial|$orden|$doc_tte|$ubicacion\n";
		} else echo "No Existe";
	}
  
  function imprimirSerial($arreglo) {
    $this->vista->imprimirSerial($arreglo);
  }
}
?>