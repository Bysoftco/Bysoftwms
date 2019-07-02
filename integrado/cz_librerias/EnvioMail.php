<?php
require_once('Mail.php');
require_once('Mail/mime.php');
//ini_set('include_path', URL_BASE .'_scripts/phpmailer/');
//require("class.phpmailer.php");

//require("class.phpmailer.php");
class EnvioMail extends Mail_mime {    
	var $destino;
	var $remite;
	var $cabecera;
	var $cuerpo;
	var $attachment;
	var $nombreAdjunto;  
	var $mensajeTexto;
	var $mensajeHtml;
	var $tplTexto;
	var $tplHtml;
	var $logger;

	/**
		* EnvioMail::EnvioMail()
		* 
		* @return 
		**/
	function EnvioMail() {}
     
	/**
		* EnvioMail::formatearDireccion()
		* Privada
		* El destinatario es un arreglo con correo y nombre por este motivo es necesario
		* aplicar esta función para separar el correo del nombre
		* Retorna solamente la dirección de correo.
		* @return 
		**/ 
	function _formatearDireccion($arreglo) {
		$posInicial = strpos($this->destino, "<");
    $posFinal = strpos($this->destino, ">");
		$largo = $posFinal-$posInicial;
            
		$direccion = substr($this->destino,$posInicial+ 1, $largo-1);

    return $direccion;
	}

	/**
		* EnvioMail::cargarCabecera()
		* Es pública 
		* se encarga de armar el encabezado del correo y preparar el contenido
		* del cuerpo
		* @return 
		**/
	function cargarCabecera($arregloDestinos, $arregloRemite, $asunto) {
		$this->destino = '"' . $arregloDestinos['nombre'] . '" <' . $arregloDestinos['email'] . '>';
		$this->remite = '"' . $arregloRemite['nombre'] . '" <' . $arregloRemite['email'] . '>';
		$this->cabecera = array('From' => $this->remite,
														'to' => $this->destino,
														'Subject' => $asunto,
														'Reply-To' => $this->remite);

		$this->cabecera = $this->headers($this->cabecera);
		
		return true;
	}
    
	/**
		* EnvioMail::cuerpo()
		* Es pública 
		* Recibe como parametros un nombre y el arreglo de campos que se deben
		* remplazar en una plantilla que debe existe en la dirección TPLDIR
		* @return 
		**/
	function cuerpo($tplTexto=NULL,$tplHtml=NULL,$arregloCampos) {
		if($tplTexto <> NULL) {
			$this->tplTexto = new HTML_Template_IT(PLANTILLAS);	
			$this->tplTexto->loadTemplateFile($tplTexto, false, false); 
			if(is_array($arregloCampos)) {
				foreach($arregloCampos as $key => $value) {
					if(is_array($value)) {
						$value = implode(", ", $value);
 					}
					$this->tplTexto->setVariable($key, $value);
				}
			}
		}

		if($tplHtml <> NULL) {
			if(empty($arregloCampos[ruta_plantillas])) {
				$ruta = './_plantillas';
			} else {
				$ruta = $arregloCampos[ruta_plantillas];
        echo "<script>alert($ruta);</script>";
			}

			$this->tplHtml = new HTML_Template_IT($ruta);	//se crea el template
			$this->tplHtml->loadTemplateFile($tplHtml, false, false);
			if(is_array($arregloCampos)) {
				foreach($arregloCampos as $key => $value) {
					if(is_array($value)) {
						$value = implode(", ", $value);
 					}
					$this->tplHtml->setVariable($key , $value);
				}
			} 
		}

		$this->cargarInformacion();
	}  

	/**
		* EnvioMail::cargarInformacion()
		* Es privada 
		* Se encarga de revisar como se está enviando el contenido del correo
		* puede ser utilizando las dos plantillas,solo una o ninguna  eso
		* es lo que valida la función para hacer la asignación correcta.
		* @return 
		**/

