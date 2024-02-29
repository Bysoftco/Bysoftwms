<?php
require_once("HTML/Template/IT.php");
require_once("ControlDatos.php");
require_once("ControlLogica.php");

class ControlPresentacion {
  var $datos;
  var $plantilla;

  function ControlPresentacion(&$datos) {
    $this->datos = $datos;
    $this->plantilla = new HTML_Template_IT();
  } 

  function mantenerDatos($arregloCampos,&$plantilla) {
    $plantilla = $plantilla;
    
    if(is_array($arregloCampos)) {
      foreach($arregloCampos as $key => $value) {
        $plantilla->setVariable($key, $value);
      }
    }
  }

  //Función que coloca los datos que vienen de la BD
  function setDatos($arregloDatos,&$datos,&$plantilla) {
    foreach($datos as $key => $value) {
      $plantilla->setVariable($key , $value);
    }
  }

  function cargaPlantilla($arregloDatos) {
    $unAplicaciones = new Control();
    $formularioPlantilla = new HTML_Template_IT();

    $formularioPlantilla->loadTemplateFile(PLANTILLAS.$arregloDatos['plantilla'],true,true);
    $formularioPlantilla->setVariable('comodin',' ');
    $this->mantenerDatos($arregloDatos,$formularioPlantilla);
    $metodo = $arregloDatos['thisFunction'];
    $this->$metodo($arregloDatos,$unAplicaciones,$formularioPlantilla);
    if($arregloDatos['mostrar']) {
      $formularioPlantilla->show();
    } else {
      return $formularioPlantilla->get();
    }
  }

  //Arma cada Formulario o función en pantalla
  function setFuncion($arregloDatos,$unDatos) {
    $unDatos = new Control();

    $metodo = $arregloDatos['thisFunction']; 
    $r = $unDatos->$metodo($arregloDatos);
    $unaPlantilla = new HTML_Template_IT();

    $unaPlantilla->loadTemplateFile(PLANTILLAS.$arregloDatos['plantilla'],true,true);
    $unaPlantilla->setVariable('comodin',' ');
    if(!empty($arregloDatos['mensaje'])) {
      $unaPlantilla->setVariable('mensaje',$arregloDatos['mensaje']);
      $unaPlantilla->setVariable('estilo',$arregloDatos['estilo']);
    }

    $this->mantenerDatos($arregloDatos,$unaPlantilla);
    $arregloDatos['n'] = 0;
    while($obj=$unDatos->db->fetch()) {
      $odd = ($arregloDatos['n'] % 2) ? 'odd' : '';
      $arregloDatos['n'] = $arregloDatos['n'] + 1;
      $unaPlantilla->setCurrentBlock('ROW');
      $this->setDatos($arregloDatos,$obj,$unaPlantilla);
      $metodo = $arregloDatos['thisFunction'];
      $this->$metodo($arregloDatos,$obj,$unaPlantilla);
      $unaPlantilla->setVariable('n', $arregloDatos['n']);
      $unaPlantilla->setVariable('odd', $odd);
      $unaPlantilla->parseCurrentBlock();
    }
    if($arregloDatos['n'] == 0 and empty($unDatos->mensaje)) {
      $unaPlantilla->setVariable('mensaje','&nbsp;No hay registros para listar'.$arregloDatos['mensaje']);
      $unaPlantilla->setVariable('estilo','ui-state-error');
      $unaPlantilla->setVariable('mostrarCuerpo','none');
    }
    $unaPlantilla->setVariable('num_registros',$arregloDatos['n']);
    if($arregloDatos['mostrar']) {
      $unaPlantilla->show();
    } else {
      $unaPlantilla->setVariable('cuenta',$this->cuenta);
      return $unaPlantilla->get();
    }
  }

