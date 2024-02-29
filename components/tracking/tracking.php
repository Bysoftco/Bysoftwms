<?php 
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH.'tracking/model/tracking.php';
require_once COMPONENTS_PATH.'tracking/views/vista.php';
require_once COMPONENTS_PATH.'tracking/views/tmpl/EnvioMail.php';

class tracking {
  var $vista;
  var $datos;

  function tracking() {
    $this->vista = new TrackingVista();
    $this->datos = new TrackingModelo();
  }
	
  function filtroTracking($arreglo) {
    $this->vista->filtroTracking($arreglo);
  }

  function listadoTracking($arreglo) {
    if(!isset($arreglo['pagina']) || empty($arreglo['pagina'])) {
      $arreglo['pagina'] = 1;
    }
    $arreglo['datos'] = $this->datos->listadoTracking($arreglo);
    $arreglo['perfil'] = $_SESSION['datos_logueo']['perfil_id'];
    $this->vista->listadoTracking($arreglo);
  }
  
  function agregarTracking($arreglo) {
    $datosTracking = $this->datos->datosTracking($arreglo);
    $arreglo['datosTracking'] = $datosTracking[0];
    
    $this->vista->agregarTracking($arreglo);
  }
  
  function verTracking($arreglo) {
    $datosTracking = $this->datos->datosTracking($arreglo);
    $arreglo['datosTracking'] = $datosTracking[0];
    
    $this->vista->verTracking($arreglo);
  }
	
  function editarTracking($arreglo) {
    $datosTracking = $this->datos->datosTracking($arreglo);
    $arreglo['datosTracking'] = $datosTracking[0];
    $arreglo['id'] = $arreglo['datosTracking']['id'];

    $this->vista->agregarTracking($arreglo);
  }
  
  function nuevoTracking($arreglo) {
		recuperar_Post($this->datos);
		$this->datos->save($arreglo['id'],'id');
		// Envía Mensaje de Correo para Tracking
    $arreglo['mensajeTexto'] = $arreglo['mensaje']; 
		$arreglo['asunto_mail'] = $arreglo['asunto'];
    // Verifica internamente si hay archivos adjuntos
		$this->envioMail($arreglo);

		$this->listadoTracking($arreglo);
  }
  
  function findCliente($arreglo) {
    $arreglo['q'] = strtolower($_GET["q"]);
    $unaConsulta = $this->datos->findCliente($arreglo);
    $Existe = count($unaConsulta); 

    foreach($unaConsulta as $value) {
			$nombre = trim($value['razon_social']);
      $nit = $value['numero_documento'];
			$email = $value['correo_electronico'];
			echo "$nombre|$nit|$email\n";
    }
    if($Existe == 0) echo "No hay Resultados|0\n";
  }
  
  function eliminarTracking($arreglo) {
    $this->datos->eliminarTracking($arreglo);
    $this->listadoTracking($arreglo);
  }
  
  function cargarArchivo($arreglo) {
		$ruta = "integrado/_mail/"; $arreglo[adjuntos] = '';
		foreach($_FILES as $key) {
    	if($key['error'] == UPLOAD_ERR_OK ) {//Verificamos si se subio correctamente
				$nombre = $key['name']; //Definimos el nombre del archivo en el servidor
      	$temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
      	move_uploaded_file($temporal, $ruta . $nombre); //Movemos el archivo temporal a la ruta especificada
        $arreglo['adjuntos'] .= $nombre.' ';
				$estado = true;
			} else {
				echo $key['error']; //Si no se cargó mostramos el error
				$estado = false;
			}
		}
		if($estado) {
			$mensaje = $arreglo['adjuntos'];
		} else {
			$mensaje = "Este correo no contiene documentos adjuntos.";
		}
		echo $mensaje;
	}
  
	function envioMail($arreglo) {
		$arreglo['remite'] = 'blogistic@grupobysoft.com';
		$remite = array('email' => $arreglo['remite'],'nombre' => $arreglo['remite']);
		$destino = array('email'  => $arreglo['destino'],'nombre' => $arreglo['destino']);				

		$mail = new EnvioMail();

    // Verifica si tiene documentos adjuntos
    if($arreglo['wadjunto']=='1') $mail->adjuntarArchivo($arreglo['adjuntos']);
		$mail->cuerpo($arreglo['mensajeTexto']);
		$mail->cargarCabecera($destino, $remite, $arreglo['asunto_mail']);
		//Procedimiento de Envío de mail y validación de envío correcto
		$arreglo['info'] = $mail->enviarEmail() ? TRUE : FALSE;
		$this->vista->mostrarMensaje($arreglo);
	}
}
?>