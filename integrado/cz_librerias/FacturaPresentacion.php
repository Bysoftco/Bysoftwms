<?php
require_once("HTML/Template/IT.php");
require_once("Funciones.php");
require_once("FacturaDatos.php");
require_once("montoEscrito.php");

class FacturaPresentacion {
  var $datos;
  var $plantilla;
  var $trm;

  function FacturaPresentacion(&$datos) {
    $this->datos =& $datos;
    $this->plantilla = new HTML_Template_IT();
  }

  //Función que coloca los datos que vienen de la BD
  function setDatos($arregloDatos,&$datos,&$plantilla) {
    foreach ($datos as $key => $value) {
      $plantilla->setVariable($key , $value);
    }
  }

  function mantenerDatos($arregloCampos,&$plantilla) {
    $plantilla =& $plantilla;
    if(is_array($arregloCampos)) {
      foreach ($arregloCampos as $key => $value) {
        $plantilla->setVariable($key , $value);
      }
    }
  }

  //Función que Arma una lista
  function getLista($arregloDatos,$seleccion,&$plantilla) {
    $unaLista = new Factura();
    $lista	= $unaLista->lista($arregloDatos[tabla],$arregloDatos[condicion],$arregloDatos[campoCondicion]);

    $lista	= armaSelect($lista,'[seleccione]',$seleccion);
    $plantilla->setVariable($arregloDatos[labelLista], $lista);
  }

