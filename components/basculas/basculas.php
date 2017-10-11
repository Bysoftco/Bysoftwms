<?php 
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'basculas/model/basculas.php';
require_once COMPONENTS_PATH . 'basculas/views/vista.php';

class basculas {
	var $vista;
	var $datos;
	
	function basculas() {
		$this->vista = new BasculasVista();
		$this->datos = new BasculasModelo();
	}
	
	function listadoBasculas($arreglo) {
		if(!isset($arreglo['pagina']) || empty($arreglo['pagina'])){
			$arreglo['pagina']=1;
		}
		$arreglo['datosBascula'] = $this->datos->listadoBasculas($arreglo);
		$this->vista->listadoBasculas($arreglo);
	}
	
	function agregarBasculas($arreglo) {
		$lista_basculas = $this->datos->build_list("metodo_seriales", "codigo", "nombre");
    $arreglo['select_metodo'] = $this->datos->armSelect($lista_basculas,'Seleccione m&eacute;todo...');
		$this->vista->agregarBasculas($arreglo);
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
		
		/*if($estado) {
			$mensaje = "El archivo se pudo subir al servidor con el nombre de <b>$nombre</b>";
		} else {
			$mensaje = "El archivo no se pudo subir al servidor, intentalo mas tarde";
		}*/
		//echo $mensaje;
    echo $ruta.$nombre;
	}
	
	function cargarSeriales($arreglo) {
		//Carga los seriales contenido en el archivo a la base de datos
		$this->datos->cargarSeriales($arreglo);
		$this->listadoSeriales($arreglo);
	}
}
?>