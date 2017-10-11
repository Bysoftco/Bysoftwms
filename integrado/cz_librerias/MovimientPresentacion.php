	<?php
require_once ("HTML/Template/IT.php");
require_once ("OrdenLogica.php");
require_once ("InventarioLogica.php");
	class InventarioPresentacion {
    
    	var $datos;
    	var $plantilla;

		function InventarioPresentacion (&$datos) {
        	$this->datos = &$datos;
			$this->plantilla = new HTML_Template_IT();
		} 
	
	function mantenerDatos($arregloCampos,&$plantilla){
	 
	  		$plantilla = &$plantilla;
    		if (is_array($arregloCampos)) {
        		foreach ($arregloCampos as $key => $value) {
            	$plantilla->setVariable($key , $value);
             }
        }
  	}
	
	//Funcion que coloca los datos que vienen de la BD
	function setDatos ($arregloDatos,&$datos,&$plantilla){
	
		foreach ($datos as $key => $value) {
			
			$plantilla->setVariable($key , $value);
		}
		
	}
	
	function getLista ($arregloDatos,$seleccion,&$plantilla){
		
		$unaLista 	= new Inventario();
		$lista		= $unaLista->lista($arregloDatos[tabla],$arregloDatos[condicion],$arregloDatos[campoCondicion]);
		$lista		= armaSelect($lista,'[seleccione]',$seleccion);
		
		$plantilla->setVariable($arregloDatos[labelLista], $lista);
		
	}
	
	function cargaPlantilla($arregloDatos){
		
		$unAplicaciones = new Inventario();
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
	function setFuncion($arregloDatos,$unDatos){
		$unDatos = new Inventario();
		if(empty($arregloDatos[mensaje])){
			header( 'Content-type: text/html; charset=iso-8859-1' );
		}	
		$r=$unDatos->$arregloDatos[thisFunction]($arregloDatos);
		
		$unaPlantilla = new HTML_Template_IT();
		$unaPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla],true,true);
		if(!empty($unDatos->mensaje)){
		
			//$this->plantilla->setVariable('mensaje'	,$unDatos->mensaje);
			//$this->plantilla->setVariable('estilo'	,$unDatos->estilo);
			$unaPlantilla->setVariable('mensaje'	,$unDatos->mensaje);
			$unaPlantilla->setVariable('estilo'	,$unDatos->estilo);
			
		}
		$arregloDatos[mensaje_aux]=$arregloDatos[mensaje_aux];
		if(!empty($arregloDatos[mensaje_aux])){
			$unaPlantilla->setVariable('mensaje'	,$arregloDatos[mensaje_aux]);
			$unaPlantilla->setVariable('estilo'	,$arregloDatos[mensaje_aux]);
			
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
		
		if($unDatos->N==0 and empty($unDatos->datos->mensaje_error)){
			$unaPlantilla->setVariable('mensaje'	,'No hay registros para listar');
			$unaPlantilla->setVariable('estilo'		,'ui-state-error');
			
		}
		$unaPlantilla->setVariable('num_registros'	,$unDatos->N);
		
		if($arregloDatos[mostrar]){
			$unaPlantilla->show();
		}else{
			return $unaPlantilla->get();
		}
		
	}
	
	
	function maestro($arregloDatos){
		
		$this->plantilla->loadTemplateFile(PLANTILLAS .'InventarioMaestro.html',true,true);
		$this->plantilla->setVariable('mensaje'	,$this->datos->mensaje);
		$this->plantilla->setVariable('estilo'	,$this->datos->estilo);
		
		$arregloDatos[tab_index]				=2;
		$this->mantenerDatos($arregloDatos,&$this->plantilla);
		
		$unDatos = new Inventario();
		$arregloDatos[id_tab]		=2;
		
		$arregloDatos[mostar]				="0";
		$arregloDatos[plantilla]			='inventarioToolbar.html';
		$arregloDatos[thisFunction]			='getToolbar';  
		$this->plantilla->setVariable('toolbarInventario'			,$this->cargaPlantilla($arregloDatos,&$this->datos));
		
			
		$traeOrden 		= new OrdenLogica();
		$htmlDatosOrden	=$traeOrden->unaOrden($arregloDatos);	
		$this->plantilla->setVariable('datosOrden'			,$htmlDatosOrden);

		$this->plantilla->show();
	}
	
	
  } 



?>