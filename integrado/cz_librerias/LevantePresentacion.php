<?php
require_once ("HTML/Template/IT.php");
require_once ("InventarioDatos.php");
require_once ("LevanteLogica.php");

class LevantePresentacion {
  var $datos;
  var $plantilla;
  var $tot_fob_nac = 0;
  var $tot_peso_nac = 0;
  var $tot_cant_nac = 0;
  var $cuenta = 0;

  function LevantePresentacion(&$datos) {
  //VERSION 20032017
    $this->datos =& $datos;
    $this->plantilla = new HTML_Template_IT();
    
  }

  function mantenerDatos($arregloCampos, &$plantilla) {
    $plantilla =& $plantilla;
    if(is_array($arregloCampos)) {
      foreach($arregloCampos as $key => $value) {
        $plantilla->setVariable($key, $value);
      }
    }
  }

  //Función que coloca los datos que vienen de la BD
  function setDatos($arregloDatos, &$datos, &$plantilla) {
    foreach($datos as $key => $value) {
      $plantilla->setVariable($key, $value);
    }
  }

  function getLista($arregloDatos, $seleccion, &$plantilla) {
    $unaLista = new Levante();
    $lista = $unaLista->lista($arregloDatos[tabla], $arregloDatos[condicion], $arregloDatos[campoCondicion]);

    $lista = armaSelect($lista, '[seleccione]', $seleccion);
    $plantilla->setVariable($arregloDatos[labelLista], $lista);
  }

