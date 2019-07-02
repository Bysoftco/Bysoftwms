<?php
/*
  Versión 1.0
  Autor Fredy Arevalo
  Fecha September  11 de 2007
  Descripción:
    Clase encargada de Construir la Interfaz Grafica Para el Modulo de Reporte
*/

require_once("HTML/Template/IT.php");
require_once("Funciones.php");




class ReportePresentacion {
  var $datos;
  var $plantilla;
  var $tot_peso_nac;
  var $p_naci_t;
  var $peso;
  var $p_retiro;
  var $cif_t;

  function ReportePresentacion(&$datos) {
    $this->datos = $datos;
    $this->plantilla = new HTML_Template_IT();
  } 

  function mantenerDatos($arregloCampos,&$plantilla) {
    $plantilla = $plantilla;
    if(is_array($arregloCampos)) {
      foreach($arregloCampos as $key => $value) {
        $plantilla->setVariable($key,$value);
      }
    }
  }

  //Función que coloca los datos que vienen de la BD
  function setDatos($arregloDatos,&$datos,&$plantilla){
    foreach($datos as $key => $value) {
      $plantilla->setVariable($key,$value);
    }
  }

  function getLista($arregloDatos,$seleccion,&$plantilla) {
    $unaLista = new Reporte();
    $lista		= $unaLista->lista($arregloDatos[tabla],$arregloDatos[condicion],$arregloDatos[campoCondicion]);
    $lista		= armaSelect($lista,'[seleccione]',$seleccion);
    $plantilla->setVariable($arregloDatos[labelLista], $lista);
  }

