<?php
require_once("ControlDatos.php");
require_once("ControlPresentacion.php");
require_once("ReporteExcel.php");

class ControlLogica {
  var $datos;
  var $pantalla;

  function ControlLogica() {
    $this->datos =& new Control();
    $this->pantalla =& new ControlPresentacion($this->datos);
  }

  function controlar($arregloDatos) {
    $arregloDatos[tab_seleccionado] = 0;
    //Asigna Plantilla Filtro de Entrada
    $arregloDatos[plantillaFiltro] = "controlFiltro.html";
    $this->pantalla->maestro($arregloDatos); 
  }
  
  function getListaControlar($arregloDatos) {
    $arregloDatos[tab_seleccionado] = 1;
    //Configuración información a mostrar TAB-Mercancia
    $arregloDatos[plantillaMercancia] = "controlListaDisponible.html";      
    $arregloDatos[metodoMercancia] = "getMercanciaBloquear";
    //Configuración información a mostrar TAB-Control
    $arregloDatos[plantillaControl] = "controlDocumentoBloqueado.html";
    $arregloDatos[metodoControl] = "getControlDocumento";
    $this->pantalla->maestro($arregloDatos);
  }
  
  function impresion($arregloDatos) {
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'levanteRemesaRetiro.html';
    $arregloDatos[thisFunction]	= 'getRetiro';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function addItemBloquear($arregloDatos) {
    // Inserta el Control a la Tabla controles_legales
    $this->datos->addItemBloquear($arregloDatos);
    // Codifica los Tipos de Controles
    list($arregloDatos[nombre_entidad],$arregloDatos[nombre_control]) = $this->datos->codTipoControl($arregloDatos);
    //Una vez registrado el Control en la tabla controles_legales se envía el email de Tracking.
    $arregloDatos[plantilla_mail] = "mailTrackingControles.html";
    $arregloDatos[asunto_mail] = "Control para el DO: ".$arregloDatos[do_asignado_full];
    $this->envioMail($arregloDatos);
  }
 
  //Función visualiza controles a un documento
  function getListaControl($arregloDatos) {
    $arregloDatos[mensaje] = '';
    $arregloDatos[plantilla] = 'controlVerBloqueos.html';
    $arregloDatos[thisFunction] = 'getListaControles';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);    
  }
  
  function getControlDocumento($arregloDatos) {
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'controlDocumentoBloqueado.html';
    $arregloDatos[thisFunction]	= 'getControlDocumento';
    
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function getItemBloquear($arregloDatos) {
    $unDatos = new Control();
    $arregloDatos[plantilla] = 'controlFormaBloquear.html';
    $arregloDatos[thisFunction] = 'controlBloquear';
    $this->pantalla->getItemBloquear($arregloDatos);
  }
  
  function existeCliente($arregloDatos) {
    $unaConsulta = new Reempaque();
    $unaConsulta->existeCliente($arregloDatos);

    if($unaConsulta->N == 0) {
      echo $unaConsulta->N;
      die();
    }
    $unaConsulta->existeReempaque($arregloDatos);

    if($unaConsulta->N > 0) {
      echo 10;
      die();
    }
    echo 1;
  }

  function imprimeLevante($arregloDatos) {
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'levanteRemesaRetiro.html';
    $arregloDatos[thisFunction] = 'listaInventario';  
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function findDocumento($arregloDatos) {
    $unaConsulta = new Control();
    $unaConsulta->findDocumento($arregloDatos);
    $arregloDatos[q] = strtolower($_GET["q"]);
    header('Content-type: text/html; charset=iso-8859-1');

    while($unaConsulta->fetch()) {
      $nombre = trim($unaConsulta->doc_tte)." [ORDEN] ".trim($unaConsulta->do_asignado);
      echo "$nombre|$unaConsulta->doc_tte|$unaConsulta->do_asignado\n";
    }
    if($unaConsulta->N == 0) {
      echo "No hay Resultados|0\n";
    }
  }
  
	function envioMail($arregloDatos) {
		$arregloDatos['remite'] = 'blogistic@grupobysoft.com';
		$remite = array('email' => $arregloDatos['remite'],'nombre' => $arregloDatos['remite']);
		$destino = array('email'  => $arregloDatos['email'],'nombre' => $arregloDatos['email']);				

		require_once('EnvioMail.php');
		$mail = new EnvioMail();

		$mail->cuerpo($arregloDatos[plantilla_mail],$arregloDatos[plantilla_mail],$arregloDatos);
		$mail->cargarCabecera($destino, $remite, $arregloDatos[asunto_mail]);
		//Procedimiento de Envío de mail y validación de envío correcto
		$arregloDatos[info] = $mail->enviarEmail() ? -1 : 0;
		$this->pantalla->mostrarMensaje($arregloDatos);
	}  
}		
?>