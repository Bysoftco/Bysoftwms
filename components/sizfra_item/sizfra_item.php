<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once(COMPONENTS_PATH.'sizfra_item/model/sizfra_item.php');
require_once(COMPONENTS_PATH.'sizfra_item/views/vista.php');
require_once(COMPONENTS_PATH.'sizfra_item/views/tmpl/Archivo.php');
require_once(COMPONENTS_PATH.'sizfra_item/views/tmpl/EnvioMail.php');

class sizfra_item {
  var $vista;
  var $datos;

  function sizfra_item() {
    $this->vista = new SizfraVista();
    $this->datos = new SizfraModelo();
  }

	function filtroSizfra($arreglo) {
		$this->vista->filtroSizfra($arreglo);
	}
	
    function listadoSizfra($arreglo) {
        $arreglo['datos'] = $this->datos->listadoSizfra($arreglo);
            
        //Instacia la clase Archivo
        $datosInterfaz = new Archivo();
        //Creamos el archivo
        $datosInterfaz->crear("integrado/_files/".$arreglo['nombreinterfaz'].".txt");
        
        $this->vista->listadoSizfra($arreglo,$datosInterfaz);
    }
   
  function enviarAdjunto($arreglo) {
		//Envía Mensaje de Correo Interfaz Sizfra
    $arreglo[mensajeTexto] = $arreglo[mensaje]; 
		$arreglo[asunto_mail] = $arreglo[asunto];
    //Verifica internamente si hay archivos adjuntos
		$this->envioMail($arreglo);

		$this->consultaSizfra($arreglo);
  }
  
  function envioMail($arreglo) {
		$arreglo['remite'] = 'blogistic@grupobysoft.com';
		$remite = array('email' => $arreglo['remite'],'nombre' => $arreglo['remite']);
		$destino = array('email'  => $arreglo['destino'],'nombre' => $arreglo['destino']);				

		$mail = new EnvioMail();

    $mail->adjuntarArchivo($arreglo[adjunto]);
		$mail->cuerpo($arreglo[mensajeTexto]);
		$mail->cargarCabecera($destino, $remite, $arreglo[asunto_mail]);
		//Procedimiento de Envío de mail y validación de envío correcto
		$arreglo[info] = $mail->enviarEmail() ? TRUE : FALSE;
		$this->vista->mostrarMensaje($arreglo);
	}
}
?>