  function cargaPlantilla($arregloDatos) {
    $unAplicaciones = new Reporte();
    $formularioPlantilla = new HTML_Template_IT();
    
    $formularioPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla],false,false);
    $formularioPlantilla->setVariable('comodin'	,' ');
    $this->mantenerDatos($arregloDatos,$formularioPlantilla);
    $this->$arregloDatos[thisFunction]($arregloDatos,$this->datos,$formularioPlantilla);
    if($arregloDatos[mostrar]) {
      $formularioPlantilla->show();
    } else {
      return $formularioPlantilla->get();
    }
  }

  // Arma cada Formulario o función en pantalla
  function setFuncion($arregloDatos,&$unDatos) {
    $unDatos = new Reporte();
    /*if(!empty($arregloDatos[setCharset])) {
      header( 'Content-type: text/html; charset=iso-8859-1' );
    }*/	

		$r = $unDatos->$arregloDatos[thisFunction]($arregloDatos);

    $unaPlantilla = new HTML_Template_IT();
    $unaPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla],true,true);
    $unaPlantilla->setVariable('comodin'	,' ');

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

		if(!empty($arregloDatos[mensaje])) {
      $unaPlantilla->setVariable('mensaje',$arregloDatos[mensaje]);
      $unaPlantilla->setVariable('estilo'	,$arregloDatos[estilo]);
    }
    $this->mantenerDatos($arregloDatos,$unaPlantilla);
    $$arregloDatos[n] = 0;

		while($unDatos->fetch()) {
      if($arregloDatos[n] % 2 ){ $odd = 'odd'; } else { $odd = ''; }
      $arregloDatos[n] = $arregloDatos[n] + 1;
      $unaPlantilla->setCurrentBlock('ROW');
      $this->setDatos($arregloDatos,$unDatos,$unaPlantilla);
      $this->$arregloDatos[thisFunction]($arregloDatos,$unDatos,$unaPlantilla);
      $unaPlantilla->setVariable('n',$arregloDatos[n]);
      $unaPlantilla->setVariable('odd',$odd);
      $unaPlantilla->parseCurrentBlock();
    }

    if($unDatos->N == 0 and empty($unDatos->mensaje)) {
      $unaPlantilla->setVariable('mensaje','No hay registros para listar'.$arregloDatos[mensaje]);
      $unaPlantilla->setVariable('estilo','ui-state-error');
      $unaPlantilla->setVariable('mostrarCuerpo','none');
    }

		$unaPlantilla->setVariable('num_registros',$unDatos->N);
    if($arregloDatos[mostrar]) {
      $unaPlantilla->show();
    } else {
      $unaPlantilla->setVariable('cuenta',$this->cuenta);
      return $unaPlantilla->get();
    }
  }

  function getValoresFormateados(&$arregloDatos,$datos) {
    //Acumuladores de registros y totales
    $this->c_rpk_t = $this->c_rpk_t + abs($datos->c_rpk);
    $this->peso = $this->peso + abs($datos->peso);
    $this->p_nal_t = $this->p_nal_t + abs($datos->p_nal);
    $this->p_ext_t = $this->p_ext_t + abs($datos->p_ext);
    $this->p_ret_nal_t = $this->p_ret_nal_t + abs($datos->p_ret_nal);
    $this->p_ret_ext_t = $this->p_ret_ext_t + abs($datos->p_ret_ext);
    $this->p_sptr_nal = $this->p_sptr_nal + abs($datos->p_sptr_nal);
    $this->p_sptr_ext_t = $this->p_sptr_ext_t + abs($datos->p_sptr_ext);
    $this->p_prtt_nal = $this->p_prtt_nal + abs($datos->p_prtt_nal);
    $this->p_prtt_ext = $this->p_prtt_ext + abs($datos->p_prtt_ext);
    $this->p_rpk_t = $this->p_rpk_t + abs($datos->p_rpk);
    $this->cif_t = $this->cif_t + abs($datos->cif);
    $this->q_nonac_t = $this->q_nonac_t + abs($datos->q_nonac);
    $this->q_retiro_t = $this->q_retiro_t + abs($datos->q_retiro);
    $this->q_naci_t = $this->q_naci_t + abs($datos->q_naci);
    $this->cantidad_t = $this->cantidad_t + abs($datos->cantidad);
    $this->valor_t = $this->valor_t + abs($datos->valor);
    $this->cif_r = $this->cif_r + abs($datos->cif_retirado);
    $this->fob_r = $this->cif_r + abs($datos->fob_retirado);
    $this->c_nal_t = $this->c_nal_t + abs($datos->c_nal);
    $this->c_ext_t = $this->c_ext_t + abs($datos->c_ext);
    $this->tc_ret_nal = $this->tc_ret_nal + abs($datos->c_ret_nal);
    $this->tc_ret_ext = $this->tc_ret_ext + abs($datos->c_ret_ext);
    $this->tc_sptr_nal = $this->tc_sptr_nal + abs($datos->c_sptr_nal);
    $this->tc_sptr_ext = $this->tc_sptr_ext + abs($datos->c_sptr_ext);
    $this->tc_prtt_nal = $this->tc_prtt_nal + abs($datos->c_prtt_nal);
    $this->tc_prtt_ext = $this->tc_prtt_ext + abs($datos->c_prtt_ext);
    $this->tc_kit_ext = $this->tc_kit_ext + abs($datos->c_kit_ext);
    $this->t_cif = $this->t_cif + abs($datos->cif);
    $this->t_fob = $this->t_fob + abs($datos->fob);
    $this->t_fob_ret = $this->t_fob_ret + abs($datos->fob_retiro);
    $this->t_cif_ret = $this->t_cif_ret + abs($datos->cif_retiro);
    $this->t_cif_proc = $this->t_cif_proc + abs($datos->cif_proceso);
    $this->t_cif_ensamb = $this->cif_ensamb + abs($datos->cif_ensamble);
    $this->t_rpk=$this->t_rpk  +abs($datos->v_rpk);
	
	$this->tvalor_f = $this->tvalor_f + abs($datos->valor);//here
	$this->t_fob_f = $this->t_fob_f+ abs($datos->fob);//here

    //Formateo de Datos de Registros
    $arregloDatos[cif_f] = number_format(abs($datos->cif),DECIMALES,".",",");
    $arregloDatos[valor_f] = number_format(abs($datos->valor),DECIMALES,".",",");
    $arregloDatos[fob_f] = number_format(abs($datos->fob),DECIMALES,".",",");
    $arregloDatos[cantidad_f] = number_format(abs($datos->cantidad),DECIMALES,".",",");
    $arregloDatos[q_naci_f] = number_format(abs($datos->q_naci),DECIMALES,".",",");
    $arregloDatos[q_nonac_f] = number_format(abs($datos->q_nonac),DECIMALES,".",",");
    $arregloDatos[q_retiro_f] = number_format(abs($datos->q_retiro),DECIMALES,".",",");
    $arregloDatos[c_rpk_f] = number_format(abs($datos->c_rpk),DECIMALES,".",",");
    $arregloDatos[peso_f] = number_format(abs($datos->peso),DECIMALES,".",",");
    $arregloDatos[p_ext_f] = number_format(abs($datos->p_ext),DECIMALES,".",",");
    $arregloDatos[p_nal_f] = number_format(abs($datos->p_nal),DECIMALES,".",",");
    $arregloDatos[p_ret_ext_f] = number_format(abs($datos->p_ret_ext),DECIMALES,".",",");
    $arregloDatos[p_ret_nal_f] = number_format(abs($datos->p_ret_nal),DECIMALES,".",",");
    $arregloDatos[p_sptr_ext_f] = number_format(abs($datos->p_sptr_ext),DECIMALES,".",",");
    $arregloDatos[p_sptr_nal_f] = number_format(abs($datos->p_sptr_nal),DECIMALES,".",",");
    $arregloDatos[p_prtt_ext_f] = number_format(abs($datos->p_prtt_ext),DECIMALES,".",",");
    $arregloDatos[p_prtt_nal_f] = number_format(abs($datos->p_prtt_nal),DECIMALES,".",",");
    $arregloDatos[p_retiro_f] = number_format(abs($datos->p_retiro),DECIMALES,".",",");
    $arregloDatos[cif_r] = number_format(abs($datos->cif_retirado),DECIMALES,".",",");
    $arregloDatos[p_rpk] = number_format(abs($datos->p_rpk),DECIMALES,".",",");
          
    $arregloDatos[c_nal_f] = number_format(abs($datos->c_nal),DECIMALES,".",",");
    $arregloDatos[c_ext_f] = number_format(abs($datos->c_ext),DECIMALES,".",",");
    $arregloDatos[c_ret_nal_f] = number_format(abs($datos->c_ret_nal),DECIMALES,".",",");
    $arregloDatos[c_ret_ext_f] = number_format(abs($datos->c_ret_ext),DECIMALES,".",",");
    $arregloDatos[c_sptr_nal_f] = number_format(abs($datos->c_sptr_nal),DECIMALES,".",",");
    $arregloDatos[c_sptr_ext_f] = number_format(abs($datos->c_sptr_ext),DECIMALES,".",",");
    $arregloDatos[c_prtt_nal_f] = number_format(abs($datos->c_prtt_nal),DECIMALES,".",",");
    $arregloDatos[c_prtt_ext_f] = number_format(abs($datos->c_prtt_ext),DECIMALES,".",",");
    $arregloDatos[c_kit_ext_f] = number_format(abs($datos->c_kit_ext),DECIMALES,".",",");
    $arregloDatos[c_kit_nal_f] = number_format(abs($datos->c_kit_nal),DECIMALES,".",","); 
    $arregloDatos[fob_retiro] = number_format(abs($datos->fob_retiro),DECIMALES,".",",");
    $arregloDatos[cif_retiro] = number_format(abs($datos->cif_retiro),DECIMALES,".",",");
    $arregloDatos[cif_proceso] = number_format(abs($datos->cif_proceso),DECIMALES,".",",");
    $arregloDatos[cif_ensamble] = number_format(abs($datos->cif_ensamble),DECIMALES,".",",");
    $arregloDatos[v_rpk_f] = number_format(abs($datos->v_rpk),DECIMALES,".",",");
	
	$arregloDatos[tvalor_f] = number_format(abs($this->tvalor_f),DECIMALES,".",",");
	 $arregloDatos[t_fob_f] = number_format(abs($this->t_fob_f),DECIMALES,".",",");         
                    
    //Formateo de Acumuladores de Totales
    $arregloDatos[tpeso_f] = number_format($this->peso,DECIMALES,".",",");
    $arregloDatos[tp_nal_f] = number_format($this->p_nal_t,DECIMALES,".",",");
    $arregloDatos[tp_ext_f] = number_format($this->p_ext_t,DECIMALES,".",",");
    $arregloDatos[tp_ret_nal_f] = number_format($this->p_ret_nal_t,DECIMALES,".",",");
    $arregloDatos[tp_ret_ext_f] = number_format($this->p_ret_ext_t,DECIMALES,".",",");
    $arregloDatos[tp_sptr_nal_f] = number_format($this->p_sptr_nal,DECIMALES,".",",");
    $arregloDatos[tp_sptr_ext_f] = number_format($this->p_sptr_ext_t,DECIMALES,".",",");
    $arregloDatos[tp_prtt_nal_f] = number_format($this->p_prtt_nal,DECIMALES,".",",");
    $arregloDatos[tp_prtt_ext_f] = number_format($this->p_prtt_ext,DECIMALES,".",",");
          
    //$arregloDatos[p_rpk_t] = number_format($this->p_rpk_t,DECIMALES,".",",");
    $arregloDatos[cif_t] = number_format($this->cif_t,DECIMALES,".",",");
    $arregloDatos[q_nonac_t] = number_format($this->q_nonac_t,DECIMALES,".",",");
    $arregloDatos[q_retiro_t] = number_format($this->q_retiro_t,DECIMALES,".",",");
    $arregloDatos[q_naci_t] = number_format($this->q_naci_t,DECIMALES,".",",");
    $arregloDatos[cantidad_t] = number_format($this->cantidad_t,DECIMALES,".",",");
    $arregloDatos[valor_t] = number_format($this->valor_t,DECIMALES,".",",");
    $arregloDatos[cif_r_t] = number_format($this->cif_r,DECIMALES,".",",");
    $arregloDatos[fob_r_t] = number_format($this->fob_r,DECIMALES,".",",");
    $arregloDatos[c_nal_t] = number_format(abs($this->c_nal_t),DECIMALES,".",",");
    $arregloDatos[c_ext_t] = number_format($this->c_ext_t ,DECIMALES,".",",");
    $arregloDatos[tc_ret_ext] = number_format($this->tc_ret_ext ,DECIMALES,".",",");
    $arregloDatos[tc_ret_nal] = number_format($this->tc_ret_nal ,DECIMALES,".",",");
    $arregloDatos[tc_sptr_nal] = number_format($this->tc_sptr_nal ,DECIMALES,".",",");
    $arregloDatos[tc_sptr_ext] = number_format($this->tc_sptr_ext ,DECIMALES,".",",");
    $arregloDatos[tc_prtt_nal] = number_format(abs($this->tc_prtt_nal),DECIMALES,".",",");
    $arregloDatos[tc_prtt_ext] = number_format(abs($this->tc_prtt_ext),DECIMALES,".",","); 
    $arregloDatos[tc_kit_ext] = number_format(abs($this->tc_kit_ext),DECIMALES,".",",");
    $arregloDatos[tc_kit_nal] = number_format(abs($this->tc_kit_nal),DECIMALES,".",",");
    //$arregloDatos[c_rpk_t] = number_format($this->c_rpk_t,DECIMALES,".",",");
    //$arregloDatos[t_rpk] = number_format(abs($this->t_rpk),DECIMALES,".",",");
    $arregloDatos[t_fob_ret] = number_format(abs($this->t_fob_ret),DECIMALES,".",",");
    $arregloDatos[t_cif] = number_format(abs($this->t_cif),DECIMALES,".",",");
    $arregloDatos[t_cif_proc] = number_format(abs($this->t_cif_proc),DECIMALES,".",",");
    $arregloDatos[t_cif_ensamb] = number_format(abs($this->t_cif_ensamb),DECIMALES,".",",");
    $arregloDatos[t_cif_ret] = number_format(abs($this->t_cif_ret),DECIMALES,".",",");
  }

  function inventario($arregloDatos,$unDatos,$plantilla) {
    $this->getValoresFormateados($arregloDatos,$unDatos);
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  function getInventario($arregloDatos,$unDatos,$plantilla) {
    $this->getValoresFormateados($arregloDatos,$unDatos);
    $this->mantenerDatos($arregloDatos,$plantilla);
  }
      
  function getReporteEndosos($arregloDatos,$unDatos,$plantilla) {
    $this->getValoresFormateados($arregloDatos,$unDatos);
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  function filtronPrincipal($arregloDatos,$unDatos,$plantilla) {
  }

  function filtro($arregloDatos,$unDatos,$plantilla) {
    $unaLista 	= new Inventario();
    $lista		= $unaLista->lista("tipos_remesas");
    $lista		= armaSelect($lista,'[seleccione]',NULL);
    $plantilla->setVariable("listaTiposRemesa", $lista);
  }
  function filtroDefectuosas($arregloDatos,$unDatos,$plantilla) {
   
  }
  
  function maestroDefectuosa($arregloDatos) {

	$arregloDatos[tipoLabel]="DEFECTUOSA";
    $this->plantilla->loadTemplateFile(PLANTILLAS . 'reporteMaestroConsulta.html', true, true);
    //$this->mantenerDatos($arregloDatos, $this->plantilla);
    //$this->plantilla->setVariable('comodin', '');
	 if(!empty($arregloDatos[filtro])) {
      //$arregloDatos[mostrar] = 0;
      //$arregloDatos[plantilla] = 'reporteListadoDefectuosas.html';
      //$arregloDatos[thisFunction] = 'listarMercanciaRechazada';
      //$htmlListado = $this->setFuncion($arregloDatos, $unDatos);
      //$this->plantilla->setVariable('htmlListado', $htmlListado);
	  
	  $this->datos =& new Levante();
	  
		$arregloDatos[tipo_retiro]=17;
		$arregloDatos[having] = " HAVING peso_nonac  > 0 OR peso_naci > 0 ";
		//$arregloDatos[where] .=" AND  ie.orden='$arregloDatos[orden]'"; // filtro por referencia
		$arregloDatos[GroupBy] = "orden,codigo_referencia";  // 
		$arregloDatos[movimiento] = "16,17";
		$this->datos->getInvParaProceso($arregloDatos) ;
	
	
		$unaPlantilla = new HTML_Template_IT();
    	$unaPlantilla->loadTemplateFile(PLANTILLAS . 'reporteListadoDefectuosas.html',true,true);
    	$unaPlantilla->setVariable('comodin'	,' ');
		
		
		while($this->datos->fetch()) {
			$odd = ($arregloDatos[n] % 2) ? 'odd' : '';
      		$arregloDatos[n] = $arregloDatos[n] + 1;
			$unaPlantilla->setCurrentBlock('ROW');
     		
			$this->setValores($arregloDatos, $this->datos, $unaPlantilla);
			$this->mantenerDatos($arregloDatos, $unaPlantilla);
			$this->setDatos($arregloDatos,$this->datos,$unaPlantilla);
			
			$unaPlantilla->setVariable('n', $arregloDatos[n]);
     		$unaPlantilla->setVariable('odd', $odd);
      		$unaPlantilla->parseCurrentBlock();
		} 
	   $unaPlantilla->show();
	  //$this->datos->listarMercanciaRechazada($arregloDatos);
	  //echo "XXX".$this->datos->N;
    } else {
		  
      $arregloDatos[thisFunction] = 'filtroDefectuosas';
      $arregloDatos[plantilla] = 'reporteFiltroDefectuosas.html';
      $arregloDatos[mostrar] = 0;
	  
	  $htmlFiltro = $this->cargaPlantilla($arregloDatos);
      $this->plantilla->setVariable('filtroEntrada', $htmlFiltro);
	 } 
	  $this->plantilla->setVariable('filtro'	,$arregloDatos[filtro]);
	 $this->plantilla->setVariable('accion'	,$arregloDatos[accion]);
	$this->plantilla->show();
    // Carga información del Perfil
  }	
  
    function maestroAcondicionados($arregloDatos) {
 
  	$arregloDatos[tipoLabel]="ACONDICIONADA";
    $this->plantilla->loadTemplateFile(PLANTILLAS . 'reporteMaestroConsulta.html', true, true);
    
	 if(!empty($arregloDatos[filtro])) {
   
	  $this->datos =& new Levante();
		$arregloDatos[tipo_retiro]=16;
		$arregloDatos[having] = " HAVING peso_nonac  > 0 OR peso_naci > 0 ";
		//$arregloDatos[where] .=" AND  ie.orden='$arregloDatos[orden]'"; // filtro por referencia
		$arregloDatos[GroupBy] = "orden,codigo_referencia";  // 
		$arregloDatos[movimiento] = "16";// Aqui se suma  el retiro
		$arregloDatos[movimiento] = "16";// Aqui se suma  el retiro
		$this->datos->getInvParaProceso($arregloDatos) ;
	
		$unaPlantilla = new HTML_Template_IT();
    	$unaPlantilla->loadTemplateFile(PLANTILLAS . 'reporteListadoDefectuosas.html',true,true);
    	$unaPlantilla->setVariable('comodin'	,' ');
		
		
		while($this->datos->fetch()) {
			$odd = ($arregloDatos[n] % 2) ? 'odd' : '';
      		$arregloDatos[n] = $arregloDatos[n] + 1;
			$unaPlantilla->setCurrentBlock('ROW');
     		
			$this->setValores($arregloDatos, $this->datos, $unaPlantilla);
			$this->mantenerDatos($arregloDatos, $unaPlantilla);
			$this->setDatos($arregloDatos,$this->datos,$unaPlantilla);
			
			$unaPlantilla->setVariable('n', $arregloDatos[n]);
     		$unaPlantilla->setVariable('odd', $odd);
      		$unaPlantilla->parseCurrentBlock();
		} 
	   $unaPlantilla->show();
	  //$this->datos->listarMercanciaRechazada($arregloDatos);
	  //echo "XXX".$this->datos->N;
    } else {
		  
      $arregloDatos[thisFunction] = 'filtroDefectuosas';
      $arregloDatos[plantilla] = 'reporteFiltroDefectuosas.html';
      $arregloDatos[mostrar] = 0;
	  
	  $htmlFiltro = $this->cargaPlantilla($arregloDatos);
      $this->plantilla->setVariable('filtroEntrada', $htmlFiltro);
	 } 
	 $this->plantilla->setVariable('filtro'	,$arregloDatos[filtro]);
	 $this->plantilla->setVariable('accion'	,$arregloDatos[accion]);
	 $this->plantilla->show();
     // Carga información del Perfil
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
		
		function listadoRechazadas($arregloDatos) 
		{
			$this->datos =& new Levante();
			$arregloDatos[tipo_retiro]=17;
			$arregloDatos[having] = " HAVING peso_nonac  > 0 OR peso_naci > 0 ";
			$arregloDatos[GroupBy] = "orden,codigo_referencia";  // 
			$arregloDatos[movimiento] = "16,17";
			$this->datos->getInvParaProceso($arregloDatos) ;
	
			$unaPlantilla = new HTML_Template_IT();
    		$unaPlantilla->loadTemplateFile(PLANTILLAS . 'reporteListadoRechazadas.html',true,true);
    		$unaPlantilla->setVariable('comodin'	,' ');
		
			while($this->datos->fetch()) {
				$odd = ($arregloDatos[n] % 2) ? 'odd' : '';
      			$arregloDatos[n] = $arregloDatos[n] + 1;
				$unaPlantilla->setCurrentBlock('ROW');
     		
				$this->setValores($arregloDatos, $this->datos, $unaPlantilla);
				$this->mantenerDatos($arregloDatos, $unaPlantilla);
				$this->setDatos($arregloDatos,$this->datos,$unaPlantilla);
			
				$unaPlantilla->setVariable('n', $arregloDatos[n]);
     			$unaPlantilla->setVariable('odd', $odd);
      			$unaPlantilla->parseCurrentBlock();
			} 
	   	
		$unaPlantilla->show();
	 
  	}	

  		function listadoAcondicionadas($arregloDatos) 
		{
			
			
			$this->datos =& new Levante();
			$arregloDatos[tipo_retiro]=16;
			$arregloDatos[having] = " HAVING peso_nonac  > 0 OR peso_naci > 0 ";
			$arregloDatos[GroupBy] = "orden,codigo_referencia";  // 
			$arregloDatos[movimiento] = "16"; // Ojo aqui se suma el retiro del alistamiento
			$this->datos->getInvParaProceso($arregloDatos) ;
	
			$unaPlantilla = new HTML_Template_IT();
    		$unaPlantilla->loadTemplateFile(PLANTILLAS . 'reporteListadoRechazadas.html',true,true);
    		$unaPlantilla->setVariable('comodin'	,' ');
		
			while($this->datos->fetch()) {
				$odd = ($arregloDatos[n] % 2) ? 'odd' : '';
      			$arregloDatos[n] = $arregloDatos[n] + 1;
				$unaPlantilla->setCurrentBlock('ROW');
     		
				$this->setValores($arregloDatos, $this->datos, $unaPlantilla);
				$this->mantenerDatos($arregloDatos, $unaPlantilla);
				$this->setDatos($arregloDatos,$this->datos,$unaPlantilla);
			
				$unaPlantilla->setVariable('n', $arregloDatos[n]);
     			$unaPlantilla->setVariable('odd', $odd);
      			$unaPlantilla->parseCurrentBlock();
			} 
	   	
		$unaPlantilla->show();
	 
  	}
} 
?>