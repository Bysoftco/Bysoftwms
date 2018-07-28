	<?php
require_once ("HTML/Template/IT.php");
require_once ("OrdenLogica.php");
require_once ("MovimientoLogica.php");
    class MovimientoPresentacion 
    {
    
        var $datos;
        var $plantilla;

        function MovimientoPresentacion (&$datos) 
        {
            $this->datos = &$datos;
            $this->plantilla = new HTML_Template_IT();
        } 
	
	function mantenerDatos($arregloCampos,&$plantilla)
        {
            $plantilla = &$plantilla;
    		if (is_array($arregloCampos)) 
                {
                    foreach ($arregloCampos as $key => $value) 
                    {
                        $plantilla->setVariable($key , $value);
                    }
                }
  	}
	
	//Funcion que coloca los datos que vienen de la BD
	function setDatos ($arregloDatos,&$datos,&$plantilla)
        {
	
            foreach ($datos as $key => $value) 
            {
                $plantilla->setVariable($key , $value);
            }
		
	}
	
	function getLista ($arregloDatos,$seleccion,&$plantilla)
        {
		
            $unaLista 	= new Movimiento();
            $lista		= $unaLista->lista($arregloDatos[tabla],$arregloDatos[condicion],$arregloDatos[campoCondicion]);
            $lista		= armaSelect($lista,'[seleccione]',$seleccion);

            $plantilla->setVariable($arregloDatos[labelLista], $lista);
		
	}
	
	function cargaPlantilla($arregloDatos)
        {

            $unAplicaciones = new Levante();
            $formularioPlantilla = new HTML_Template_IT();
            $formularioPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla],false,false);
            $formularioPlantilla->setVariable('comodin'	,' ');
            $this->mantenerDatos($arregloDatos,&$formularioPlantilla);

            $this->$arregloDatos[thisFunction]($arregloDatos,$this->datos,$formularioPlantilla);
            if($arregloDatos[mostrar]){
                    $formularioPlantilla->show();
            }else{

                    return $formularioPlantilla->get();
            }
            
        }
	// Arma cada Formulario o funcion en pantalla
	function setFuncion($arregloDatos,$unDatos)
        {
		
                $unDatos = new Factura();
		if(!empty($arregloDatos[setCharset])){
			//header( 'Content-type: text/html; charset=iso-8859-1' );
		}	
		
                $r=$unDatos->$arregloDatos[thisFunction](&$arregloDatos);
		
		if(!empty($arregloDatos[thisFunctionAux])){$this->$arregloDatos[thisFunctionAux](&$arregloDatos);}
                
                $unaPlantilla = new HTML_Template_IT();
		$unaPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla],true,true);
                $unaPlantilla->setVariable('comodin'	,' ');
		if(!empty($unDatos->mensaje_error))
                {
                    $unaPlantilla->setVariable('mensaje',$unDatos->mensaje_error);
                    $unaPlantilla->setVariable('estilo'	,$unDatos->mensaje_error);
		}
	
		$this->mantenerDatos($arregloDatos,$unaPlantilla);
		$$arregloDatos[n]=0;
		while ($unDatos->fetch()) {
                   
                    if ($arregloDatos[n] % 2 ){$odd='odd';}else{$odd='';}  
                    $arregloDatos[n]=$arregloDatos[n]+1;
                    $unaPlantilla->setCurrentBlock('ROW');
                   
                    $this->setDatos($arregloDatos,$unDatos,$unaPlantilla);

                    $this->$arregloDatos[thisFunction]($arregloDatos,$unDatos,$unaPlantilla);
                    $unaPlantilla->setVariable('n'	,$arregloDatos[n]);
                    $unaPlantilla->setVariable('odd'			, $odd);
                    $unaPlantilla->parseCurrentBlock();
			
		}
		
		if($unDatos->N==0 )
                {
                  $unaPlantilla->setVariable('mensaje'	,'No hay registros para listar, '.$unDatos->mensaje_error);
		  $unaPlantilla->setVariable('estilo'	,'ui-state-highlight');
                  $unaPlantilla->setVariable('mostrarCuerpo'	,'none');
		}
		$unaPlantilla->setVariable('num_registros'	,$unDatos->N);
		
		if($arregloDatos[mostrar]){
                    $unaPlantilla->show();
		}else{
                    
                    $unaPlantilla->setVariable('cuenta'	,$this->cuenta);
                    return $unaPlantilla->get();
		}
		
	}
	
        
        function maestroReapertura($arregloDatos)
        {
            $this->plantilla->loadTemplateFile(PLANTILLAS .'movimientoMaestroReapertura.html',true,true);
		
            if(empty($arregloDatos[id_index]))
            {
                    $arregloDatos[tab_index]=0;
                    $arregloDatos[id_tab]=0;
            }
            $this->mantenerDatos($arregloDatos,&$this->plantilla);
            //Aqui se decide  si se abre la ventana por default
            $this->plantilla->setVariable('abre_ventana'		,0);
            if(empty($arregloDatos[filtro]))
            {
                $arregloDatos[mostrarFiltroEstado]	='none';
                $arregloDatos[thisFunction]			='filtro';  
                $arregloDatos[plantilla]			='ordenReporteFiltro.html';
                $arregloDatos[mostrar]				=0;
                $htmlFormularioVentana=$this->cargaPlantilla($arregloDatos);
                $this->plantilla->setVariable('filtroEntrada'			,$htmlFormularioVentana);
                $this->plantilla->setVariable('abre_ventana'	,1);
			
            }else
            {
                $unDatos = new Movimiento();
                $arregloDatos[id_tab]		=0;
                $arregloDatos[mostrar]		=0;
                $arregloDatos[reasignado]       ='Si';
                $arregloDatos[plantilla]	='ordenListadoReapertura.html';
                $arregloDatos[thisFunction]	='listarOrdenes';
                $htmlListado                    =$this->setFuncion($arregloDatos,&$unDatos);
                $this->plantilla->setVariable('htmlListado' ,$htmlListado);
            }	
		
            $this->plantilla->setVariable('nombre_empleado'	,$arregloDatos[nombre_empleado]);
            $this->plantilla->show();
        }
        
        function filtro($arregloDatos,&$datos,&$plantilla)
        {
            $arregloDatos[tabla]	='do_bodegas';
            $arregloDatos[labelLista]	='listaBodegas';
            $this->getLista($arregloDatos,NULL,$plantilla);

            $arregloDatos[tabla]	='do_estados';
            $arregloDatos[labelLista]	='listaEstados';
            $this->getLista($arregloDatos,NULL,$plantilla);
	}
	
  } 



?>