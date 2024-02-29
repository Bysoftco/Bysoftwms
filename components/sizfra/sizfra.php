<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once(COMPONENTS_PATH.'sizfra/model/sizfra.php');
require_once(COMPONENTS_PATH.'sizfra/views/vista.php');
require_once(COMPONENTS_PATH.'sizfra/views/tmpl/Archivo.php');
require_once(COMPONENTS_PATH.'sizfra/views/tmpl/EnvioMail.php');

class Sizfra {
  var $vista;
  var $datos;

  function Sizfra() {
    $this->vista = new SizfraVista();
    $this->datos = new SizfraModelo();
  }

	function filtroSizfra($arreglo) {	   
		$this->vista->filtroSizfra($arreglo);
	}
	
  function listadoSizfra($arreglo) {
    $arreglo['datos'] = $this->datos->listadoSizfra($arreglo);
    
    //Actualiza Interfaz en Inventario Movimientos
    $this->actualizaMovimientos($arreglo);

    //Instancia la clase Archivo
    $datosInterfaz = new Archivo();
    //Creamos el archivo
    $datosInterfaz->crear("integrado/_files/".$arreglo['nombreinterfaz'].".txt");
  
    $this->vista->listadoSizfra($arreglo,$datosInterfaz);
  }
  
  function consultaSizfra($arreglo) {
    $arreglo['datos'] = $this->datos->consultaSizfra($arreglo);
  
    $this->vista->consultaSizfra($arreglo);
  }
  
	function capturaSizfra($arreglo) {
		$this->vista->capturaSizfra($arreglo);
	}
  
  function deshacerSizfra($arreglo) {
    $this->datos->deshacerSizfra($arreglo);
    $ruta_completa = "integrado/_files/".$arreglo['nombreinterfazc'].".txt";

    // Eliminamos físicamente el archivo
    unlink($ruta_completa);
        
    echo 'El Nombre de la Interfaz '.$arreglo['nombreinterfazc'].' fue reversada con éxito.';
  }
  
  function actualizaMovimientos($arreglo) {
    $interfaz = $arreglo['nombreinterfaz'];
    
    foreach($arreglo['datos'] as $value) {
      $this->datos->actualizaMovimientos($value['cod_mov'],$interfaz);
    }
  }
    
  function findInterfaz($arreglo) {
    $unaConsulta = $this->datos->findInterfaz($arreglo);
    $Existe = count($unaConsulta); 
    echo $Existe;
  }
  
  function findCliente($arreglo) {
    $arreglo['q'] = strtolower($_GET["q"]);
    $unaConsulta = $this->datos->findCliente($arreglo);
    $Existe = count($unaConsulta); 

    foreach($unaConsulta as $value) {
			$nombre = trim($value['razon_social']);
      $nit = $value['numero_documento'];
			echo "$nombre|$nit\n";
    }
    if($Existe == 0) echo "No hay Resultados|0\n";
  }
  
  function enviarAdjunto($arreglo) {
		//Envía Mensaje de Correo Interfaz Sizfra
    $arreglo['mensajeTexto'] = $arreglo['mensaje']; 
		$arreglo['asunto_mail'] = $arreglo['asunto'];
    //Verifica internamente si hay archivos adjuntos
		$this->envioMail($arreglo);

		$this->consultaSizfra($arreglo);
  }
  
  function envioMail($arreglo) {
		$arreglo['remite'] = 'blogistic@grupobysoft.com';
		$remite = array('email' => $arreglo['remite'],'nombre' => $arreglo['remite']);
		$destino = array('email'  => $arreglo['destino'],'nombre' => $arreglo['destino']);				

		$mail = new EnvioMail();

    $mail->adjuntarArchivo($arreglo['adjunto']);
		$mail->cuerpo($arreglo['mensajeTexto']);
		$mail->cargarCabecera($destino, $remite, $arreglo['asunto_mail']);
		//Procedimiento de Envío de mail y validación de envío correcto
		$arreglo['info'] = $mail->enviarEmail() ? TRUE : FALSE;
		$this->vista->mostrarMensaje($arreglo);
	}
}
?>