  function maestro($arregloDatos) {
		$this->plantilla->loadTemplateFile(PLANTILLAS.'controlMaestro.html',true,true);
    $this->plantilla->setVariable('comodin',' ');

    //Captura y Coloca el Título
    $this->getTitulo($arregloDatos);    
		$this->mantenerDatos($arregloDatos,$this->plantilla);
    $arregloDatos['mostrar'] = 0;
    $arregloDatos['plantilla'] = 'controlToolbar.html';
    $arregloDatos['thisFunction'] = 'getToolbar';
    $this->plantilla->setVariable('toolbarControl',$this->cargaPlantilla($arregloDatos));
    if(empty($arregloDatos['por_cuenta_filtro'])) {
      //Indica que debe abrir la ventana de filtro de Controles
      $this->plantilla->setVariable('abre_ventana',1);
    } else {
      //Indica que no debe abrir la ventana de filtro de Controles
      $this->plantilla->setVariable('abre_ventana',0);
      //La Lógica envía la plantilla y el método para pintar el TAB de Mercancía
      $arregloDatos['mostrar'] = 0;
      $arregloDatos['plantilla'] = $arregloDatos['plantillaMercancia'];
      $arregloDatos['thisFunction'] = $arregloDatos['metodoMercancia'];  
      $htmlMercancia = $this->setFuncion($arregloDatos,$this->datos);
      $this->plantilla->setVariable('htmlMercancia',$htmlMercancia);
      //Muestra información en el TAB Control
      $arregloDatos['mostrar'] = 0;
      $arregloDatos['plantilla'] = $arregloDatos['plantillaControl'];
      $arregloDatos['thisFunction'] = $arregloDatos['metodoControl']; 
      $htmlBloquear = $this->setFuncion($arregloDatos,$this->datos);
      $this->plantilla->setVariable('htmlBloquear',$htmlBloquear);
    }
    $unDatos = new Control();
    $arregloDatos['mostrar'] = 0;
    $arregloDatos['plantilla'] = $arregloDatos['plantillaFiltro'];
    $arregloDatos['thisFunction'] = 'filtroControl';
    $htmlFiltroctrl = $this->cargaPlantilla($arregloDatos);  
    $this->plantilla->setVariable('filtroControl',$htmlFiltroctrl);
    $this->plantilla->show();
  }

  function filtroControl($arregloDatos,$unDatos,$plantilla) {}

  function getListaControles($arregloDatos,$unDatos,$plantilla) {
    $this->mantenerDatos($arregloDatos,$plantilla);
  }
  
  function getItemBloquear($arregloDatos) {
    $unDatos = new Control();
    $htmlBloquearx = $this->cargaPlantilla($arregloDatos);  
    $this->plantilla->setVariable('controlBloquear',$htmlBloquearx);
    $this->plantilla->show();
  }
  
  //Función visualiza documentos bloqueados
  function getControlDocumento($arregloDatos,$datos,$plantilla) {
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  function controlBloquear($arregloDatos,$unDatos,$plantilla) {
    $unaLista = new Control();
    
    //Carga Cuadro Combinado de Entidades
    $lista = $unaLista->lista("controles_entidades");
    $listaEntidades = $unaLista->armSelect($lista,'[Seleccionar]',NULL);
    $plantilla->setVariable("listaEntidades", $listaEntidades);
    
    //Carga Cuadro Combinado de Controles
    $lista = $unaLista->lista("controles_control");
    $listaControles = $unaLista->armSelect($lista,'[Seleccionar]',NULL);
    $plantilla->setVariable("listaControles", $listaControles);
  }

  function listaInventario($arregloDatos,&$datos,&$plantilla) {  
    $this->setValores($arregloDatos,$datos,$plantilla);
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  function getMercanciaBloquear($arregloDatos,$datos,$plantilla) {
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  function getInventario($arregloDatos,$datos,$plantilla) { 
    $this->setValores($arregloDatos,$datos,$plantilla);
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  function getToolbar($arregloDatos,&$datos,&$plantilla) {
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  function traeLevante($arregloDatos,&$datos,&$plantilla) {
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  function getTitulo(&$arregloDatos) {
    if(!empty($arregloDatos['por_cuenta_filtro'])) {
      $unControl = new Control();
      $unControl->getCliente($arregloDatos);
      $dato = $unControl->db->fetch();
      $arregloDatos['titulo'] .= " - [".$dato->numero_documento."] <b>" . $dato->razon_social . "</b>";
    } else $arregloDatos['titulo'] = "CONTROLES";
  } 

	function mostrarMensaje($arregloDatos) {
		if($arregloDatos['info']) {
			$msg = "Se ha enviado un correo a la cuenta: ".$arregloDatos['email']." satisfactoriamente.";
			$registrar = -1;
		} else {
			$msg = "Error al enviar el correo electr\u00f3nico, por favor revisar el servidor de correo saliente.";
			$registrar = 0;
		}
		echo "<script type='text/javascript'>alert('$msg');</script>";
		if($registrar) {
			//Registra información del correo en la tabla de Tracking
			$unCorreo = new Orden();
			$unCorreo = $unCorreo->registroCorreo($arregloDatos);
			//Notifica resultado del registro
			if(!$unCorreo) {
				$msg = "Error al intentar registrar el correo a la cuenta: ".$arregloDatos['email'];
				echo "<script type='text/javascript'>alert('$msg');</script>";			
			}			
		}
	}
}
?>