	function cargarInformacion() {
    $ruta = "integrado/_mail/";
		//si se creo la variable de texto se asume que se envio un texto directamente
		//y no se utiliza plantilla
		if(!empty($mensajeTexto)) {
			$this->setTXTBody($mensajeTexto);
		} else {
			if(isset($this->tplTexto)) {
				//se verifica que se utilizo la plantilla y se asigna a el cuerpo
				//en modo texto
				$this->setTXTBody($this->tplTexto->get());
			}
		}

		if(!empty($mensajeHtml)) {
			$this->setHTMLBody($mensajeHtml);
		} else {
			if(isset($this->tplHtml)) {
				//se verifica que se utilizo la plantilla y se asigna a el cuerpo
				//en modo html
				$this->setHTMLBody($this->tplHtml->get());
			}
		}        

		if(!empty($this->attachment)) {
			//se verifica que se agregaron múltiples archivos adjuntos
      foreach($this->nombreAdjunto as $nombreArchivo) {
        //$this->addAttachment($ruta . $this->nombreAdjunto);  
        $this->addAttachment($ruta . $nombreArchivo);
      }
		}
		// se asigna el cuerpo al correo
		$this->Mail_mime();
		$this->cuerpo = $this->get();
	}

	/**
		* EnvioMail::validarDominio()
		* Es privada de la clase
		* Retorna falso si no existe
		* Esta función se encarga de validar si el dominio de la dirección de 
		* correo existe recibe como parametro una direccion de correo
		* Comprueba registros DNS correspondientes a nombres de máquinas en Internet o direcciones IP 
		* @return 
		**/

	function _validarDominio($email) {
		$exp = "^[a-z\'0-9]+([._-][a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$"; 
		if(eregi($exp,$email)) { 
			if(checkdnsrr(array_pop(explode("@",$email)),"MX")) { 
				return true; 
			} else { 
				$this->logger = 'La dirección de Correo '.$email.' no existe o el servidor no esta disponible';
				return false; 
			} 
		} else { 
			return false; 
		} 
	} 

	/**
		* EnvioMail::adjuntarArchivo()
		* publica recibe como argumento el nombre del imput de la plantilla que
		* captura los parametros del archivo, si el archivo tiene una extencion que esta dentro
		* del listado de no validas retorna false.
		* @return  boolean
		**/
	
	function adjuntarArchivo_($file) {
		$extensionesnoValidas = "@exe,data"; 
		if(!empty($_FILES['archivo']['tmp_name'])) {
			$userfile = $_FILES[$file]['tmp_name'];
			$userfile_name = $_FILES[$file]['name'];

			$pathParts = pathinfo($userfile_name);
			$extension = $pathParts["extension"];
			$this->attachment = $userfile;
			$this->nombreAdjunto = $userfile_name;   

			if(strpos($extensionesnoValidas, $extension)) {
				$this->logger = "El Archivo que intenta subir tiene una extensión no valida [$extension]";
				return false;
			}
		} 
		return true;
	}

	function adjuntarArchivo($file) {
    $this->attachment = explode(" ",$file);
    $this->nombreAdjunto = explode(" ",$file);

		return true;
	}

	function lipiarPlantillas($contenidoTexto = '' , $contenidoHTML = '') {
		$this->tplCuerpo->free();
		$this->tplTexto->free();;
	}

	function enviarEmail() {
		$params["host"] = "ssl://mail.grupobysoft.com";
		$params["port"] = "465";
		$params["auth"] = true;
		$params["username"] = "blogistic@grupobysoft.com";
		$params["password"] = "bysoft01";

		$unMTA = & Mail::factory('smtp', $params);

		$returnValue = $unMTA->send($this->destino,$this->cabecera,$this->cuerpo);
		if(PEAR::isError($returnValue)) {
			$this->logger = $returnValue->getMessage();
			PEAR::raiseError('No Se pudo Enviar el Email.' . "\n" . $returnValue->getMessage(), 12101);
			return $returnValue;
		} else {
			return TRUE;
		}
	}
}
?>