  function cargaPlantilla($arregloDatos) {
    $unAplicaciones = new Levante();
    $formularioPlantilla = new HTML_Template_IT();
    $formularioPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla], false, false);
    $formularioPlantilla->setVariable('comodin', ' ');
    $this->mantenerDatos($arregloDatos, $formularioPlantilla);

    $this->$arregloDatos[thisFunction]($arregloDatos, $this->datos, $formularioPlantilla);
    if($arregloDatos[mostrar]) {
      $formularioPlantilla->show();
    } else {
      return $formularioPlantilla->get();
    }
  }

  // Arma cada Formulario o función en pantalla
  function setFuncion($arregloDatos, $unDatos) {
    $unDatos = new Levante();
    if(!empty($arregloDatos[setCharset])) {
      header('Content-type: text/html; charset=iso-8859-1');
    }

    $r = $unDatos->$arregloDatos[thisFunction]($arregloDatos);

    $unaPlantilla = new HTML_Template_IT();
    $unaPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla], true, true);
    $unaPlantilla->setVariable('comodin', ' ');
    if(!empty($arregloDatos[mensaje])) {
      $unaPlantilla->setVariable('mensaje', $arregloDatos[mensaje]);
      $unaPlantilla->setVariable('estilo', $arregloDatos[estilo]);
    }

    $this->mantenerDatos($arregloDatos, $unaPlantilla);
    $$arregloDatos[n] = 0;
    while($unDatos->fetch()) {
      $odd = ($arregloDatos[n] % 2) ? 'odd' : '';
      $arregloDatos[n] = $arregloDatos[n] + 1;
      $unaPlantilla->setCurrentBlock('ROW');
      $this->setDatos($arregloDatos, $unDatos, $unaPlantilla);

      $this->$arregloDatos[thisFunction]($arregloDatos, $unDatos, $unaPlantilla);
      $unaPlantilla->setVariable('n', $arregloDatos[n]);
      $unaPlantilla->setVariable('odd', $odd);
      $unaPlantilla->parseCurrentBlock();
    }
    if(!empty($arregloDatos[thisFunctionAux])) {
      $this->$arregloDatos[thisFunction]($arregloDatos, $unDatos, $unaPlantilla);
    }

    if($unDatos->N == 0 and empty($unDatos->mensaje)) {
      $unaPlantilla->setVariable('mensaje', 'No hay registros para listar' . $arregloDatos[mensaje]);
      $unaPlantilla->setVariable('estilo', 'ui-state-error');
      $unaPlantilla->setVariable('mostrarCuerpo', 'none');
    }
    $unaPlantilla->setVariable('num_registros', $unDatos->N);

    if($arregloDatos[mostrar]) {
      $unaPlantilla->show();
    } else {
      $unaPlantilla->setVariable('cuenta', $this->cuenta);
      return $unaPlantilla->get();
    }
  }

  function maestro($arregloDatos) {
  
    if($arregloDatos[tipo_retiro_label] == "Matriz") {
      $this->plantilla->setVariable('tipo_retiro_label', 'Matriz');
    }
    $this->plantilla->loadTemplateFile(PLANTILLAS . 'levanteMaestro.html', true, true);
    $this->plantilla->setVariable('comodin', ' ');

    $arregloDatos[tab_index] = 2;
    $this->getTitulo($arregloDatos);
    $this->mantenerDatos($arregloDatos, $this->plantilla);

    $arregloDatos[mostar] = "0";
    //Valida si existe tipo de retiro
    if(isset($arregloDatos[tipo_retiro])) {
      //Convierte el tipo Retiro basado en ei tipo de Remesa
      $arregloDatos[tipo_retiro_label] = $this->datos->tipos_remesas($arregloDatos[tipo_retiro]); 
    }
    $arregloDatos[plantilla] = 'levanteToolbar.html';
    $arregloDatos[thisFunction] = 'getToolbar';
    $this->plantilla->setVariable('toolbarLevante', $this->setFuncion($arregloDatos, $this->datos));

    if(empty($arregloDatos[por_cuenta_filtro])) {
      $this->plantilla->setVariable('abre_ventana', 1);
    } else {
      $this->plantilla->setVariable('abre_ventana', 0);
      // el método controlarTransaccion de la Logica envia la plantilla y el método para pintar el TAB de mercancia
      $arregloDatos[mostrar] = 0;
      $arregloDatos[plantilla] = $arregloDatos[plantillaMercanciaCuerpo];
      $arregloDatos[thisFunction] = $arregloDatos[metodoMercanciaCuerpo];
      $htmlMercancia = $this->setFuncion($arregloDatos, $this->datos);
      $this->plantilla->setVariable('htmlMercancia', $htmlMercancia);

      //Pinta el formulario en el TAB  levante
      $arregloDatos[mostrar] = 0;
      $arregloDatos[plantilla] = $arregloDatos[plantillaCabeza];
      $arregloDatos[thisFunction] = $arregloDatos[metodoCabeza];
      $htmlLevante = $this->setFuncion($arregloDatos, $this->datos);
      $this->plantilla->setVariable('htmlLevante', $htmlLevante);

      //Cuerpo del movimiento
      $arregloDatos[mostrar] = 0;
      $arregloDatos[plantilla] = $arregloDatos[plantillaCuerpo];
      $arregloDatos[thisFunction] = $arregloDatos[metodoCuerpo];
      $htmlLevante = $this->setFuncion($arregloDatos, $this->datos);
      $this->plantilla->setVariable('htmlCuerpo', $htmlLevante);
    }

    //Carga información del Perfil
    $arregloDatos[perfil] = $_SESSION['datos_logueo']['perfil_id'];
    //Valida el Perfil para identificar el Tercero
    if($arregloDatos[perfil] == 23) {
      $arregloDatos[soloLectura] = "readonly=''";
      //Carga información del usuario
      $arregloDatos[usuario] = $_SESSION['datos_logueo']['usuario'];
      $arregloDatos[cliente] = $this->datos->findClientet($arregloDatos[usuario]);
    } else {
      $arregloDatos[soloLectura] = "";
      $arregloDatos[usuario] = "";
      $arregloDatos[cliente] = "";
    }
    $unDatos = new Levante();
    $arregloDatos[id_tab] = 2;
    $arregloDatos[mostar] = "0";
    $arregloDatos[plantilla] = $arregloDatos[plantillaFiltro];
    $arregloDatos[thisFunction] = 'filtro';
    $this->plantilla->setVariable('filtro', $this->cargaPlantilla($arregloDatos, $this->datos));

    $arregloDatos[thisFunction] = 'filtro';
    $arregloDatos[plantilla] = 'levanteFiltroBusca.html';
    $arregloDatos[mostrar] = 0;
    $htmlFiltro = $this->cargaPlantilla($arregloDatos);
    $this->plantilla->setVariable('filtroFiltro', $htmlFiltro);
    $this->plantilla->show();
  }

  function maestroConsulta($arregloDatos) {
    $this->plantilla->loadTemplateFile(PLANTILLAS . 'levanteMaestroConsulta.html', true, true);
    $this->mantenerDatos($arregloDatos, $this->plantilla);
    $this->plantilla->setVariable('comodin', '');
    // Carga información del Perfil
    $arregloDatos[perfil] = $_SESSION['datos_logueo']['perfil_id'];
    $arregloDatos[ocultaColumna] = $arregloDatos[perfil] == 23 ? 'none' : 'block';
    $arregloDatos[verColumna] = $arregloDatos[perfil] == 23 ? 'none' : 'block';
    $this->plantilla->setVariable('ocultaColumna', $arregloDatos[ocultaColumna]);
    $this->plantilla->setVariable('verColumna', $arregloDatos[verColumna]);

    if(!empty($arregloDatos[filtro])) {
      $arregloDatos[mostrar] = 0;
      $arregloDatos[plantilla] = 'levanteListado.html';
      $arregloDatos[thisFunction] = 'listarLevantes';
      $htmlListado = $this->setFuncion($arregloDatos, $unDatos);
      $this->plantilla->setVariable('htmlListado', $htmlListado);
    } else {
      $arregloDatos[thisFunction] = 'filtro';
      $arregloDatos[plantilla] = 'levanteReporteFiltro.html';
      $arregloDatos[mostrar] = 0;
      // Valida el Perfil para identificar el Tercero
      if($arregloDatos[perfil] == 23) {
        $arregloDatos[soloLectura] = "readonly=''";
        // Carga información del Usuario
        $arregloDatos[usuario] = $_SESSION['datos_logueo']['usuario'];
        $arregloDatos[cliente] = $this->datos->findClientet($arregloDatos[usuario]);
      } else {
        $arregloDatos[soloLectura] = "";
        $arregloDatos[usuario] = "";
        $arregloDatos[cliente] = "";
      }
      $htmlFiltro = $this->cargaPlantilla($arregloDatos);
      $this->plantilla->setVariable('filtroEntrada', $htmlFiltro);
    }
    $this->plantilla->show();
  }

  function filtro($arregloDatos, $unDatos, $plantilla) {
    $unaLista = new Inventario();
    $lista = $unaLista->lista("tipos_remesas");
    $lista = armaSelect($lista, '[seleccione]', NULL);
    $plantilla->setVariable("listaTiposRemesa", $lista);

    $unaLista = new Inventario();
    $lista = $unaLista->lista("inventario_tipos_movimiento", "2,3,4,5,6,7,8,9,10,16,17", 'codigo');

    $lista = armaSelect($lista, '[seleccione]', NULL);
    $plantilla->setVariable("listaTipos", $lista);
  }

  function validaCapturaDeclaraciones($arregloDatos, $unDatos, $plantilla) {
    // Se valida si se captura CIF y cantidad de declaraciones
    $arregloDatos[captura] = "hidden";
    if($unDatos->cant_declaraciones == 0) {
      $arregloDatos[cant_declaraciones] = "";
      $arregloDatos[cant_declaraciones_aux] = "";
      $arregloDatos[valor_aux] = "";
      $arregloDatos[captura] = "text";
    } else {
      $arregloDatos[cant_declaraciones_aux] = $unDatos->cant_declaraciones;
      $arregloDatos[valor_aux] = $unDatos->valor;
    }
    $this->mantenerDatos($arregloDatos, $plantilla);
  }

  //Método que muestra la mercancia para Nacionalizacion
  function getMercancia($arregloDatos, $unDatos, $plantilla) {
    //se valida si se puede elejir mercancia nacional no nacional o ambas
    $this->validaCapturaDeclaraciones($arregloDatos, $unDatos, $plantilla);
    if($unDatos->cantidad_nonac <= 0) {
      $arregloDatos[mostrarBotonGuardar] = 'none';  // No deja Seleccionar mercancia
    }

    $arregloDatos[readonly] = "";
    if($unDatos->cod_referencia == 1) { // Si es Bultos
      $arregloDatos[cantidad_nonac] = $arregloDatos[cant_bultos] - $unDatos->cantidad_naci;
      //saldo por nacionalizar
      // si ya llenó el encabezado se arrastra la cantidad del encabezado
      $arregloDatos[cantidad] = $arraegloDatos[cant_bultos];
      $arregloDatos[valor] = $unDatos->peso_naci + $unDatos->fob_nonac;
      $arregloDatos[readonly] = "readonly=''";
      $arregloDatos[v_aux_nonac] = "";
      $arregloDatos[fob_nonac] = "";
      $arregloDatos[valor] = $unDatos->fob_naci;
    }
    $arregloDatos[cant_declaraciones] = $arregloDatos[cant_declaraciones] + 1;
    // se calcula la cantidad restante cuando son bultos y se cambia la cantidad por los bultos a nacionalizar
    $unaConsulta = new Levante();
    $unaConsulta->getSumaGrupo($arregloDatos);
    $unaConsulta->fetch();
    $arregloDatos[sum_cant_naci] = $unaConsulta->sum_cant_naci;
    if(empty($arregloDatos[sum_cant_naci])) {
      $arregloDatos[sum_cant_naci] = 0;
    }
    $arregloDatos[prefijo]="03".date('Y')."000";
    $this->setValores($arregloDatos, $unDatos, $plantilla);
    $this->mantenerDatos($arregloDatos, $plantilla);
  }

  function getformaCosteo($arregloDatos, $unDatos, $unaPlantilla) {
    $arregloDatos[mostrar] = 0;
    $arregloDatos[plantilla] = 'levanteFormaCosteo.html';
    $arregloDatos[thisFunction] = 'setFormaCosteo';
    $htmlFormaCosteo = $this->setFuncion($arregloDatos, $this->datos);
    return $htmlFormaCosteo;
  }

  function setFormaCosteo($arregloDatos, $unDatos, $unaPlantilla) {
  }

  function getCabezaLevante($arregloDatos, $unDatos, $unaPlantilla) {
    // si es procesamiento parcial interno	
    $unaConsulta = new Levante();
    $arregloDatos[cuenta_grupos] = $unDatos->lev_cuenta_grupo;
    $unaConsulta->cuentaDeclaraciones($arregloDatos);
    // si hay más de un parcial se muestra el número
    $unaPlantilla->setVariable("parcial", $unDatos->lev_cuenta_grupo);
    if($unDatos->lev_cuenta_grupo > 1) {
      //".$unDatos->lev_cuenta_grupo;
      $unaPlantilla->setVariable("parcial", $unDatos->lev_cuenta_grupo);
    }

    if($arregloDatos[parcial] == 1) {
      $unaPlantilla->setVariable("checked", "checked");
    }

    if($unDatos->tip_movimiento <> 7) {  // Alistamiento
      $unaPlantilla->setVariable("mostrarBoton", "none");
    }

    if($unDatos->cierre == 1) {
      $unaPlantilla->setVariable("mostrarBotonCerrar", "none");
      $unaPlantilla->setVariable("mostrarBotonGuardar", "none");
      $unaPlantilla->setVariable("mostrarBoton", "none");
      if($unDatos->tip_movimiento == 7) {
        $unaPlantilla->setVariable("mostrarBoton", "block");
      }
    }
    
    if(empty($unDatos->fmm)) {
      $unaPlantilla->setVariable("fmm", "1");
    }

    $unaPlantilla->setVariable("cant_declaraciones", $arregloDatos[cant_declaraciones]);
    $unaPlantilla->setVariable("peso_declaraciones", $arregloDatos[peso_declaraciones]);
    $unaPlantilla->setVariable("mostrarOtros", "none");
    if($arregloDatos[tipo_movimiento] == 8) { // deja ver el campo de bodega
      if($unDatos->bodega == 0) {
        $unaPlantilla->setVariable("bodega", "");
      }
      $unaPlantilla->setVariable("mostrarOtros", "block");
    }
  }

  function getCabezaProceso($arregloDatos, $unDatos, $unaPlantilla) {
    $arregloDatos[tabla] = 'tipos_embalaje';
    $arregloDatos[labelLista] = 'selectUnidad';
    $this->getLista($arregloDatos, trim($unDatos->unidad), $unaPlantilla);

    if($unDatos->cierre) {
      $arregloDatos[checked] = 'checked';
    }
    $this->mantenerDatos($arregloDatos, $unaPlantilla);
  }

  function getCuerpoRetiro($arregloDatos, $unDatos, $unaPlantilla) {
    // Valida existencia de variables para no eliminar datos
    // Autor: Fredy Salom -> Fecha: 26-Ene-2017
    /***************************************************************/
	//var_dump($unDatos);
	// se consulta las declaraciones de la orden
	$unFormulario= new Levante($arregloDatos);
	$arregloDatos[una_orden]=$unDatos->orden;
	$unFormulario->gerIFM($arregloDatos);
	$unFormulario->fetch();
	$arregloDatos[un_fmm] =$unFormulario->fmm;
    // Totales Nacionales
    if(!isset($this->tot_cant_nac)) $this->tot_cant_nac = 0;
    if(!isset($this->tot_peso_nac)) $this->tot_peso_nac = 0;
    if(!isset($this->tot_fob_nac)) $this->tot_fob_nac = 0;
    /***************************************************************/
    // Totales Extranjeros
    if(!isset($this->tot_cant_nonac)) $this->tot_cant_nonac = 0;
    if(!isset($this->tot_peso_nonac)) $this->tot_peso_nonac = 0;
    if(!isset($this->tot_fob_nonac)) $this->tot_fob_nonac = 0;    
    /***************************************************************/
    $this->getCuerpoLevante($arregloDatos, $unDatos, $unaPlantilla);
  }

  function getCuerpoLevante($arregloDatos, $unDatos, $unaPlantilla) {
    // Contamos el número de levantes
    $unConteo = new Levante();
    $unConteo->ultimoGrupo($arregloDatos);
    $unConteo->cuentaDeclaraciones($arregloDatos);

    $arregloDatos[grupo_label] = ($unDatos->un_grupo >= 1) ? "[$unDatos->un_grupo] " : "";
    $this->setValores($arregloDatos, $unDatos, $unaPlantilla);
    $arregloDatos[tipo_retiro_filtro] = $arregloDatos[tipo_retiro];
    if($arregloDatos[tipo_movimiento] <> 3) {
      $this->sePuedeBorrar($arregloDatos, $unDatos);
    }
    $this->mantenerDatos($arregloDatos, $unaPlantilla);
  }

  function sePuedeBorrar(&$arregloDatos, $unDatos) {
    // Si ya hay retiros no se permite borrar el registro
    $unaConsulta = new Levante();
    $arregloDatos[cod_item] = $unDatos->item;
    $unaConsulta->hayMovimientos($arregloDatos);
    $unaConsulta->fetch();

    $arregloDatos[label] = "";
    if($unaConsulta->tipo_movimiento / 1 > 0) {
      $arregloDatos[label] = "X";
      $arregloDatos[estilo] = "ui-state-highlight";
      $arregloDatos[mensaje] = "ya hay mercancia en tipo de movimiento: $unaConsulta->movimiento ,no se permite eliminar registros para no alterar el inventario. reverse este movimiento para poder modificar el movimiento ";
    } else {
      $arregloDatos[label] = "<a href='#' class='signup borrar_id' title='Quitar Levante {n}' id='$arregloDatos[n]' cursor><img src='integrado/imagenes/borrar.gif' width='15' height='15' border='1'  ></a>";
    }
  }

  function setValores(&$arregloDatos, &$datos, $plantilla) {  
    // Si los valores son negativos significa que  ya se retiró la mercancía por lo tanto se forza a cero
    if($arregloDatos[tipo_ajuste] == 15) {
      $arregloDatos[tipo_retiro_filtro] = 7;
    }
		if($datos->cod_referencia <> 4){ // No se formatean los datos cuando es ajuste pues aplican valores negativos
      $arregloDatos[cantidad_nonac] = number_format(abs($datos->cantidad_nonac), DECIMALES, ".", ""); // se formatea para evitar error de validacion javascript
      $arregloDatos[peso_nonac] = number_format(abs($datos->peso_nonac), DECIMALES, ".", "");
      $arregloDatos[fob_nonac] = number_format(abs($datos->fob_nonac), DECIMALES, ".", "");
		} else {
			$arregloDatos[cantidad_nonac] = number_format($datos->cantidad_nonac, DECIMALES, ".", ""); // se formatea para evitar error de validacion javascript
      $arregloDatos[peso_nonac] = number_format($datos->peso_nonac, DECIMALES, ".", "");
      $arregloDatos[fob_nonac] = number_format($datos->fob_nonac, DECIMALES, ".", "");
		}
    // Variables de pesos cantidad y fob formateadas
		if($datos->cod_referencia <> 4){
			$arregloDatos[peso_naci_f] = number_format(abs($datos->peso_naci), DECIMALES, ".", ",");
			$arregloDatos[peso_nonac_f] = number_format(abs($datos->peso_nonac), DECIMALES, ".", ",");	

			$arregloDatos[cant_naci_f] = number_format(abs($datos->cantidad_naci), DECIMALES, ".", ",");
			$arregloDatos[cant_nonac_f] = number_format(abs($datos->cantidad_nonac), DECIMALES, ".", ",");

			$arregloDatos[fob_naci_f] = number_format(abs($datos->fob_naci), DECIMALES, ".", ",");
		} else {
			$arregloDatos[peso_naci_f] = number_format($datos->peso_naci, DECIMALES, ".", ",");
			$arregloDatos[peso_nonac_f] = number_format($datos->peso_nonac, DECIMALES, ".", ",");	

			$arregloDatos[cant_naci_f] = number_format($datos->cantidad_naci, DECIMALES, ".", ",");
			$arregloDatos[cant_nonac_f] = number_format($datos->cantidad_nonac, DECIMALES, ".", ",");

			$arregloDatos[fob_naci_f] = number_format($datos->fob_naci, DECIMALES, ".", ",");
		}
		
		if($datos->cod_referencia <> 4){
			$this->total_fob = $this->total_fob + $datos->fob_nonac;

			$arregloDatos[total_fob] = number_format(abs($this->total_fob), DECIMALES, ".", ",");
			$arregloDatos[fob_saldo_f] = number_format(abs($fob), DECIMALES, ".", ",");
			$arregloDatos[fob_f] = number_format(abs($datos->fob_nonac), DECIMALES, ".", ",");
			$arregloDatos[cif_f] = number_format(abs($datos->cif), DECIMALES, ".", ",");
		} else {
			$this->total_fob = $this->total_fob + $datos->fob_nonac;

			$arregloDatos[total_fob] = number_format($this->total_fob, DECIMALES, ".", ",");
			$arregloDatos[fob_saldo_f] = number_format($fob, DECIMALES, ".", ",");
			$arregloDatos[fob_f] = number_format($datos->fob_nonac, DECIMALES, ".", ",");
			$arregloDatos[cif_f] = number_format($datos->cif, DECIMALES, ".", ",");
		}
    //totales pesos formateados
    if($datos->cod_referencia <> 4) {
      $this->tot_peso_nac = $this->tot_peso_nac + abs($datos->peso_naci);
      $arregloDatos[tot_peso_nac] = $this->tot_peso_nac;
      $arregloDatos[tot_peso_nacf] = number_format($this->tot_peso_nac, DECIMALES, ".", ",");

			$this->tot_peso_nonac = $this->tot_peso_nonac + abs($datos->peso_nonac);
      $arregloDatos[tot_peso_nonac] = $this->tot_peso_nonac;
      $arregloDatos[tot_peso_nonacf] = number_format($this->tot_peso_nonac, DECIMALES, ".", ",");

      $this->tot_peso_nac1 = $this->tot_peso_nac1 + abs($datos->peso_naci);
      $arregloDatos[tot_peso_nac1] = $this->tot_peso_nac1;
      $arregloDatos[tot_peso_nacf1] = number_format($this->tot_peso_nac1, DECIMALES, ".", ",");
    } else {
			$this->tot_peso_nac = $this->tot_peso_nac + $datos->peso_naci;
      $arregloDatos[tot_peso_nac] = $this->tot_peso_nac;
      $arregloDatos[tot_peso_nacf] = number_format($this->tot_peso_nac, DECIMALES, ".", ",");

      $this->tot_peso_nonac = $this->tot_peso_nonac + $datos->peso_nonac;
      $arregloDatos[tot_peso_nonac] = $this->tot_peso_nonac;
      $arregloDatos[tot_peso_nonacf] = number_format($this->tot_peso_nonac, DECIMALES, ".", ",");

      $this->tot_peso_nac1 = $this->tot_peso_nac1 + $datos->peso_naci;
      $arregloDatos[tot_peso_nac1] = $this->tot_peso_nac1;
      $arregloDatos[tot_peso_nacf1] = number_format($this->tot_peso_nac1, DECIMALES, ".", ",");
		}
    // Totales Fob formateados
		if($datos->cod_referencia <> 4) {
      $this->tot_fob_nac = $this->tot_fob_nac + abs($datos->fob_naci);
      $arregloDatos[tot_fob_nac] = number_format($this->tot_fob_nac, DECIMALES, ".", ",");

			$this->tot_fob_nonac = $this->tot_fob_nonac + abs($datos->fob_nonac);
			$arregloDatos[tot_fob_nonac] = number_format($this->tot_fob_nonac, DECIMALES, ".", ",");
			$arregloDatos[fob_nonac_f] = number_format(abs($datos->fob_nonac), DECIMALES, ".", ",");
			
		} else {
			$this->tot_fob_nac = $this->tot_fob_nac + $datos->fob_naci;
      $arregloDatos[tot_fob_nac] = number_format($this->tot_fob_nac, DECIMALES, ".", ",");

			$this->tot_fob_nonac = $this->tot_fob_nonac + $datos->fob_nonac;
			$arregloDatos[tot_fob_nonac] = number_format($this->tot_fob_nonac, DECIMALES, ".", ",");
			$arregloDatos[fob_nonac_f] = number_format($datos->fob_nonac, DECIMALES, ".", ",");
		}
    //Totales cantidades formateados
		if($datos->cod_referencia <> 4) {
			$this->tot_cant_nac = $this->tot_cant_nac + abs($datos->cantidad_naci);
			$arregloDatos[tot_cant_nac] = number_format($this->tot_cant_nac, DECIMALES, ".", ",");

			$this->tot_cant_nac1 = $this->tot_cant_nac1 + abs($datos->cantidad_naci);
			$arregloDatos[tot_cant_nac1] = number_format($this->tot_cant_nac1, DECIMALES, ".", ",");

			$this->tot_cant_nonac = $this->tot_cant_nonac + abs($datos->cantidad_nonac);
			$arregloDatos[tot_cant_nonac] = number_format($this->tot_cant_nonac, DECIMALES, ",", ",");
			$arregloDatos[t_cant_nonac] = $this->tot_cant_nonac;
		} else {
			$this->tot_cant_nac = $this->tot_cant_nac + $datos->cantidad_naci;
			$arregloDatos[tot_cant_nac] = number_format($this->tot_cant_nac, DECIMALES, ".", ",");
	  
			$this->tot_cant_nac1 = $this->tot_cant_nac1 + $datos->cantidad_naci;
			$arregloDatos[tot_cant_nac1] = number_format($this->tot_cant_nac1, DECIMALES, ".", ",");
		
			$this->tot_cant_nonac = $this->tot_cant_nonac + $datos->cantidad_nonac;
			$arregloDatos[tot_cant_nonac] = number_format($this->tot_cant_nonac, DECIMALES, ",", ",");
			$arregloDatos[t_cant_nonac] = $this->tot_cant_nonac;
		}
    // Aqui se formatean las cifras y se muestra valor absoluto para el caso de retiros
	
		if($datos->cod_referencia <> 4) {
			$arregloDatos[peso_f] = number_format(abs($datos->peso_naci), DECIMALES, ".", ",");
			$arregloDatos[cantidad_f] = number_format(abs($datos->cantidad_naci), DECIMALES, ".", ",");
		
			$this->tot_cif = $this->tot_cif + abs($datos->cif);
			$arregloDatos[tot_cif] = number_format($this->tot_cif, DECIMALES, ".", ",");
	
			$this->tot_fob = $this->tot_fob + abs($datos->fob_nonac);
			$arregloDatos[tot_fob] = number_format($this->tot_fob, DECIMALES, ".", ",");
							
		} else {
			$arregloDatos[peso_f] = number_format($datos->peso_naci, DECIMALES, ".", ",");
			$arregloDatos[cantidad_f] = number_format($datos->cantidad_naci, DECIMALES, ".", ",");
			
			$this->tot_cif = $this->tot_cif + $datos->cif; 
			$arregloDatos[tot_cif] = number_format($this->tot_cif, DECIMALES, ".", ",");
	
			$this->tot_fob = $this->tot_fob + $datos->fob_nonac;
			$arregloDatos[tot_fob] = number_format($this->tot_fob, DECIMALES, ".", ",");
				
		}
	
		if(empty($arregloDatos[tipo_retiro_filtro])) {
      $arregloDatos[tipo_retiro_filtro] = $arregloDatos[tipo_retiro];
    }
    if(empty($arregloDatos[tipo_retiro_filtro])) {
      $arregloDatos[tipo_retiro_filtro] = $arregloDatos[tipo_movimiento];
    }

    switch($arregloDatos[tipo_retiro_filtro]) {
      case 1:
      case 0:
        $arregloDatos[fob_saldo] = "0";
        $arregloDatos[peso_nonac_f] = "";
        $arregloDatos[cant_nonac_f] = "";
        $arregloDatos[fob_nonac_f] = "";
        $arregloDatos[tot_peso_nonac] = "";
        $arregloDatos[tot_cant_nonac] = "";
        $arregloDatos[tot_fob_nonac] = "";
        $arregloDatos[fob_saldo_f] = "";
        $arregloDatos[total_fob] = "";
        $arregloDatos[fob_f] = "";
        $arregloDatos[ext] = "";
        break;
      case 2: // reexportacion
      case 3: // RETIRO
      case 11: // reexportacion
      case 8: // producto para Proceso
      case 9: // producto para Ensamble  
      case 7: // producto para Ensamble 
      case 13:
        $arregloDatos[sn] = " | [EXT] ";
        $arregloDatos[snt] = " | [EXT] ";
        $arregloDatos[sn_aux] = " | [EXT] ";
        $arregloDatos[type_nonac] = "text";
        $arregloDatos[cantidad_nonaci_aux] = $datos->cantidad_nonaci;
        $arregloDatos[peso_nonaci_aux] = $datos->peso_nonaci;
        $arregloDatos[fob_nonaci_aux] = $datos->fob_nonaci;
        $arregloDatos[ext] = "/FOB";
        break;
		case 16: // garantiza valores positivos en mercancia acondicionada
		case 17: // garantiza valores positivos en rechazados
			  $arregloDatos[cantidad_naci] = abs($datos->cantidad_naci);
        	$arregloDatos[peso_naci] = abs($datos->peso_naci);
        	$arregloDatos[cif] = abs($datos->cif);
		break;
		
      default:
        break;
    }
	
		// Garantiza mostrar valores y etiquetas de Extranjero cuando aplique 23/11/2016, se agrego  or $this->tot_peso_nac <> 0 09122017
		if(($this->tot_peso_nonac <> 0 or  $this->tot_cant_nonac <> 0 or $this->tot_peso_nac <> 0) && $arregloDatos[tipo_retiro_filtro] <> 1) {
			
			$arregloDatos[sn] = " | [EXT] ";
			$arregloDatos[snt] = " | [EXT] ";
			$arregloDatos[sn_aux] = " | [EXT] ";
			$arregloDatos[type_nonac] = "text";
			$arregloDatos[cantidad_nonaci_aux] = $datos->cantidad_nonaci;
			$arregloDatos[peso_nonaci_aux] = $datos->peso_nonaci;
			$arregloDatos[fob_nonaci_aux] = $datos->fob_nonaci;
			$arregloDatos[ext] = "/FOB";
			$arregloDatos[mostrarCaptura]='none';
			// se garantizan  valores positivos 04/01/2018
		} else {
			
			$arregloDatos[tot_cant_nonac] = "";
			$arregloDatos[tot_peso_nonac] = "";
			$arregloDatos[total_fob] = "";
			$arregloDatos[tot_cant_nonacf] = "";
			$arregloDatos[tot_peso_nonacf] = "";
			$arregloDatos[tot_fob] = "";
			$arregloDatos[sn] = "";
			$arregloDatos[snt] = "";
			$arregloDatos[mostrarCaptura]='block';
		}
		// se garantizan valores positivos
	
		
		
  }

  function listaInventario($arregloDatos, $datos, $plantilla) {
    // Se trae el grupo
    $this->setValores($arregloDatos, $datos, $plantilla);
    $this->mantenerDatos($arregloDatos, $plantilla);
  }

  function getInventario($arregloDatos, $datos, $plantilla) {
    $this->setValores($arregloDatos, $datos, $plantilla);
    $this->mantenerDatos($arregloDatos, $plantilla);
  }

  function getParaCosteo($arregloDatos, $datos, $plantilla) {
    $this->setValores($arregloDatos, $datos, $plantilla);
    $this->mantenerDatos($arregloDatos, $plantilla);
  }

  function getParaAdicionales($arregloDatos, $datos, $plantilla) {
    $unaLista = new Levante();
    $lista = $unaLista->getTipos();
    $lista = armaSelect($lista, '[seleccione]', 24);
    $plantilla->setVariable('listaTipos', $lista);

    $arregloDatos[tabla] = 'embalajes';
    $arregloDatos[labelLista] = 'listaEmbalajes';
    $this->getLista($arregloDatos, NULL, $plantilla);
    //$this->setValores(&$arregloDatos,$datos,$plantilla);
    $this->mantenerDatos($arregloDatos, $plantilla);
  }

  function getParaProceso($arregloDatos, $datos, $plantilla) {
    //$this->getMercancia($arregloDatos,$datos,$plantilla);// cuando es nacionalizacion
    $this->setValores($arregloDatos, $datos, $plantilla);
    $this->mantenerDatos($arregloDatos, $plantilla);
    if($bloqueado == "Si") {
      $plantilla->setVariable('bloqueado', 1);
      $plantilla->setVariable('imagen', 'bloqueo.gif');
    } else {
      $plantilla->setVariable('bloqueado', 0);
      $plantilla->setVariable('imagen', 'checkin.gif');
    }
	
	 
  }
  
 
  function getInvParaRetiro($arregloDatos, $datos, $plantilla) {
    $unaConsulta = new Levante();
    $otraConsulta = new Levante();
    // Se averigua si el DO está bloqueado
    $arregloDatos[orden_bloqueo] = $datos->orden;
    $arregloDatos[id_bloqueo] = $unaConsulta->getIdBloqueo($arregloDatos);
    $bloqueado = $otraConsulta->getEstadoBloqueo($arregloDatos);

    if($bloqueado == "Si") {
      $plantilla->setVariable('bloqueado', 1);
      $plantilla->setVariable('imagen', 'bloqueo.gif');
    } else {
      $plantilla->setVariable('bloqueado', 0);
      $plantilla->setVariable('imagen', 'checkin.gif');
    }
	
	if($arregloDatos[tipo_retiro]<>1){
		$plantilla->setVariable('verRetiroRapido', 'none');
	}
	
	
	
    $arregloDatos[type_nonac] = "hidden";
    $arregloDatos[cantidad_nonaci_aux] = "0";
    $arregloDatos[peso_nonaci_aux] = "0";
    $arregloDatos[fob_nonaci_aux] = "0";
    //aqui se decide si se deja editar o no la mercancia sin nacionalizar;
    $this->setValores($arregloDatos, $datos, $plantilla);
    /*
      Si es producto terminado  se muestra el detalle para armar la matriz de integración
    */
    switch($arregloDatos[accion_aux]) {
      case 'getItemRetiro':
        break;
      case '':
        break;
      default:
        $consultaDo = new Levante();
        $consultaDo->thereIs($arregloDatos);

        if($arregloDatos[marca_matriz] == '11') {
          $arregloDatos[una_matriz] = 'Matriz';
        }
		 
        if($consultaDo->N > 0 && $arregloDatos[una_matriz] == 'Matriz') { // y tipo de operacion es matriz
          if($arregloDatos[sendmetodo] == "addItemRetiro") {
            $arregloDatos[mostrar] = 0;
            $arregloDatos[plantilla] = 'levanteListadoMercanciaProceso.html';
            $arregloDatos[thisFunction] = 'getCuerpoMercanciaProceso';
            $arregloDatos[detalleProducto] = $this->setFuncion($arregloDatos, $unDatos);
          }
        }
    }
    $this->mantenerDatos($arregloDatos, $plantilla);
  }

  function getCuerpoMercanciaProceso($arregloDatos, $unDatos, $unaPlantilla) {
    $this->setValores($arregloDatos, $unDatos, $unaPlantilla);
    $arregloDatos[n_aux] = $arregloDatos[n] - 1;
    $this->mantenerDatos($arregloDatos, $unaPlantilla);
  }

  function listarLevantes($arregloDatos, &$datos, &$plantilla) {
    //Configura la fecha para TimeStamp con formato AAAA-mm-dd HH:MM
    $arregloDatos[fecha] = date('Y-m-d h:i',strtotime($datos->fecha));
    $plantilla->setVariable('img_editar', 'layer--pencil.png');
    $this->mantenerDatos($arregloDatos, $plantilla);
  }

  function getToolbar($arregloDatos, &$datos, &$plantilla) {
    $this->mantenerDatos($arregloDatos, $plantilla);
  }

  function traeLevante($arregloDatos, &$datos, &$plantilla) {
    $this->mantenerDatos($arregloDatos, $plantilla);
  }

  //Pinta el cuerpo del retiro
  function getRetiro($arregloDatos, &$datos, &$plantilla) {
    $arregloDatos[mostrar] = 0;
    $arregloDatos[plantilla] = 'levanteRemesaCuerpo.html';
    $arregloDatos[thisFunction] = 'getCuerpoRetiro';
    $arregloDatos[cuerpoRetiro] = $this->setFuncion($arregloDatos, $unDatos);
    $this->mantenerDatos($arregloDatos, $plantilla);
  }

  //Pinta el cuerpo del retiro
  function matriz($arregloDatos, &$datos, &$plantilla) { 
    $arregloDatos[mostrar] = 0;
    $arregloDatos[plantilla] = 'levanteMatrizCuerpo.html';
    $arregloDatos[thisFunction] = 'getCuerpoRetiro';
    $arregloDatos[cuerpoRetiro] = $this->setFuncion($arregloDatos, $unDatos);
    $this->mantenerDatos($arregloDatos, $plantilla);
  }

  function matrizRetiroCabeza($arregloDatos, &$datos, &$plantilla) {
    // La siguiente consulta, devuelve el inventario del retiro del producto terminado
    $unaConsulta= new Levante();
    $unaConsulta->getDatosRetiro($arregloDatos);
    $unaConsulta->fetch();
    $arregloDatos[fecha_retiro] = $unaConsulta->fecha;
    $arregloDatos[orden_ing] = $unaConsulta->orden;
    $arregloDatos[cant_nal] =   number_format($unaConsulta->cantidad_naci, DECIMALES, ".", ",");
    $arregloDatos[refe_retito] = $unaConsulta->refe_retito;
    $arregloDatos[peso_nal] = number_format($unaConsulta->peso_naci, DECIMALES, ".", ",");
    $arregloDatos[cif_ret] =  number_format($unaConsulta->cif, DECIMALES, ".", ",");
    
    // Faltan los valores extranjeros 
    if($unaConsulta->peso_nonac > 0 or $unaConsulta->cantidad_nonac > 0) {
      $arregloDatos[l_ext] = " [EXT] ";
      $arregloDatos[ext_peso] = number_format($unaConsulta->peso_nonac, DECIMALES, ".", ",");
      $arregloDatos[ext_cantidad] = number_format($unaConsulta->cantidad_nonac, DECIMALES, ".", ",");
      $arregloDatos[fob_ret] = number_format($unaConsulta->fob_nonac, DECIMALES, ".", ",");
    }
    // Se pintan los ajustes y los totales después de ajustes
    $unAjuste = new Levante();
    $unAjuste->getDatosAjustes($arregloDatos);
    $unAjuste->fetch();

    $arregloDatos[cant_nal_a] = number_format($unAjuste->cantidad_naci, DECIMALES, ".", ",");
    $arregloDatos[peso_nal_a] = number_format($unAjuste->peso_naci, DECIMALES, ".", ",");
    $arregloDatos[cif_ret_a] = number_format($unAjuste->cif, DECIMALES, ".", ",");
    $arregloDatos[refe_ajuste] = $unAjuste->refe_retiro;

    if($unAjuste->peso_nonac <> 0 or $unAjuste->cantidad_nonac <> 0 or  $unaConsulta->cantidad_nonac <> 0 or  $unaConsulta->peso_nonac <> 0  or  $unaConsulta->fob_nonac <> 0) {
     $arregloDatos[l_ext_a] = " [EXT] ";
		 $arregloDatos[ext_peso_a] = number_format($unAjuste->peso_nonac, DECIMALES, ".", ",");
		 $arregloDatos[ext_cantidad_a] = number_format($unAjuste->cantidad_nonac, DECIMALES, ".", ",");
		 $arregloDatos[fob_ret_a] = number_format($unAjuste->fob_nonac, DECIMALES, ".", ",");
		
		 $arregloDatos[total_cantidad_ext] = number_format($unaConsulta->cantidad_nonac+$unAjuste->cantidad_nonac, DECIMALES, ".", ",");
		 $arregloDatos[total_peso_ext] = number_format($unaConsulta->peso_nonac+$unAjuste->peso_nonac, DECIMALES, ".", ",");
		 $arregloDatos[total_fob_ext] = number_format($unaConsulta->fob_nonac+$unAjuste->fob_nonac, DECIMALES, ".", ",");
    }
	  $arregloDatos[total_cantidad_nal] = number_format(abs($unaConsulta->cantidad_naci)+$unAjuste->cantidad_naci, DECIMALES, ".", ",");
	  $arregloDatos[total_peso_nal] = number_format($unaConsulta->peso_naci+$unAjuste->peso_naci, DECIMALES, ".", ",");
	  $arregloDatos[total_cif_a] = number_format($unaConsulta->cif+$unAjuste->cif, DECIMALES, ".", ",");
    $arregloDatos[valor_aux] = $datos->valor;
    $arregloDatos[valor] = number_format(abs($datos->valor), DECIMALES, ".", ",");
    $arregloDatos[mostrar] = 0;
    $arregloDatos[plantilla] = 'levanteMatrizCuerpoRetiro.html';
    $arregloDatos[thisFunction] = 'matrizRetiroCuerpo';

    $arregloDatos[detalle] = $this->setFuncion($arregloDatos, $unDatos);
    $this->mantenerDatos($arregloDatos, $plantilla);
  }

  function matrizRetiroCuerpo($arregloDatos, &$datos, &$plantilla) {
    // Se procede a pintar los valores de mercancia nacional y demas
    $this->setValores($arregloDatos, $datos, $plantilla);
    $unNacional = new Levante();
    $unNacional->getNacional($arregloDatos);
    //Nacional
    $unNacional->fetch();
    $peso_nacional = $unNacional->peso;
    $cantidad_nacional = $unNacional->cantidad;
    $arregloDatos[tot_peso_nal] = number_format(abs($unNacional->peso ), DECIMALES, ".", ",");
    $arregloDatos[tot_cant_nal] = number_format(abs($unNacional->cantidad ), DECIMALES, ".", ",");

    //Desperdicios --->DESDE AQUI
    $unNacional->peso = 0;
    $unNacional->cantidad = 0;
    $unNacional->getDesperdicios($arregloDatos);
    $unNacional->fetch();
    $peso_desperdicios = $unNacional->peso;
    $cantidad_desperdicios = $unNacional->cant;
    $arregloDatos[pesod_f] = number_format(abs($unNacional->peso_naci ), DECIMALES, ".", ",");
    $arregloDatos[cantidadd_f] = number_format(abs($unNacional->cantidad_naci ), DECIMALES, ".", ",");
    if($unNacional->peso_nonac <> 0) {
      $arregloDatos[ld_ext] = " [EXT] ";
      $arregloDatos[des_cantidad_ext] = number_format($unNacional->cantidad_nonac, DECIMALES, ".", ",");
      $arregloDatos[des_peso_ext] = number_format($unNacional->peso_nonac, DECIMALES, ".", ",");
    }
    //Nacionalizado
    $arregloDatos[t_peso_nac] = $this->tot_peso_nac-$peso_nacional; // se quita el peso  nacional
    $arregloDatos[t_peso_nac] = number_format(abs($arregloDatos[t_peso_nac] ), DECIMALES, ".", ",");
	
    $arregloDatos[t_cant_nac] = $this->tot_cant_nac-$cantidad_nacional; // se quita el peso  nacional
    $arregloDatos[t_cant_nac] = number_format(abs($arregloDatos[t_cant_nac] ), DECIMALES, ".", ",");
	
    //Ajustes
    $unAjuste = new Levante();
    $unAjuste->getAjustes($arregloDatos);
    $unAjuste->fetch();
    $arregloDatos[ajustes_peso] = number_format($unAjuste->peso_naci, DECIMALES, ".", ","); 
    $arregloDatos[ajustes_cantidad] = number_format($unAjuste->cantidad_naci, DECIMALES, ".", ",");
    if($unAjuste->peso_nonac <> 0) {
      $arregloDatos[l_ext] = " [EXT] ";
      $arregloDatos[ajustes_cantidad_ext] = number_format($unAjuste->cantidad_nonac, DECIMALES, ".", ",");
      $arregloDatos[ajustes_peso_ext] = number_format($unAjuste->peso_nonac, DECIMALES, ".", ",");
    }	

    // Desperdicios
    $this->mantenerDatos($arregloDatos, $plantilla);
  }

  function getMatrizCambio($arregloDatos, &$datos, &$plantilla) {
    $this->mantenerDatos($arregloDatos, $plantilla);
  }

  function getTitulo(&$arregloDatos) {
  //var_dump($arregloDatos);
    if(empty($arregloDatos[doc_filtro])) {
      $arregloDatos[doc_filtro] = $arregloDatos[documento_filtro];
    }
	$arregloDatos[titulo] = "  $arregloDatos[tipo_retiro_label]: ";
	  
	  
	  /*
    	switch($arregloDatos[tipo_movimiento]) {
    
	  case 2:
        $arregloDatos[titulo] = " LEVANTE: ";
        break;
      case 3:
        $arregloDatos[titulo] = " RETIRO $arregloDatos[tipo_retiro_label]: ";
        break;
      case 7:
        $arregloDatos[titulo] = " PEDIDO $arregloDatos[tipo_retiro_label]: ";
        break;
      case 8:
        $arregloDatos[titulo] = " PROCESO $arregloDatos[tipo_retiro_label]: ";
        break;
      case 9:
        $arregloDatos[titulo] = " ENSAMBLE $arregloDatos[tipo_retiro_label]: ";
        break;
      case 13:
        $arregloDatos[titulo] = " ENDOSO: $arregloDatos[tipo_retiro_label]";
        break;
    }
    switch($arregloDatos[tipo_retiro]) {
      case 2:
        $arregloDatos[titulo] .= " REEX ";
        break;
    }*/
    if(!empty($arregloDatos[por_cuenta_filtro])) {
      $unLevante = new Levante();
      $unLevante->getCliente($arregloDatos);
      $unLevante->fetch();
      $arregloDatos[titulo] .= "[" . $unLevante->numero_documento . "] " . $unLevante->razon_social;
    }
    if(!empty($arregloDatos[doc_filtro])) {
      $arregloDatos[titulo] .= ", DOCUMENTO: $arregloDatos[doc_filtro]";
    }
    if(!empty($arregloDatos[orden_filtro])) {
      $arregloDatos[titulo] .= ", ORDEN: $arregloDatos[orden_filtro]";
    }
    if(!empty($arregloDatos[arribo_filtro])) {
      $arregloDatos[titulo] .= ", ARRIBO: $arregloDatos[arribo_filtro]";
    }
  }
  
  function getDatosInventario($arregloDatos) {
    echo "Generación de Datos ....";
    
  }

	function mostrarMensaje($arregloDatos) {
	
   
		if($arregloDatos[info]) {
			$msg = "Se ha enviado un correo a la cuenta: ".$arregloDatos[email]." satisfactoriamente.";
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
				$msg = "Error al intentar registrar el correo a la cuenta: ".$arregloDatos[email];
				echo "<script type='text/javascript'>alert('$msg');</script>";			
			}			
		}
	}
}
?>