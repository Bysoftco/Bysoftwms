<?php
require_once("HTML/Template/IT.php");
require_once("OrdenDatos.php");

class OrdenPresentacion {
	var $datos;
	var $plantilla;

	function OrdenPresentacion(&$datos) {
		$this->datos = $datos;
		$this->plantilla = new HTML_Template_IT();
	}
	
  function cargarFotos($arregloDatos,$datos,$plantilla) {}

  function regBorrar($arregloDatos) {}
	
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
			$plantilla->setVariable($key,$value);
		}
	}

	function setDatosOrden($arregloDatos,&$datos,&$plantilla) {
		if($datos->salud)	{ $plantilla->setVariable('check_salud', 'checked'); }
	}
	
	function getLista($arregloDatos,$seleccion,&$plantilla) {
		$unaLista = new Orden();
		$lista = $unaLista->lista($arregloDatos['tabla'],$arregloDatos['condicion'],$arregloDatos['campoCondicion']);
		$lista = $unaLista->armSelect($lista,'[seleccione]',$seleccion);
		
		$plantilla->setVariable($arregloDatos['labelLista'],$lista);
	}
	
	function cargaPlantilla($arregloDatos) {
		$unAplicaciones = new Orden();
		$formularioPlantilla = new HTML_Template_IT(PLANTILLAS);
    
		$formularioPlantilla->loadTemplateFile($arregloDatos['plantilla'],true,true);
		$formularioPlantilla->setVariable('comodin',' ');
		$this->mantenerDatos($arregloDatos,$formularioPlantilla);

		$metodo = $arregloDatos['thisFunction'];
		$this->$metodo($arregloDatos,$this->datos,$formularioPlantilla);
  
		if($arregloDatos['mostrar']) {
			$formularioPlantilla->show();
		} else {
			return $formularioPlantilla->get();
		}
	}
	
	//Arma cada Formulario o función en pantalla
	function setFuncion($arregloDatos,&$unDatos) {
		$unDatos = new Orden();
		$metodo = $arregloDatos['thisFunction'];

		$r = $unDatos->$metodo($arregloDatos); //Ejecuta Método en Datos

		$unaPlantilla = new HTML_Template_IT(PLANTILLAS);
		$unaPlantilla->loadTemplateFile($arregloDatos['plantilla'],true,true);
		$unaPlantilla->setVariable('comodin','');
		if(!empty($unDatos->mensaje)) {
			$unaPlantilla->setVariable('mensaje',$unDatos->mensaje);
			$unaPlantilla->setVariable('estilo',$unDatos->estilo);
		}
		if(!empty($arregloDatos['F_aux'])) {
			$unaPlantilla->setVariable('mensaje',$arregloDatos['mensaje_aux']);
			$unaPlantilla->setVariable('estilo',$arregloDatos['mensaje_aux']);
		}

		$this->mantenerDatos($arregloDatos,$unaPlantilla);
		$arregloDatos['n'] = 0;
		$rows = $unDatos->db->countRows();
		$unaPlantilla->setVariable('num_registros',$rows);
		while($obj=$unDatos->db->fetch()) {
			$odd = $arregloDatos['n'] % 2 ? 'odd' : '';
			$arregloDatos['n'] = $arregloDatos['n'] + 1;
			$unaPlantilla->setCurrentBlock('ROW');
			$this->setDatos($arregloDatos,$obj,$unaPlantilla);
			$metodo = $arregloDatos['thisFunction'];
			$this->$metodo($arregloDatos,$obj,$unaPlantilla);
			$unaPlantilla->setVariable('n', $arregloDatos['n']);
			if($metodo=='datosOrden' && $rows==1) break;
			$unaPlantilla->setVariable('odd', $odd);
			$unaPlantilla->parseCurrentBlock();
		}
		if($rows == 0 and empty($unDatos->mensaje_error)) {
			$unaPlantilla->setVariable('mensaje','&nbsp;No hay registros para listar');
			$unaPlantilla->setVariable('estilo','ui-state-error');
		}

		if(!empty($unDatos->mensajepersonalizado)) {
			$unaPlantilla->setVariable('mensaje',$unDatos->mensajepersonalizado);
			$unaPlantilla->setVariable('estilo','ui-state-error');
		}

    if($arregloDatos['mostrar']) {
      $unaPlantilla->show();
    } else {
      return $unaPlantilla->get();
    }
	}

	// Función para convertir arreglo en objeto stdClass
	function converArrObj($Arreglo) {	     
	    // Crea nuevo objeto stdClass
	    $objeto = new stdClass();
	     
	    // Usa bucle para convertir arreglo en
	    // objeto stdClass
	    foreach($Arreglo as $clave => $valor) {
	        if(is_array($valor)) {
						$valor = converArrObj($valor);
	        }
	        $objeto->$clave = $valor;
	    }
	    return $objeto;
	}

	// Función que lista y obtiene los Arribos asignados al DO
	// Autor: Fredy Salom	Fecha: 17/02/2023
	function setArribos($arregloDatos,&$unDatos) {
		$rsDatos = new Orden();
		$metodo = $arregloDatos['thisFunction'];

		$r = $rsDatos->$metodo($arregloDatos);

		$unaPlantilla = new HTML_Template_IT(PLANTILLAS);
		$unaPlantilla->loadTemplateFile($arregloDatos['plantilla'],true,true);
		$unaPlantilla->setVariable('comodin','');
		if(!empty($rsDatos->mensaje)) {
			$unaPlantilla->setVariable('mensaje',$rsDatos->mensaje);
			$unaPlantilla->setVariable('estilo',$rsDatos->estilo);
		}
		if(!empty($arregloDatos['F_aux'])) {
			$unaPlantilla->setVariable('mensaje',$arregloDatos['mensaje_aux']);
			$unaPlantilla->setVariable('estilo',$arregloDatos['mensaje_aux']);
		}

		$this->mantenerDatos($arregloDatos,$unaPlantilla);
		$arregloDatos['n'] = 0;
		$rows = $rsDatos->db->countRows();
		$unaPlantilla->setVariable('num_registros',$rows);
		$datos = $rsDatos->db->getArray();
		foreach($datos as $arribos) {
			$odd = $arregloDatos['n'] % 2 ? 'odd' : '';
			$arregloDatos['n'] = $arregloDatos['n'] + 1;
			$unaPlantilla->setCurrentBlock('ROW');
			// Convertimos el Arreglo a Objeto
			$objArribo = $this->converArrObj($arribos);
			$this->setDatos($arregloDatos,$objArribo,$unaPlantilla);
			$metodo = $arregloDatos['thisFunction'];
			$this->$metodo($arregloDatos,$objArribo,$unaPlantilla);
			$unaPlantilla->setVariable('n', $arregloDatos['n']);
			$unaPlantilla->setVariable('odd', $odd);
			$unaPlantilla->parseCurrentBlock();			
		}
		if($rows == 0 and empty($rsDatos->mensaje_error)) {
			$unaPlantilla->setVariable('mensaje','&nbsp;No hay registros para listar');
			$unaPlantilla->setVariable('estilo','ui-state-error');
		}

		if(!empty($rsDatos->mensajepersonalizado)) {
			$unaPlantilla->setVariable('mensaje',$rsDatos->mensajepersonalizado);
			$unaPlantilla->setVariable('estilo','ui-state-error');
		}

    if($arregloDatos['mostrar']) {
      $unaPlantilla->show();
    } else {
      return $unaPlantilla->get();
    }
	}

	// Función imprime Ingreso de Mercancía / Acta Inconsistencias
	// Autor: Fredy Salom	- Fecha: 30/05/2023
	function setImprimir($arregloDatos,&$unDatos) {
		$impDatos = new Orden();
		$metodo = $arregloDatos['thisFunction'];

		$r = $impDatos->$metodo($arregloDatos);

		$shPlantilla = new HTML_Template_IT(PLANTILLAS);
		$shPlantilla->loadTemplateFile($arregloDatos['plantilla'],true,true);
		$shPlantilla->setVariable('comodin','');
		if(!empty($impDatos->mensaje)) {
			$shPlantilla->setVariable('mensaje',$impDatos->mensaje);
			$shPlantilla->setVariable('estilo',$impDatos->estilo);
		}
		if(!empty($arregloDatos['F_aux'])) {
			$shPlantilla->setVariable('mensaje',$arregloDatos['mensaje_aux']);
			$shPlantilla->setVariable('estilo',$arregloDatos['mensaje_aux']);
		}

		$this->mantenerDatos($arregloDatos,$shPlantilla);
		$arregloDatos['n'] = 0;
		$rows = count($r);
		$shPlantilla->setVariable('num_registros',$rows);
		$datos = $r;
		foreach($datos as $dimprimir) {
			$odd = $arregloDatos['n'] % 2 ? 'odd' : '';
			$arregloDatos['n'] = $arregloDatos['n'] + 1;
			$shPlantilla->setCurrentBlock('ROW');
			// Convertimos el Arreglo a Objeto
			$objImprimir = $this->converArrObj($dimprimir);
			$this->setDatos($arregloDatos,$objImprimir,$shPlantilla);
			$metodo = $arregloDatos['thisFunction'];
			$this->$metodo($arregloDatos,$objImprimir,$shPlantilla);
			$shPlantilla->setVariable('n', $arregloDatos['n']);
			$shPlantilla->setVariable('odd', $odd);
			$shPlantilla->parseCurrentBlock();			
		}
		if($rows == 0 and empty($impDatos->mensaje_error)) {
			$shPlantilla->setVariable('mensaje','&nbsp;No hay registros para imprimir');
			$shPlantilla->setVariable('estilo','ui-state-error');
		}

		if(!empty($impDatos->mensajepersonalizado)) {
			$shPlantilla->setVariable('mensaje',$impDatos->mensajepersonalizado);
			$shPlantilla->setVariable('estilo','ui-state-error');
		}

    if($arregloDatos['mostrar']) {
      $shPlantilla->show();
    } else {
      return $shPlantilla->get();
    }
	}

	function ordenArriboMaestro($arregloDatos) {
		$unaPlantilla = new HTML_Template_IT(PLANTILLAS);
		$unaPlantilla->loadTemplateFile('ordenArriboMaestro.html',true,true);
		$unaPlantilla->setVariable('mensaje',$this->datos->mensaje);
		$unaPlantilla->setVariable('estilo',$this->datos->estilo);

		$this->mantenerDatos($arregloDatos,$unaPlantilla);

		$unDatos = new Orden();		
		$arregloDatos['id_tab'] = 0;
		
		if(empty($arregloDatos['tab_index'])) { $arregloDatos['tab_index'] = 0; }
		$arregloDatos['mostrar'] = "0";
		$arregloDatos['plantilla'] = 'ordenArriboToolbar.html';
		$arregloDatos['thisFunction'] = 'getToolbar';
		
		// Carga información del Perfil
		$arregloDatos['perfil'] = $_SESSION['datos_logueo']['perfil_id'];
		// Valida el Perfil para identificar el Tercero
		$arregloDatos['verToolbar'] = $arregloDatos['perfil'] == 23 ? 'none' : 'block';
		$unaPlantilla->setVariable('verToolbar',$arregloDatos['verToolbar']);
		$unaPlantilla->setVariable('toolbarArribo',$this->cargaPlantilla($arregloDatos));
		
		//Si se está creando la orden se deja en modo edición
		$arregloDatos['plantilla'] = ($arregloDatos['metodo'] == 'addOrden') ? 'ordenDatosForm.html' : 'ordenDatosInfo.html';
		//Info de la Orden
		$arregloDatos['thisFunction']	= 'datosOrden';
		$htmlDatosOrden =	$this->setFuncion($arregloDatos,$unDatos);
		$unaPlantilla->setVariable('datosOrden', $htmlDatosOrden);
		$arregloDatos['plantilla'] = 'ordenListaArribos.html';
		$arregloDatos['thisFunction']	= 'listaArribos';
		$htmlListaArribos =	$this->setArribos($arregloDatos,$this->datos);
		$unaPlantilla->setVariable('listaArribos',$htmlListaArribos);
		
		//Aquí se decide  si se abre la ventana por default
		$unaPlantilla->setVariable('abre_ventana',0);
		$unaPlantilla->setVariable('nombre_empleado',$arregloDatos['nombre_empleado']);
		$unaPlantilla->show();
		die();
	}
	
	function maestro($arregloDatos) {
		$this->plantilla->loadTemplateFile(PLANTILLAS.'ordenMaestro.html',true,true);
		$this->plantilla->setVariable('mensaje',$this->datos->mensaje);
		$this->plantilla->setVariable('estilo',$this->datos->estilo);
		
		if(empty($arregloDatos['tab_index'])) { $arregloDatos['tab_index'] = 0; }
		if(empty($arregloDatos['naturaleza_filtro'])) {
			$arregloDatos['naturaleza_filtro'] = 0;
		}

		$this->mantenerDatos($arregloDatos,$this->plantilla);
		
		$unDatos = new Orden();
		$arregloDatos['id_tab'] = 0;

    //Carga información del Perfil
    $arregloDatos['perfil'] = $_SESSION['datos_logueo']['perfil_id'];
    //Valida el Perfil para identificar el Tercero
    if($arregloDatos['perfil'] == 23) {
      $arregloDatos['soloLectura'] = "readonly=''";
      //Carga información del Usuario
      $arregloDatos['usuario'] = $_SESSION['datos_logueo']['usuario'];
      $arregloDatos['por_cuenta'] = $arregloDatos['usuario'];
      $datos = $unDatos->findClientet($arregloDatos['por_cuenta']);
      $arregloDatos['cliente'] = $datos->razon_social;
      $arregloDatos['email'] = $datos->correo_electronico;
      $arregloDatos['comercial'] = $datos->nombre_vendedor;
      $arregloDatos['numdoc'] = $arregloDatos['por_cuenta'];
      $arregloDatos['zfcode'] = $unDatos->existeZFCode($arregloDatos);
    } else {
      $arregloDatos['soloLectura'] = "";
      $arregloDatos['usuario'] = "";
      $arregloDatos['cliente'] = "";
    }
		
		$arregloDatos['plantilla'] = 'ordenCrear.html';
		$arregloDatos['thisFunction'] = 'formularioCrear';
		$htmlFormularioCrear = $this->cargaPlantilla($arregloDatos);
		$this->plantilla->setVariable('htmlFormularioCrear',$htmlFormularioCrear);
		
		$arregloDatos['mostrar'] = 0;
		$arregloDatos['metodo'] = 'listaUbicaciones';
		$arregloDatos['tituloFormulario'] = 'Modificar Orden';
		$arregloDatos['plantilla'] = 'ordenListadoUbicacion.html';
		$arregloDatos['thisFunction'] = 'listaUbicaciones';  
		$htmlListaUbicaciones=$this->setFuncion($arregloDatos,$this->datos);
		$this->plantilla->setVariable('htmlListaUbicaciones', $htmlListaUbicaciones);
		
		//Inserción del código para Información de Carga
					
		//Aquí se decide  si se abre la ventana por default
		$this->plantilla->setVariable('abre_ventana',0);		
		$this->plantilla->setVariable('nombre_empleado',$arregloDatos['nombre_empleado']);

		$this->plantilla->show();
	}
	
	function listaUbicaciones($arregloDatos,$unDatos,$unaPlantilla) {
		$do_asignado = str_pad($unDatos->do_asignado,3,'0',STR_PAD_LEFT);
		$anio = substr($unDatos->anio,2,2);
		$mes = date("m");
		$do_asignado = $anio.$mes.$unDatos->ciudad.$unDatos->bodega.$unDatos->contador.$do_asignado;
		$unaPlantilla->setVariable('do_asignado',$do_asignado);
		
		//si es cambio de  año se inicializan contadores
		if($unDatos->contador > 9) {
			echo "<div class='ui-state-error'>Error de desbordamiento, el n&uacute;mero de DOs super&oacute; el tope definido para la ubicaci&oacute;n $unDatos->nombre</div>";
		}
		$arregloDatos['year'] = date('Y');
		if($arregloDatos['year'] <> $unDatos->anio) {
			$unaUbicacion = new Orden();
			$arregloDatos['codigo_bodega'] = $unDatos->codigo;
			$arregloDatos['contador'] = (intval($unDatos->doini) == 901) ? 9 : 0;
			echo "<div class='ui-state-error'>Se Iniciaron Consecutivos por Cambio de año. De clic de nuevo en el link para ingresar a este formulario</div>";
			$unaUbicacion->inicializaContadores($arregloDatos);
		}
	}
	
	function listaDos($arregloDatos) {
		$this->plantilla->loadTemplateFile(PLANTILLAS .'ordenListado.html',false,false);
		$this->mantenerDatos($arregloDatos,$this->plantilla);
		$this->plantilla->setVariable('comodin','');
		$this->plantilla->show();
	}
	
	function getToolbar($arregloDatos,&$datos,&$plantilla) {
		$this->mantenerDatos($arregloDatos,$plantilla);
	}

	function maestroConsulta($arregloDatos) {
		$this->plantilla->loadTemplateFile(PLANTILLAS.'ordenMaestroConsulta.html',true,true);
		$this->mantenerDatos($arregloDatos,$this->plantilla);
		$this->plantilla->setVariable('comodin','');
	
		if(!empty($arregloDatos['filtro'])) {
			$arregloDatos['mostrar'] = 0;
			$arregloDatos['plantilla'] = $arregloDatos['mostrar_plantilla'];
			$arregloDatos['thisFunction'] = 'listarOrdenes';
			$htmlListado = $this->setFuncion($arregloDatos,$this->datos);
			$this->plantilla->setVariable('htmlListado',$htmlListado);
		} else {
			$arregloDatos['mostrar'] = 0;
			$arregloDatos['plantilla'] = 'ordenReporteFiltro.html';
			$arregloDatos['thisFunction'] = 'filtro';
      // Carga información del Perfil y Usuario
      $arregloDatos['perfil'] = $_SESSION['datos_logueo']['perfil_id'];
      // Valida el Perfil para identificar el Tercero
      if($arregloDatos['perfil'] == 23) {
        $arregloDatos['soloLectura'] = "readonly=''";
        $arregloDatos['usuario'] = $_SESSION['datos_logueo']['usuario'];
        $arregloDatos['por_cuenta'] = $arregloDatos['usuario'];
        $unDatos = new Orden();
      	$datos = $unDatos->findClientet($arregloDatos['por_cuenta']);
      	$arregloDatos['cliente'] = $datos->razon_social;
      } else {
        $arregloDatos['soloLectura'] = "";
        $arregloDatos['usuario'] = "";
        $arregloDatos['cliente'] = "";
      }
			$htmlFiltro = $this->cargaPlantilla($arregloDatos);
			$this->plantilla->setVariable('filtroEntradaConsulta',$htmlFiltro);
		}
		$this->plantilla->show();
	}
	
	function maestroReapertura($arregloDatos) {
		$this->plantilla->loadTemplateFile(PLANTILLAS.'ordenMaestroReapertura.html',true,true);

		if(empty($arregloDatos['id_index'])) {
			$arregloDatos['tab_index'] = 0;
			$arregloDatos['id_tab'] = 0;
		}
		$this->mantenerDatos($arregloDatos,$this->plantilla);
		
		//Aquí se decide  si se abre la ventana por default
		$this->plantilla->setVariable('abre_ventana',0);
			
    if(empty($arregloDatos['filtro'])) {
			$arregloDatos['mostrarFiltroEstado'] = 'none';
			$arregloDatos['thisFunction'] = 'filtro';  
			$arregloDatos['plantilla'] = 'ordenReporteFiltro.html';
			$arregloDatos['mostrar'] = 0;
			
			$htmlFormularioVentana = $this->cargaPlantilla($arregloDatos);
			$this->plantilla->setVariable('filtroEntrada',$htmlFormularioVentana);
			$this->plantilla->setVariable('abre_ventana',1);
		} else {
			$unDatos = new Orden();
			$arregloDatos['id_tab'] = 0;
			$arregloDatos['mostrar'] = 0;
			$arregloDatos['reasignado'] = 'Si';
			$arregloDatos['plantilla'] = 'ordenListadoReapertura.html';
			$arregloDatos['thisFunction'] = 'listarOrdenes';
			$htmlListado = $this->setFuncion($arregloDatos,$unDatos);
			$this->plantilla->setVariable('htmlListado',$htmlListado);
		}	
		
		$this->plantilla->setVariable('nombre_empleado',$arregloDatos['nombre_empleado']);
		$this->plantilla->show();
	}

	function formularioCrear($arregloDatos,$datos,$plantilla) {
		$arregloDatos['tabla'] = 'do_bodegas';
		$arregloDatos['labelLista'] = 'listaBodegas';
		$arregloDatos['condicion'];	$arregloDatos['campoCondicion'];
		$this->getLista($arregloDatos,trim($datos->naturaleza),$plantilla);

		$unaLista = new Orden();
		$lista = $unaLista->getTipoOperacion($arregloDatos);
		$listaOperaciones	= $unaLista->armSelect($lista,'[Operacion]',NULL);
		$arregloDatos['listaOperaciones'] = $listaOperaciones;
		
		$arregloDatos['tabla'] = 'tipos_documentos_transportes';
		$arregloDatos['labelLista'] = 'listaTiposDocumentos';
		$this->getLista($arregloDatos,NULL,$plantilla);
		
		$arregloDatos['tabla'] = 'modalidad_importacion';
		$arregloDatos['labelLista']	= 'listaModalidad';
		$this->getLista($arregloDatos,'C100',$plantilla);
		
		$this->setDatosOrden($arregloDatos,$datos,$plantilla);
		$this->mantenerDatos($arregloDatos,$plantilla);
	}

	function newCamion($arregloDatos) {}
	
	function listarOrdenes($arregloDatos,&$datos,&$plantilla) {
		$plantilla->setVariable('img_editar','layer--pencil.png');
		if($arregloDatos['accion'] == 'reapertura') {
			$linkReapertura = "<a href='javascript: reapertura($datos->do_asignado)' class='signup documento_id' title='Reapertura Do $datos->do_asignado' id='{n}' cursor><img src='integrado/imagenes/ico_reapertura.gif' border='0'></a>";
			$plantilla->setVariable('linkReapertura',$linkReapertura);
		}
	}

	function datosOrden($arregloDatos,$datos,$plantilla) {
		$arregloDatos['tabla'] = 'tipos_documentos_transportes';
		$arregloDatos['labelLista'] = 'listaTiposDocumentos';
		$this->getLista($arregloDatos,$datos->tipo_documento,$plantilla);

		$unaLista = new Orden();
		$lista = $unaLista->getTipoOperacion($arregloDatos);
		$listaOperaciones	= $unaLista->armSelect($lista,'[Operacion]',$datos->tipo_operacion);
		$arregloDatos['listaOperaciones'] = $listaOperaciones;
		
		$arregloDatos['tabla'] = 'do_bodegas';
		$arregloDatos['labelLista'] = 'listaBodegas';
		$arregloDatos['condicion'];	$arregloDatos['campoCondicion'];
		$this->getLista($arregloDatos,trim($datos->bodega),$plantilla);

		if($datos->ind_cons == 'Si') { $arregloDatos['checked_ind_cons'] = 'checked'; }
		if($datos->ind_fragil == 'Si') { $arregloDatos['checked_ind_fragil'] = 'checked'; }
		if($datos->ind_hielo == 'Si') { $arregloDatos['checked_ind_hielo'] = 'checked'; }
		if($datos->ind_asig == 'Si') { $arregloDatos['checked_ind_asig'] = 'checked'; }
		if($datos->ind_venci == 'Si') { $arregloDatos['checked_ind_venci'] = 'checked'; }
		if($datos->ind_ubica == 'Si') { $arregloDatos['checked_ind_ubica' ]	= 'checked'; }

		//Muestra Lista de Fotos - Proceso que pinta la plantilla
    $arregloDatos['mostrar'] = 0;
    $arregloDatos['plantilla'] = 'ordenListaFotos.html';
    $arregloDatos['thisFunction'] = 'listarFotos';
    $getFotos = $this->setFuncion($arregloDatos,$datos);
    $plantilla->setVariable('getFotos',$getFotos);

    $this->setDatosOrden($arregloDatos,$datos,$plantilla);
		$this->mantenerDatos($arregloDatos,$plantilla);
	}
	
	// Crea el Contenido de un acordión o sea un arribo
	function listaArribos($arregloDatos,$datos,&$plantilla) {
		// Solo Se pinta el primer arribo por rendimiento y porque es el único que ve el usuario por default
		
		if($arregloDatos['n'] == 1) {
			$arregloDatos['id_form'] = $arregloDatos['n'];
			$arregloDatos['plantilla'] = 'ordenArriboInfo.html';
			
			if($arregloDatos['metodo'] == 'addArribo' or $arregloDatos['metodo'] == 'addOrden') {
				$arregloDatos['plantilla'] = 'ordenArribo.html';
			}
                       
			$arregloDatos['arribo'] = $datos->arribo;       	
			$arregloDatos['thisFunction'] = 'getArribo';
			$arregloDatos['thisFunctionAux'] = NULL;
			//$htmlArribo =	$this->obtenerArribo($arregloDatos,$datos);
			$htmlArribo =	$this->setArribos($arregloDatos,$datos);
			$plantilla->setVariable('htmlArribo',$htmlArribo);
		}
	}

	function getArribo($arregloDatos,&$datos,&$plantilla) {
		//se verifica si ya hay movimientos para bloquear modificación
		$unOrden = new Levante();
		$unOrden->findMovimientos($arregloDatos);
		if($datos->peso_bruto == 0) {
			$arregloDatos['mostrarInventario'] = 'none';
		}
		$arregloDatos['tabla'] = 'transportador';
		$arregloDatos['labelLista']	= 'selectTransportador';
		$this->getLista($arregloDatos,$datos->transportador,$plantilla);
		$arregloDatos['tabla'] = 'monedas';
		$arregloDatos['labelLista'] = 'selectMonedas';
		$this->getLista($arregloDatos,$datos->moneda,$plantilla);

		$arregloDatos['moneda']=$datos->moneda;
		$arregloDatos['tipo_operacion']=$datos->tipo_operacion;
		$arregloDatos['tabla'] = 'posiciones';
		$arregloDatos['labelLista'] = 'selectUbicacion';
		$this->getLista($arregloDatos,$datos->moneda,$plantilla);
		
		$this->mantenerDatos($arregloDatos,$plantilla);
	}
	
	function filtro($arregloDatos,$datos,$plantilla) {
		$arregloDatos['tabla'] = 'do_bodegas';
		$arregloDatos['labelLista'] = 'listaBodegas';
		$this->getLista($arregloDatos,NULL,$plantilla);
		
		$arregloDatos['tabla'] = 'do_estados';
		$arregloDatos['labelLista'] = 'listaEstados';
		$this->getLista($arregloDatos,NULL,$plantilla);
	}

	function listarFotos($arregloDatos,$datos,$plantilla) {}
	
	function mostrarMensaje($arregloDatos) {
		if($arregloDatos['info']) {
			$msg = "Se ha enviado un correo a la(s) cuenta(s): ".$arregloDatos['email']." satisfactoriamente.";
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

  function getActaIngreso($arregloDatos) {}
}
?>