	<?php

require_once("Nacionalizar.datos.php");
require_once("Inventario.datos.php");

	class NacionalizarDatos{
    	var $datos;
		var $pantalla;

   		function NacionalizarDatos () {
        	$this->datos = &new Nacionalizar();
     
        } 
		
		function setDatosRotacion ($arregloDatos) {
			$this->plantilla->setVariable('orden'				,	$this->datos->orden);
			$this->plantilla->setVariable('ingreso'		    	,	$this->datos->num_ingreso);
			$this->plantilla->setVariable('num_referencia'		,	$this->datos->num_referencia);
			$this->plantilla->setVariable('id_rotacion'			,	$this->datos->id_rotacion);
			
			$this->plantilla->setVariable('cantidad_nacional'	,	$this->datos->cantidad_nacional);
			$this->plantilla->setVariable('cantidad_extranjera'	,	$this->datos->cantidad_extranjera);
			$this->plantilla->setVariable('peso_nacional'	,	$this->datos->peso_nacional);
			$this->plantilla->setVariable('peso_extranjero'	,	$this->datos->peso_extranjero);
			$this->plantilla->setVariable('cif_nacional'	,	$this->datos->cif_nacional);
			$this->plantilla->setVariable('cif_extranjero'	,	$this->datos->cif_extranjero);
			
			
			$this->plantilla->setVariable('nombre_bodega'		,	$this->datos->nombre_bodega);
			$this->plantilla->setVariable('nombre_estado'		,	$this->datos->nombre_estado	);
			$this->plantilla->setVariable('documento'			,	$this->datos->documento	);
			$this->plantilla->setVariable('nombre_referencia'	,	$this->datos->nombre_referencia	);
			$this->plantilla->setVariable('subreferencia'	    ,	$this->datos->subreferencia);
			//<span class="Subtitulo_rojo">qen_rotacion_extrangera</span>
			
		}
		 
		function asignarCampos ($arregloDatos) {
		 	$this->plantilla->setVariable('manifiesto'			,	$this->datos->manifiesto);
			$this->plantilla->setVariable('fecha_manifiesto'	,	$this->datos->fecha_manifiesto);
		    $this->plantilla->setVariable('orden'			,	$this->datos->orden);
			$this->plantilla->setVariable('ingreso'		    ,	$this->datos->num_ingreso);
			$this->plantilla->setVariable('nombre_cliente'	,	$this->datos->nombre_cliente);
			$this->plantilla->setVariable('id_cliente'      ,   $arregloDatos[id]);
			$this->plantilla->setVariable('documento'		,	$this->datos->documento_transporte);
		    $this->plantilla->setVariable('disponibles'		,	$this->datos->disponibles);
			$this->plantilla->setVariable('no_disponibles'	,	$this->datos->no_disponibles);
			$this->plantilla->setVariable('cantidad_ingreso',	$this->datos->cantidad_ingreso);
		    $this->plantilla->setVariable('vencimiento'		,   $this->datos->fecha_vencimiento);
			$this->plantilla->setVariable('factura'			,	$this->datos->factura);
			$this->plantilla->setVariable('peso_bascula'	,   $this->datos->peso_bascula);
			$this->plantilla->setVariable('valor_cif'		,   $this->datos->valor_cif);
			$this->plantilla->setVariable('num_referencia'	,   $this->datos->num_referencia);
			$this->plantilla->setVariable('peso_guia'		,   $this->datos->peso_guia);
			$this->plantilla->setVariable('peso_d'			,   $this->datos->peso_disponible);
			$this->plantilla->setVariable('fob_ref_extranjero'	,   $this->datos->fob_ref_extranjero);
			$this->plantilla->setVariable('fob_ref_nacional'	,   $this->datos->fob_ref_nacional);
			$this->plantilla->setVariable('nombre_estado'	    ,	$this->datos->nombre_estado);
			$this->plantilla->setVariable('nombre_ubicacion'	,	$this->datos->nombre_ubicacion);
			$this->plantilla->setVariable('subreferencia'	    ,	$this->datos->subreferencia);
			
			
		} 
  		
		function asignarCamposFormulario($arregloCampos){		    
         	 if (is_array($arregloCampos)) {
               foreach ($arregloCampos as $key => $value) {
               	$this->plantilla->setVariable($key , $value);
                
             }
           }
		}
	} 

	
	
	class NacionalizarFormulario extends NacionalizarDatos {
   
		function NacionalizarFormulario () {
    		 NacionalizarDatos::NacionalizarDatos();
			 $this->pantalla = &new NacionalizarPantallaFormulario($this->datos);
		} 
		
		
		function listadoParaNacionalizar($arregloDatos){
			$unNacionalizar = new Nacionalizar();
			$arregloDatos[orden]=$arregloDatos[num_do];
			$unNacionalizar->listadoParaNacionalizar($arregloDatos);
			
			$arregloDatos[plantilla]='nacionalizarInventario.html';
			$this->pantalla->listadoParaNacionalizar($arregloDatos,&$unNacionalizar);
		}
		
		
		function filtroNacionalizar ($arregloDatos){
			$this->pantalla->filtroNacionalizar($arregloDatos);
		}
		
		function filtroLevante ($arregloDatos){
			$this->pantalla->filtroLevante($arregloDatos);
		}
		
		//funcion  que lista la mercancia por nacionalizar que esta en el inventario
		function paraNacionalizarInventario ($arregloDatos){
			$paraNacionalizar = new Inventario();
			$arregloDatos[tipo_movimiento]=5;
			if(empty($arregloDatos[orden])){
				$arregloDatos[orden]=$arregloDatos[num_do];
			}
			$paraNacionalizar->listar($arregloDatos);	
			$this->pantalla->paraNacionalizarInventario($arregloDatos,&$paraNacionalizar);
		}
		
		function listaLevantes($arregloDatos){
			$this->datos->listaLevantes($arregloDatos);
			$this->pantalla->listaLevantes($arregloDatos);
		}
		
		function filtroConsulta($arregloDatos) {
				$this->pantalla->filtroConsulta($arregloDatos);
		}
		
		function verDetalleLevante($arregloDatos) {
			$this->datos->verDetalleLevante($arregloDatos);
			$this->pantalla->verDetalleLevante($arregloDatos);
		}
		
		function guardaNacionalizar($arregloDatos){
			
			$this->datos->reportarCantidadNacionalizada($arregloDatos);
			$this->datos->reportarLevanteCuerpo($arregloDatos);
			$this->datos->reportarLevanteCabeza($arregloDatos);
			$unNacionalizar = new Nacionalizar();
			$unNacionalizar->listadoParaNacionalizar($arregloDatos);
			$this->pantalla->listadoParaNacionalizar($arregloDatos,&$unNacionalizar);
		}
		
		function guardaNacionalizarInventario($arregloDatos){
			$this->datos->reportarLevanteCabeza($arregloDatos);
			$this->datos->reportarLevanteCuerpo($arregloDatos);
			$this->datos->reportarCantidadNacionalizadaReferencias($arregloDatos);
			$paraNacionalizar = new Inventario();
			$arregloDatos[tipo_movimiento]=5;
			if(empty($arregloDatos[orden])){
				$arregloDatos[orden]=$arregloDatos[num_do];
			}
			$paraNacionalizar->listar($arregloDatos);	
			$this->pantalla->paraNacionalizarInventario($arregloDatos,&$paraNacionalizar);
		
			
			;
			
			//$unNacionalizar = new Nacionalizar();
			//$unNacionalizar->listadoParaNacionalizar($arregloDatos);
			
		}
		
		
		
		
	}
  	
	
	
	class NacionalizarListado extends NacionalizarDatos {
   
		function NacionalizarListado () {
    		 NacionalizarDatos::NacionalizarDatos();
			 $this->pantalla = &new NacionalizarPantallaListado($this->datos);
		}	
     	
		
	} 

	//*************************************************************************************************
	//Interfaz Grafica de La Aplicacion
	//**************************************************************************************************
	
	
	class NacionalizarPantalla extends NacionalizarDatos  {
   		var $datos;
		var $plantilla;
    	function NacionalizarPantalla (&$datos) {
      		$this->datos = &$datos;
        	$this->plantilla = new HTML_Template_IT();
    	} 
		
     
	 } 
	 
	 class NacionalizarPantallaFormulario extends  NacionalizarPantalla {
   
    	function NacionalizarPantallaFormulario  (&$datos) {
      		 NacionalizarPantalla::NacionalizarPantalla($datos);
    	} 
		
			
		function buscarCliente($arregloDatos) {
			$this->plantilla->loadTemplateFile(PLANTILLAS . 'buscarClienteReferencia.html',true,true);
			$this->plantilla->setVariable('titulo','Nacionalizar');
			$this->plantilla->setVariable('modulo', 'Nuevo Control');
			$this->plantilla->setVariable('kernel', 'Nacionalizar');
			$this->plantilla->setVariable('metodo', 'listaArribos');
			$this->plantilla->setVariable('tipo'  , 'Formulario');
			$this->plantilla->show();	
		  }
		  
		  
		   function filtroNacionalizar ($arregloDatos) {
		    
			 $operaciones=$this->datos->lista('tipos_operacion','codigo','nombre');
			 $selectOperaciones =armaLista($operaciones ,'[Operaciones]');
			 $this->plantilla->loadTemplateFile(PLANTILLAS . 'acondicionadoFiltroDoc.html',false,false);
			 $this->plantilla->setVariable('operaciones' ,	$selectOperaciones);
			 $this->plantilla->setVariable('titulo'      ,	'Nacionalizar');
			 $this->plantilla->setVariable('kernel'      ,	'Nacionalizar');
			 $this->plantilla->setVariable('tipo'        ,	'Formulario');
			 $this->plantilla->setVariable('metodo'      ,	'listadoParaNacionalizar');
			 $this->plantilla->show();	
		}
		
		   function filtroLevante ($arregloDatos) {
		    
			 $operaciones=$this->datos->lista('tipos_operacion','codigo','nombre');
			 $selectOperaciones =armaLista($operaciones ,'[Operaciones]');
			 $this->plantilla->loadTemplateFile(PLANTILLAS . 'acondicionadoFiltroDoc.html',false,false);
			 $this->plantilla->setVariable('operaciones' ,	$selectOperaciones);
			 $this->plantilla->setVariable('titulo'      ,	'Nacionalizar');
			 $this->plantilla->setVariable('kernel'      ,	'Nacionalizar');
			 $this->plantilla->setVariable('tipo'        ,	'Formulario');
			 $this->plantilla->setVariable('metodo'      ,	'paraNacionalizarInventario');
			 $this->plantilla->show();	
		}
		
		  
		  function listadoParaNacionalizar($arregloDatos,$datos) {
		  		
				$unaLista = new Nacionalizar();
				$this->datos=$datos;
				$this->plantilla->loadTemplateFile(PLANTILLAS . 'nacionalizarInventario.html',true,true);
				$n=0;
				$unNombre = new Nacionalizar();
				
				$depositos	=$unaLista->lista('bodegas','codigo','nombre');
				
			
				while ($this->datos->fetch()) {
					
					$n=$n+1;
					
					$this->plantilla->setCurrentBlock('ROW');
					$this->asignarCampos($arregloDatos);
					
					$sias = $unaLista->lista('clientes','numero_documento','razon_social','tipo',4);
					$selectSias   	=armaSelect($sias , '[SIA]' );
					$this->plantilla->setVariable('selectSias'		, $selectSias);
					
					$this->setDatosRotacion($arregloDatos);
					$this->plantilla->setVariable('lote'      		,$this->datos->lote);
					$arregloDatos[cliente]		= $this->datos->cliente; 
					$arregloDatos[id_referencia]=$this->datos->id_referencia;
					$nombre	=$unNombre->nombreReferencia($arregloDatos);
					$this->plantilla->setVariable('n'      		,$n);
					$this->plantilla->setVariable('levante'      		,'03'.date('Y'));
					$this->plantilla->setVariable('nombre_referencia'      		,$nombre);
					
					$this->plantilla->parseCurrentBlock();
					
				}
				$this->plantilla->setVariable('ordenAux',$this->datos->orden );
				$this->plantilla->setVariable('ingresoAux',$this->datos->num_ingreso );
				$this->plantilla->setVariable('documentoAux',$this->datos->documento );
				
				if($this->datos->N == 0){
					$this->plantilla->setVariable('estilo'      		,'info');
					$this->plantilla->setVariable('mensaje'      		,"No se encontró mercancía para nacionalizar del cliente".$arregloDatos[cliente]);
					$this->plantilla->setVariable('mostrarTabla'        ,'none');
				}	
				
				
				$this->plantilla->show();
		  }
		  
		  
		  
		function listaLevantes($arregloDatos){
				$this->plantilla->LoadTemplateFile(PLANTILLAS .'nacionalizarListadoLevantes.html', true,true);
				while($this->datos->fetch()){
					++$n;
					$this->plantilla->setCurrentBlock('ROW');
					$this->plantilla->SetVariable('n'               , $n);
					$this->plantilla->SetVariable('levante'               , $this->datos->levante);
					$this->plantilla->SetVariable('fecha'               , $this->datos->fecha);
					$this->plantilla->SetVariable('sia'               , $this->datos->sia); 
					$this->plantilla->SetVariable('fmmn'               , $this->datos->fmmn);
					$this->plantilla->parseCurrentBlock();
					
				}
				$this->plantilla->setVariable('mensaje'        ,'');
				if($this->datos->N == 0){
					$this->plantilla->setVariable('estilo'      		,'info');
					$this->plantilla->setVariable('mensaje'      		,"La consulta no arrojo ningun registro como resultado");
					$this->plantilla->setVariable('mostrarTabla'        ,'none');
				}
		
				$this->plantilla->show(); 
		}


		  function filtroConsulta($arregloDatos) {
		  
			$this->plantilla->loadTemplateFile(PLANTILLAS . 'nacionalizarFiltroConsulta.html',false,false);
			$this->plantilla->setVariable('comodin','');
			$this->plantilla->show();	
		  }
		  
		
		function verDetalleLevante($arregloDatos){
				$this->plantilla->LoadTemplateFile(PLANTILLAS .'nacionalizarDetalleLevantes.html', true,true);
				while($this->datos->fetch()){
					++$n;
					$this->plantilla->setCurrentBlock('ROW');
					$this->plantilla->SetVariable('n'               , $n);
					$this->plantilla->SetVariable('levante'               , $this->datos->levante);
					$this->plantilla->SetVariable('orden'               , $this->datos->orden);
					$this->plantilla->SetVariable('lote'               , $this->datos->lote);
					$this->plantilla->SetVariable('ingreso'               , $this->datos->ingreso);
					$this->plantilla->SetVariable('cantidad'               , $this->datos->cantidad);
					$this->plantilla->SetVariable('documento'               , $this->datos->documento);
					$this->plantilla->SetVariable('nombre_referencia'        , $this->datos->nombre_referencia);
					
					$this->plantilla->parseCurrentBlock();
					
				}
				$this->plantilla->setVariable('mensaje'        ,'');
				if($this->datos->N == 0){
					$this->plantilla->setVariable('estilo'      		,'info');
					$this->plantilla->setVariable('mensaje'      		,"La consulta no arrojo ningun registro como resultado");
					$this->plantilla->setVariable('mostrarTabla'        ,'none');
				}
		
				$this->plantilla->show(); 
		}
		
		
		function paraNacionalizarInventario($arregloDatos,$datos) {
		  		
				$unaLista = new Nacionalizar();
				$this->datos=$datos;
				$this->plantilla->loadTemplateFile(PLANTILLAS . 'nacionalizarReferencias.html',true,true);
				$n=0;
				$unNombre = new Nacionalizar();
				
				$depositos	=$unaLista->lista('bodegas','codigo','nombre');
				$this->plantilla->setVariable('fecha_levante'		, date('Y/m/d'));
			
				while ($this->datos->fetch()) {
					
					$n=$n+1;
					
					$this->plantilla->setCurrentBlock('ROW');
					$this->asignarCampos($arregloDatos);
					
					$sias = $unaLista->lista('clientes','numero_documento','razon_social','tipo',4);
					$selectSias   	=armaSelect($sias , '[SIA]' );
					$this->plantilla->setVariable('selectSias'		, $selectSias);
					
					$this->setDatosRotacion($arregloDatos);
					$this->plantilla->setVariable('lote'      		,$this->datos->lote);
					$arregloDatos[cliente]		= $this->datos->cliente; 
					$arregloDatos[id_referencia]=$this->datos->id_referencia;
					$nombre	=$unNombre->nombreReferencia($arregloDatos);
					$this->plantilla->setVariable('n'      		,$n);
					$this->plantilla->setVariable('levante'      		,'03'.date('Y').'000');
					
					$this->datos->no_disponibles=number_format ($this->datos->no_disponibles,2,'.','');
					$this->datos->disponibles=number_format ($this->datos->disponibles,2,'.','');
					
					$this->plantilla->setVariable('no_disponibles'      	,$this->datos->no_disponibles);
					$this->plantilla->setVariable('disponibles'      		,$this->datos->disponibles);
					$this->plantilla->setVariable('nombre_bodega'      		,$this->datos->nombre_bodega);
					$this->plantilla->setVariable('nombre_referencia'      	,$nombre);
					
					$this->datos->peso_disponible=number_format ($this->datos->peso_disponible,2,'.','');
					$this->datos->peso_guia=number_format ($this->datos->peso_guia,2,'.','');
					
					$this->plantilla->setVariable('peso_disponible'      	,$this->datos->peso_disponible);
					$this->plantilla->setVariable('peso_guia'      		    ,$this->datos->peso_guia);
					$this->plantilla->setVariable('factura'      		    ,$this->datos->factura);
					//$this->plantilla->setVariable('cif_extranjero'      	,$this->datos->fob_ref_extranjero);
					//$this->plantilla->setVariable('cif_nacional'      	,$this->datos->fob_ref_nacional);
					$this->datos->fob_ref_extranjero=number_format ($this->datos->fob_ref_extranjero,2,'.','');
					$this->datos->fob_ref_nacional=number_format ($this->datos->fob_ref_nacional,2,'.','');
					$this->plantilla->setVariable('cif_extranjero'      	,$this->datos->fob_ref_extranjero);
					$this->plantilla->setVariable('cif_nacional'      	    ,$this->datos->fob_ref_nacional);
					$this->plantilla->parseCurrentBlock();
					
				}
				$this->plantilla->setVariable('ordenAux',$this->datos->orden );
				$this->plantilla->setVariable('ingresoAux',$this->datos->num_ingreso );
				$this->plantilla->setVariable('documentoAux',$this->datos->documento );
				
				if($this->datos->N == 0){
					$this->plantilla->setVariable('estilo'      		,'info');
					$this->plantilla->setVariable('mensaje'      		,"No se encontró mercancía para nacionalizar del cliente".$arregloDatos[cliente]);
					$this->plantilla->setVariable('mostrarTabla'        ,'none');
				}	
				
				
				$this->plantilla->show();
		  }


		
		} 
	 
	 class NacionalizarPantallaListado extends  NacionalizarPantalla {
   
    	function NacionalizarPantallaListado (&$datos) {
      		NacionalizarPantalla::NacionalizarPantalla($datos);
    	}
	}

?>