  // Arma cada Formulario o función en pantalla
	function setFuncion($arregloDatos,&$unDatos) {
    $unDatos = new Factura();

		if(!empty($arregloDatos[setCharset])) {
      header( 'Content-type: text/html; charset=iso-8859-1' );
		}	
		
    $r = $unDatos->$arregloDatos[thisFunction]($arregloDatos);
    
    $unaPlantilla = new HTML_Template_IT();
		$unaPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla],true,true);
    if(!empty($arregloDatos[thisFunctionAux])) { $this->$arregloDatos[thisFunctionAux]($arregloDatos,$unaPlantilla); }
    $unaPlantilla->setVariable('comodin'	,' ');
		if(!empty($unDatos->mensaje_error)) {
      $unaPlantilla->setVariable('mensaje',$unDatos->mensaje_error);
      $unaPlantilla->setVariable('estilo'	,$unDatos->mensaje_error);
		}

    $this->mantenerDatos($arregloDatos,$unaPlantilla);
		$arregloDatos[n] = 0;
		while ($unDatos->fetch()) {
		  $odd = ($arregloDatos[n] % 2) ? 'odd' : '';  
      $arregloDatos[n] = $arregloDatos[n] + 1;
      $unaPlantilla->setCurrentBlock('ROW');
      $this->setDatos($arregloDatos,$unDatos,$unaPlantilla);

      $this->$arregloDatos[thisFunction]($arregloDatos,$unDatos,$unaPlantilla);
      $unaPlantilla->setVariable('n' ,$arregloDatos[n]);
      $unaPlantilla->setVariable('odd'			, $odd);
      $unaPlantilla->parseCurrentBlock();
    }

    if($unDatos->N == 0) {
      $unaPlantilla->setVariable('mensaje', 'No hay registros para listar, '.$unDatos->mensaje_error);
		  $unaPlantilla->setVariable('estilo', 'ui-state-highlight');
      $unaPlantilla->setVariable('mostrarCuerpo' ,'none');
		}
		$unaPlantilla->setVariable('num_registros',$unDatos->N);

		if($arregloDatos[mostrar]) {
      $unaPlantilla->show();
		} else {
      $unaPlantilla->setVariable('cuenta',$this->cuenta);
      return $unaPlantilla->get();
		}
  }
 
  function cargaPlantilla($arregloDatos) {
    $unAplicaciones = new Levante();
    $formularioPlantilla = new HTML_Template_IT();

    $formularioPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla],false,false);
    $formularioPlantilla->setVariable('comodin'	,' ');
    $this->mantenerDatos($arregloDatos, $formularioPlantilla);

    $this->$arregloDatos[thisFunction]($arregloDatos,$this->datos,$formularioPlantilla);
    if($arregloDatos[mostrar]) {
      $formularioPlantilla->show();
    } else {
      return $formularioPlantilla->get();
    }
  }

  function maestro($arregloDatos) {
    $this->plantilla->loadTemplateFile(PLANTILLAS .'facturaMaestro.html',true,true);
    $this->plantilla->setVariable('comodin'	,' ');
    $this->plantilla->setVariable('abre_ventana',1);
    $this->mantenerDatos($arregloDatos,$this->plantilla);    

    $arregloDatos[mostar] = "0";
    $arregloDatos[plantilla] = 'facturaToolbar.html';
    $arregloDatos[thisFunction] = 'getToolbar';  
    $this->plantilla->setVariable('toolbar',$this->setFuncion($arregloDatos,$this->datos));

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
    $arregloDatos[thisFunction] = 'filtro';  
    $arregloDatos[plantilla] = 'facturaFiltroCrear.html';
    $arregloDatos[mostrar] = 0;
    $htmlFiltro = $this->cargaPlantilla($arregloDatos);
    $this->plantilla->setVariable('filtroFiltro',$htmlFiltro);
    $this->plantilla->show();
  }

  function preFactura($arregloDatos) {
    $this->plantilla->loadTemplateFile(PLANTILLAS .'facturaGenerarFactura.html',true,true);
    $this->plantilla->setVariable('comodin'	,' ');
    $this->mantenerDatos($arregloDatos,$this->plantilla);    

    $arregloDatos[mostar] = "0";
    $arregloDatos[plantilla] = 'facturaToolbar.html';
    $arregloDatos[thisFunction] = 'getToolbar';  
    $this->plantilla->setVariable('toolbar',$this->setFuncion($arregloDatos,$this->datos));
            
    $arregloDatos[mostar] = "0";
    $arregloDatos[plantilla] = 'facturaFormCabeza.html';
    $arregloDatos[thisFunction] = 'getCabeza';  
    $this->plantilla->setVariable('unaFactura',$this->setFuncion($arregloDatos,$this->datos));

    $this->plantilla->show();		
  }

  // Función Principal para las consultas
  function maestroConsulta($arregloDatos) {
    $this->plantilla->loadTemplateFile(PLANTILLAS .'facturaMaestroConsulta.html',true,true);

    $this->mantenerDatos($arregloDatos,$this->plantilla);
    $this->plantilla->setVariable('comodin','');
    
    // Carga información del Perfil
    $arregloDatos[perfil] = $_SESSION['datos_logueo']['perfil_id'];
    if(!empty($arregloDatos[filtro])) {
      $arregloDatos[mostrar] = 0;
      $arregloDatos[plantilla] = 'facturaListado.html';
      $arregloDatos[thisFunction]	= 'listarFacturas';
      // Valida el Perfil para determinar la visualización de la Barra de Herramientas 
      $arregloDatos[verToolbar] = $arregloDatos[perfil] == 23 ? 'none' : 'block';
      $this->plantilla->setVariable('verToolbar', $arregloDatos[verToolbar]);
      $htmlListado = $this->setFuncion($arregloDatos,$unDatos);
      $this->plantilla->setVariable('htmlListado',$htmlListado);
    } else {
      $arregloDatos[thisFunction]	= 'filtroConsulta';  
      $arregloDatos[plantilla] = 'facturaReporteFiltro.html';
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
      $this->plantilla->setVariable('filtroEntradaConsulta',$htmlFiltro);
    }
    $this->plantilla->show();
  }

  function listarFacturas($arregloDatos,&$datos,&$plantilla) {
    $plantilla->setVariable('img_editar','layer--pencil.png');
    $arregloDatos[neto_f] = number_format(round($datos->neto),0,',','.');
    $arregloDatos[iva_f] = number_format(round($datos->iva),0,',','.');
    $arregloDatos[total_f] = number_format(round($datos->total),0,',','.');
    $arregloDatos[subtotal_f] = number_format(round($datos->subtotal),0,',','.');
    $this->mantenerDatos($arregloDatos,$plantilla);
	}

  function getParaFacturar($arregloDatos,&$datos,&$plantilla) {
    if(empty($datos->factura)) {
      $plantilla->setVariable('img_editar','layer--pencil.png');
    } else {
      $plantilla->setVariable('img_editar','ico_facturado.gif');
    }
    $plantilla->setVariable('centro',$centro);

    $subcentro = substr($datos->do_asignado,4,3);
    $plantilla->setVariable('subcentro',$subcentro);

    $centro = substr($datos->do_asignado,0,4);
    $plantilla->setVariable('centro',$centro);

    if(!empty($datos->reasignado)) {
      $plantilla->setVariable('r','[R]');
    }
  }

  function getParaFacturarRemesa($arregloDatos,&$datos,&$plantilla) {
    if(empty($datos->factura)) {
      $plantilla->setVariable('img_editar','layer--pencil.png');
    } else {
      $plantilla->setVariable('img_editar','ico_facturado.gif');
    }
    $plantilla->setVariable('centro',$centro);

    $subcentro = substr($datos->do_asignado,4,3);
    $plantilla->setVariable('subcentro',$subcentro);

    $centro = substr($datos->do_asignado,0,4);
    $plantilla->setVariable('centro',$centro);

    if(!empty($datos->reasignado)) {
      $plantilla->setVariable('r','[R]');
    }
  }

  function filtroConsulta($arregloDatos,&$datos,&$plantilla) { }

  function filtro($arregloDatos) { }
  
  // Método que retorna la Barra de Herramientas
  function getToolbar($arregloDatos) { }

  function getConceptos($arregloDatos,$unDatos,$unaPlantilla) {
    //Cargar lista de  conceptos_tarifas
    $arregloDatos[tabla] = 'conceptos_tarifas';
    $arregloDatos[labelLista]	= 'selectTipos';
    $this->getLista($arregloDatos,$unDatos->tipo,$unaPlantilla);
    if($unDatos->tipo == 0) { $arregloDatos[tipo] = ""; }//Para que se dispare la validación
    $this->valoresFormateados($arregloDatos,$unDatos);
	
    $arregloDatos[nombre_usuario] = $_SESSION['nombre_usuario'];
    $this->mantenerDatos($arregloDatos,$unaPlantilla);
  }

  // Función que construye la plantilla con los anticipos
  function getAnticipos(&$arregloDatos) {
    $arregloDatos[plantilla] = 'facturaAnticipoListado.html';
    if($arregloDatos[metodo] == "Preliquidacion" OR $arregloDatos[metodo] == "getPreFactura") {
      $arregloDatos[plantilla] = 'facturaAnticipo.html';
    }    

    $arregloDatos[mostrar] = 0;
    $arregloDatos[thisFunction] = 'listarAnticipos';
    $arregloDatos[anticipos] = $this->setFuncion($arregloDatos,$unDatos);
  }

  function listarAnticipos($arregloDatos,$unDatos,$unaPlantilla) { }

  function getCabeza($arregloDatos,$unDatos,$unaPlantilla) 
  {
  	$unaConsulta = new Factura();
 	$arregloDatos[proxima_factura]=$unaConsulta->proximaFactura($arregloDatos);
  
    if(empty($arregloDatos[posy])) {
      $arregloDatos[posy] = 40;
    } else {
      $arregloDatos[posy] = 0;      
    }
    $arregloDatos[mostrarBarra] = "none";
    $monto = new EnLetras();

    $arregloDatos[tabla] = 'do_bodegas';
    $arregloDatos[labelLista]	= 'selectUbicacion';
    $this->getLista($arregloDatos,$unDatos->ubicacion,$unaPlantilla);

    $arregloDatos[tabla] = 'vendedores';
    $arregloDatos[labelLista]	= 'selectVendedor';
    $this->getLista($arregloDatos,$unDatos->vendedor,$unaPlantilla);  
    $arregloDatos[netof] = number_format($unDatos->neto,DECIMALES,",",".");

    $this->valorLetras($arregloDatos,$unDatos);

    $this->valoresFormateados($arregloDatos,$unDatos);
    $this->getMarcaCheck($arregloDatos,$unDatos);

    $arregloDatos[mostrar] = 0;
    $arregloDatos[plantilla] = $arregloDatos[plantilla_conceptos];
    $arregloDatos[thisFunction] = 'getConceptos';
    $arregloDatos[conceptos] = $this->setFuncion($arregloDatos,$unDatos);

    // se consulta el tope minimo
    $getTope = new Factura();
    $getTope->getTope($arregloDatos);
    $getTope->fetch();
    $arregloDatos[tope_minimo_retencion] = $getTope->tope_minimo;

    // Se consultan los anticipos
    $this->getAnticipos($arregloDatos);
    $this->mantenerDatos($arregloDatos,$unaPlantilla);
  }
  
  function getFechaFormateada(&$arregloDatos,$unDatos) {
    $arregloDatos[a_fac] = substr($unDatos->fecha_factura,0,4);
    $arregloDatos[m_fac] = substr($unDatos->fecha_factura,5,2);
    $arregloDatos[d_fac] = substr($unDatos->fecha_factura,8,2);
    $arregloDatos[a_en] = substr($unDatos->fecha_entrada,0,4);
    $arregloDatos[m_en] = substr($unDatos->fecha_entrada,5,2);
    $arregloDatos[d_en] = substr($unDatos->fecha_entrada,8,2);
    $arregloDatos[a_sa] = substr($unDatos->fecha_salida,0,4);
    $arregloDatos[m_sa] = substr($unDatos->fecha_salida,5,2);
    $arregloDatos[d_sa] = substr($unDatos->fecha_salida,8,2);
  }

  function valoresFormateados(&$arregloDatos,$unDatos) {
    $unaConsulta = new Factura();
    
    $arregloDatos[basef] = number_format(round($unDatos->base),0,',','.');
    $arregloDatos[cantidadf] = number_format(round($unDatos->cantidad),0,',','.');
    $arregloDatos[valor_unitariof] = number_format(round($unDatos->valor_unitario),0,',','.');
    $arregloDatos[valorf] = number_format(round($unDatos->valor),0,',','.');
    $arregloDatos[ivaf] = number_format(round($unDatos->iva),0,',','.');
    $arregloDatos[subtotalf] = number_format(round($unDatos->subtotal),0,',','.');
    $arregloDatos[rte_fuentef] = number_format(round($unDatos->rte_fuente),0,',','.');
    $arregloDatos[rte_ivaf] = number_format(round($unDatos->rte_iva),0,',','.');
    $arregloDatos[rte_icaf] = number_format(round($unDatos->rte_ica),0,',','.');
    $arregloDatos[totalf] = number_format(round($unDatos->total),0,',','.');
    $arregloDatos[rte_creef] = number_format(round($unDatos->rte_cree),0,',','.');
    
    // Suma el valor adicional de los anticipos
    $otraConsulta = new Factura();
    
    $otros_anticipos = $otraConsulta->totalAnticipos($arregloDatos);
    $unDatos->valor_anticipo = $unDatos->valor_anticipo + $otros_anticipos;
    $arregloDatos[anticipof] = number_format(round($unDatos->valor_anticipo),0,',','.');
    $arregloDatos[netof] = number_format(round($unDatos->neto),0,',','.');
	
	// SI LA FACTURA ESTA EN DOLARES
	
	if($unDatos->trm > 0 or $this->trm > 0){
		    if(empty($unDatos->trm)){
				$unDatos->trm=$this->trm ;
			}	
				$this->trm=$unDatos->trm;
			//var_dump($unDatos);
			 $arregloDatos[subtotalf]=number_format ($unDatos->subtotal/$unDatos->trm,2,',','.');
			 $arregloDatos[ivaf]=number_format ($unDatos->iva/$unDatos->trm,2,',','.');
			 $arregloDatos[anticipof]=number_format ($unDatos->valor_anticipo/$unDatos->trm,2,',','.');
			 $arregloDatos[totalf] = number_format ($unDatos->total/$unDatos->trm,2,',','.');
			 $arregloDatos[rte_fuentef] = number_format ($unDatos->rte_fuente/$unDatos->trm,2,',','.');
			 $arregloDatos[rte_icaf] = number_format ($unDatos->rte_ica/$unDatos->trm,2,',','.');
			 $arregloDatos[rte_ivaf] = number_format ($unDatos->rte_iva/$unDatos->trm,2,',','.');
			 $arregloDatos[totalf] = number_format($unDatos->total/$unDatos->trm,2,',','.');
			 $arregloDatos[netof] = number_format($unDatos->neto/$unDatos->trm,2,',','.');
			 $arregloDatos[valorf] = number_format($unDatos->valor/$unDatos->trm,2,',','.');
			 
			 $arregloDatos[valor_unitariof] = number_format($unDatos->valor_unitario/$unDatos->trm,0,',','.');
		}
	
  }

  function getMarcaCheck(&$arregloDatos,$unDatos) {
    if($unDatos->efectivo >= 1) {
      $arregloDatos[ck_efectivo] = "X";
      $arregloDatos[checkedEfectivo] = "checked";
    }
    if($unDatos->cheque >= 1) {
      $arregloDatos[ck_cheque] = "X";
      $arregloDatos[checkedCheque] = "checked";
    }
    if($unDatos->credito >= 1) {
      $arregloDatos[ck_credito] = "X";
      $arregloDatos[checkedCredito] = "checked";
    }
    if($unDatos->anticipo >= 1) {
      $arregloDatos[ck_anticipo] = "X";
      $arregloDatos[checkedAnticipo] = "checked";
    }
            
    // Régimen
    switch($arregloDatos[regimen]) {
      case 1:
        $arregloDatos[regimen_f] = "Comun";
        break;
      case 2:
        break;
    }
  }

  function valorLetras(&$arregloDatos,$unDatos) {
    $valor = $unDatos->total;
    $monto = new EnLetras();

    $moneda = 'Pesos';
	$valor=round($unDatos->total);
	if($unDatos->trm > 0)
	{
		$moneda = 'Dolares';
		$valor=$unDatos->total/$unDatos->trm;
	}
    $arregloDatos[monto_letras] = strtoupper($monto->ValorEnLetras($valor,$moneda));   
	$arregloDatos[elaborado_por]=$_SESSION['datos_logueo']['nombre_usuario']." ".$_SESSION['datos_logueo']['apellido_usuario'];
	if($unDatos->trm > 0)
	{
		$array_valor=explode(",",number_format ($valor,2,',','.'));
		$decimal=substr($array_valor[1],0,2);
		if($decimal==1){$decimal=0;}
		$arregloDatos[monto_letras]=str_replace('MCTE',$decimal.'/100 '.'USD$',$arregloDatos[monto_letras]);
	}
  }

  function getFormaAnticipos($arregloDatos,$unaPlantilla) { }

  function impresion($arregloDatos,$unDatos,$unaPlantilla) {
    $unDato = new Factura();
    
    $arregloDatos[datos_remesa] = $unDatos->remesa; // Averigua la cantidad de la remesa
    $unDato->getDatosRemesa($arregloDatos);
    $unDato->fetch();
    $arregloDatos[datos_remesa] = "Piezas : " . trim(abs($unDato->cantidad_naci) + abs($unDato->cantidad_nonac));
    $arregloDatos[datos_remesa] .= "  Peso : " . trim(abs($unDato->peso_naci) + abs($unDato->peso_nonac));
    $arregloDatos[datos_remesa] .= "  Valor : " . number_format (round($unDato->cif),0,',','.');
    if(empty($unDatos->remesa) or ((int) $unDatos->remesa)==0) {  // si no hay remesa se saca la informacion de la ORDEN
      $arregloDatos[una_orden]=$unDatos->orden;
      $datosOrden = new Factura();
      $datosOrden->getDatosUnaOrden($arregloDatos);
      $datosOrden->fetch();
      $arregloDatos[datos_remesa] = "Piezas : " . trim(abs($datosOrden->cantidad));
    	$arregloDatos[datos_remesa] .= "  Peso : " . trim(abs($datosOrden->peso));
    	$arregloDatos[datos_remesa] .= "  Valor : " . number_format(round($datosOrden->valor),0,',','.');
    }
    
    $this->conceptos($arregloDatos,$unDatos,$unaPlantilla);
  }

  function copias($arregloDatos) {
    $unaConsulta = new Factura();
    
    $unaConsulta->pieFacturas($arregloDatos);
    $unaConsulta->fetch();
    $this->plantilla->loadTemplateFile(PLANTILLAS .'facturaCopias.html',true,true);
    $factura = $this->factura($arregloDatos);
    
    for($i=1;$i<=$unaConsulta->numero_copias;$i++) {
      $this->plantilla->setCurrentBlock('ROW');
      $this->plantilla->setVariable('copia',$factura);
      $this->plantilla->setVariable('pie1',$unaConsulta->pie1);
      $this->plantilla->setVariable('pie2',$unaConsulta->pie2);

      $hora = "::Hora de facturación ".date("H:m");
      switch($i) {
        case 1:
          $this->plantilla->setVariable('num_copia'	,"Cliente $hora");
          break;
        case 2:
          $this->plantilla->setVariable('num_copia'	,"Contabilidad $hora");
          break;
        case 3:
          $this->plantilla->setVariable('num_copia'	,"Facturación $hora");
          break;
        case 4:
          $this->plantilla->setVariable('num_copia'	,"Consecutivo $hora");
          break;
      }
      $this->plantilla->parseCurrentBlock();  
    }
    $this->plantilla->show();
  }

  // Pinta la Factura
  function factura($arregloDatos) {
    $arregloDatos[mostrar] = 0;
    $arregloDatos[plantilla] = 'facturaMembrete.html';
    $arregloDatos[thisFunction]	= 'impresion'; 
    return $this->setFuncion($arregloDatos,$this->datos);
  }

  function conceptos($arregloDatos,$unDatos,$unaPlantilla) {
    $unaConsulta = new Factura();
    
    $unaConsulta->getDatosResolucion($arregloDatos,$unDatos->id_resolucion);
    $unaConsulta->getRutaFirma($arregloDatos,$unDatos->id_firma);
    $this->valorLetras($arregloDatos,$unDatos);
           
    $this->valoresFormateados($arregloDatos,$unDatos);
    $this->getFechaFormateada($arregloDatos,$unDatos);
    $this->getMarcaCheck($arregloDatos,$unDatos);
    
    $arregloDatos[mostrar] = 0;
    $arregloDatos[plantilla] = 'facturaMembreteConceptos.html';
    $arregloDatos[thisFunction] = 'getConceptos';
    $arregloDatos[conceptos] = $this->setFuncion($arregloDatos,$unDatos);

    $this->mantenerDatos($arregloDatos,$unaPlantilla);
  }
   function getFacturaPreliquidacion($arregloDatos,$unDatos,$unaPlantilla) {
    $this->getFechaFormateada($arregloDatos,$unDatos);
	$this->valoresFormateados($arregloDatos,$unDatos);
	$this->valorLetras($arregloDatos,$unDatos);
  	$this->mantenerDatos($arregloDatos,$unaPlantilla); 
	
	
	//$arregloDatos[num_prefactura]=1398;
	$arregloDatos[mostrar] = 0;
	$arregloDatos[plantilla] = "facturaConceptosPreliquidacion.html";
    $arregloDatos[thisFunction] = 'getConceptos';
    $arregloDatos[conceptos] = $this->setFuncion($arregloDatos,$unDatos);
	//echo $arregloDatos[conceptos];
	$this->mantenerDatos($arregloDatos,$unaPlantilla);
  }
}
?>