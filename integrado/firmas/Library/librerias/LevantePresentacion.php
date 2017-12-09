<?php
    require_once ("HTML/Template/IT.php");
    require_once ("InventarioDatos.php");
    require_once ("LevanteLogica.php");
	class LevantePresentacion {
    
    	var $datos;
    	var $plantilla;
        var $tot_fob_nac    =0;
        var $tot_peso_nac   =0;
        var $tot_cant_nac   =0;
        var $cuenta   =0;

    function LevantePresentacion (&$datos) 
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
    function setDatos ($arregloDatos,&$datos,&$plantilla){

        foreach ($datos as $key => $value) {

                $plantilla->setVariable($key , $value);
        }

    }
	
    function getLista ($arregloDatos,$seleccion,&$plantilla){

        $unaLista 	= new Levante();
        $lista		= $unaLista->lista($arregloDatos[tabla],$arregloDatos[condicion],$arregloDatos[campoCondicion]);
       
        $lista		= armaSelect($lista,'[seleccione]',$seleccion);
        $plantilla->setVariable($arregloDatos[labelLista], $lista);

    }
	
    function cargaPlantilla($arregloDatos){

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
		
                $unDatos = new Levante();
		if(!empty($arregloDatos[setCharset])){
			header( 'Content-type: text/html; charset=iso-8859-1' );
		}	
		$r=$unDatos->$arregloDatos[thisFunction](&$arregloDatos);
		
		$unaPlantilla = new HTML_Template_IT();
		$unaPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla],true,true);
                $unaPlantilla->setVariable('comodin'	,' ');
		if(!empty($arregloDatos[mensaje])){
	
                    $unaPlantilla->setVariable('mensaje',$arregloDatos[mensaje]);
                    $unaPlantilla->setVariable('estilo'	,$arregloDatos[estilo]);
			
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
		if(!empty($arregloDatos[thisFunctionAux])){
                    $this->$arregloDatos[thisFunction]($arregloDatos,$unDatos,$unaPlantilla);
                }
                     
		if($unDatos->N==0 and empty($unDatos->mensaje))
                {
                  $unaPlantilla->setVariable('mensaje'	,'No hay registros para listar'.$arregloDatos[mensaje]);
		  $unaPlantilla->setVariable('estilo'	,'ui-state-error');
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
	
	
	function maestro($arregloDatos){
		//var_dump($arregloDatos);
		$this->plantilla->loadTemplateFile(PLANTILLAS .'levanteMaestro.html',true,true);
                $this->plantilla->setVariable('comodin'	,' ');
		$arregloDatos[tab_index]				=2;
                $this->getTitulo(&$arregloDatos);
		$this->mantenerDatos($arregloDatos,&$this->plantilla);
		
                $arregloDatos[mostar]                           ="0";
                $arregloDatos[plantilla]			='levanteToolbar.html';
                $arregloDatos[thisFunction]			='getToolbar';  
                $this->plantilla->setVariable('toolbarLevante',$this->setFuncion($arregloDatos,&$this->datos));
                
		if(empty($arregloDatos[por_cuenta_filtro])){
			$this->plantilla->setVariable('abre_ventana'			,1);
                       
		}else{
			$this->plantilla->setVariable('abre_ventana'			,0);
			// el metodo controlarTransaccion de la Logica envia la plantilla y el metodo para pintar el TAB de mercancia
			
			$arregloDatos[mostrar]				=0;
			$arregloDatos[plantilla]			=$arregloDatos[plantillaMercanciaCuerpo];
			$arregloDatos[thisFunction]			=$arregloDatos[metodoMercanciaCuerpo];  
			$htmlMercancia=$this->setFuncion($arregloDatos,&$this->datos);
			$this->plantilla->setVariable('htmlMercancia'	,$htmlMercancia);
                         
                       //Pinta el formulario en el TAB  levante
                       
			$arregloDatos[mostrar]				=0;
			$arregloDatos[plantilla]			=$arregloDatos[plantillaCabeza];
			$arregloDatos[thisFunction]			=$arregloDatos[metodoCabeza]; 
			$htmlLevante=$this->setFuncion($arregloDatos,&$this->datos);
			$this->plantilla->setVariable('htmlLevante'	,$htmlLevante);
                        
                        //Cuerpo del movimiento
                        
                        $arregloDatos[mostrar]				=0;
			$arregloDatos[plantilla]			=$arregloDatos[plantillaCuerpo];
			$arregloDatos[thisFunction]			=$arregloDatos[metodoCuerpo];
			$htmlLevante=$this->setFuncion($arregloDatos,&$this->datos);
			$this->plantilla->setVariable('htmlCuerpo'			,$htmlLevante);
                      
		}
		
		
		$unDatos = new Levante();
		$arregloDatos[id_tab]                           =2;
                $arregloDatos[mostar]				="0";
		$arregloDatos[plantilla]			=$arregloDatos[plantillaFiltro];
		$arregloDatos[thisFunction]			='filtro';  
		$this->plantilla->setVariable('filtro'		,$this->cargaPlantilla($arregloDatos,&$this->datos));
		
		$arregloDatos[thisFunction]			='filtro';  
                $arregloDatos[plantilla]			='levanteFiltroBusca.html';
                $arregloDatos[mostrar]				=0;
                $htmlFiltro=$this->cargaPlantilla($arregloDatos);
                $this->plantilla->setVariable('filtroFiltro'	,$htmlFiltro);
                $this->plantilla->show();
    }
    
    function maestroConsulta($arregloDatos)
    {
		
        $this->plantilla->loadTemplateFile(PLANTILLAS .'levanteMaestroConsulta.html',true,true);
        $this->mantenerDatos($arregloDatos,&$this->plantilla);
        $this->plantilla->setVariable('comodin'	,'');

        if(!empty($arregloDatos[filtro])){
                $arregloDatos[mostrar]		=0;
                $arregloDatos[plantilla]	='levanteListado.html';
                $arregloDatos[thisFunction]	='listarLevantes';
                $htmlListado				=	$this->setFuncion($arregloDatos,&$unDatos);
                $this->plantilla->setVariable('htmlListado'			,$htmlListado);
        }else{

                $arregloDatos[thisFunction]			='filtro';  
                $arregloDatos[plantilla]			='levanteReporteFiltro.html';
                $arregloDatos[mostrar]				=0;
                $htmlFiltro=$this->cargaPlantilla($arregloDatos);
                $this->plantilla->setVariable('filtroEntradaLevante'	,$htmlFiltro);
                
               

        }
        $this->plantilla->show();
    }  
    
    function filtro($arregloDatos,$unDatos,$plantilla)
    {
        $unaLista 	= new Inventario();
        $lista		= $unaLista->lista("tipos_remesas");
        $lista		= armaSelect($lista,'[seleccione]',NULL);
        $plantilla->setVariable("listaTiposRemesa", $lista);
        
        $unaLista 	= new Inventario();
        $lista		= $unaLista->lista("inventario_tipos_movimiento","2,3,5,7,8,9,10",'codigo');
        
        $lista		= armaSelect($lista,'[seleccione]',NULL);
        $plantilla->setVariable("listaTipos", $lista);
        
        
    
       
    }
	
  
    
    function validaCapturaDeclaraciones($arregloDatos,$unDatos,$plantilla)
    {
       // Se valida si se captura CIF y cantidad de declaraciones
        
        $arregloDatos[captura]              ="hidden"; 
        if($unDatos->cant_declaraciones==0){
            $arregloDatos[cant_declaraciones]	    ="";
            $arregloDatos[cant_declaraciones_aux]   ="";
            $arregloDatos[valor_aux]                ="";
            $arregloDatos[captura]                  ="text"; 
        }else{
            $arregloDatos[cant_declaraciones_aux]   =$unDatos->cant_declaraciones;
            $arregloDatos[valor_aux]                =$unDatos->valor;
        }
        $this->mantenerDatos($arregloDatos,$plantilla);
        
    }
    //Metodo que muestra la mercancia para Nacionalizacion
    function getMercancia($arregloDatos,$unDatos,$plantilla)
    {
       
        //se valida si se puede elejir mercancia nacional no nacional o ambas
        $this->validaCapturaDeclaraciones($arregloDatos,$unDatos,$plantilla);
        
        if($unDatos->cantidad_nonac <= 0)
        {
            $arregloDatos[mostrarBotonGuardar]='none';  // No deja Seleccionar mercancia
        }
       
         
        $arregloDatos[readonly]="";
        if($unDatos->cod_referencia==1) // Si es Bultos
        {
            $arregloDatos[cantidad_nonac]   =$arregloDatos[cant_bultos]-$unDatos->cantidad_naci;
            //saldo por nacionalizar
            $arregloDatos[cantidad]         =$arregloDatos[cant_bultos];
            $arregloDatos[valor]            =$unDatos->peso_naci+$unDatos->fob_nonac;
            $arregloDatos[readonly]         ="readonly=''";
            $arregloDatos[v_aux_nonac]      ="";
            $arregloDatos[fob_nonac]        ="";
            $arregloDatos[valor]            =$unDatos->fob_naci;
            //$arregloDatos[peso_naci]        =$arregloDatos[peso_declaraciones];
        }
        
        
        $arregloDatos[cant_declaraciones]=$arregloDatos[cant_declaraciones]+1;
        // se calcula la cantidad restante cuando son bultos y se cambia la cantidad por los bultos a nacionalizar
        $unaConsulta= new Levante();
        $unaConsulta->getSumaGrupo($arregloDatos);
        $unaConsulta->fetch();
        $arregloDatos[sum_cant_naci]= $unaConsulta->sum_cant_naci;
        if(empty($arregloDatos[sum_cant_naci])){$arregloDatos[sum_cant_naci]=0;}
        
        $this->setValores(&$arregloDatos,$unDatos,$plantilla);
        $this->mantenerDatos($arregloDatos,$plantilla);
    }
    
    function getformaCosteo($arregloDatos,$unDatos,$unaPlantilla)
   {
        $arregloDatos[mostrar]		=0;
        $arregloDatos[plantilla]	='levanteFormaCosteo.html';
        $arregloDatos[thisFunction]	='setFormaCosteo';
        $htmlFormaCosteo=$this->setFuncion($arregloDatos,&$this->datos);
	return $htmlFormaCosteo;
    }  
    
    function setFormaCosteo($arregloDatos,$unDatos,$unaPlantilla)
    {
        
    }

            
    function getCabezaLevante($arregloDatos,$unDatos,$unaPlantilla)
    {
     //var_dump($unDatos);
       $unaConsulta=new Levante();
       $arregloDatos[cuenta_grupos]=$unDatos->lev_cuenta_grupo;
       $unaConsulta->cuentaDeclaraciones(&$arregloDatos);
        // si hay mas de un parcial se muestra el numero
         $unaPlantilla->setVariable("parcial", $unDatos->lev_cuenta_grupo);
       if($unDatos->lev_cuenta_grupo > 1){
            //".$unDatos->lev_cuenta_grupo;
            $unaPlantilla->setVariable("parcial", $unDatos->lev_cuenta_grupo);
        }
        if($arregloDatos[parcial]==1){
          $unaPlantilla->setVariable("checked", "checked");  
        }
         
        if($unDatos->tip_movimiento==3)
        {
           $unaPlantilla->setVariable("mostrarBoton","none"  );
        }
        
        $unaPlantilla->setVariable("cant_declaraciones",$arregloDatos[cant_declaraciones] );
        $unaPlantilla->setVariable("peso_declaraciones",$arregloDatos[peso_declaraciones] );
        
    }
    
    function getCabezaProceso($arregloDatos,$unDatos,$unaPlantilla)
    {
     
        $arregloDatos[tabla]		='tipos_embalaje';
	$arregloDatos[labelLista]	='selectUnidad';
        $this->getLista($arregloDatos,trim($unDatos->unidad),$unaPlantilla);
        
        if($unDatos->cierre){
            $arregloDatos[checked]	='checked';
        }
       $this->mantenerDatos($arregloDatos,$unaPlantilla); 
    }
    
    function getCuerpoRetiro($arregloDatos,$unDatos,$unaPlantilla)
    {
       
        $this->getCuerpoLevante($arregloDatos,$unDatos,$unaPlantilla);
    }
    
        
    function getCuerpoLevante($arregloDatos,$unDatos,$unaPlantilla)
    {
        //var_dump($arregloDatos);
        // Contamos el numero de levantes
        $unConteo= new Levante();
        $unConteo->ultimoGrupo(&$arregloDatos);
        $unConteo->cuentaDeclaraciones(&$arregloDatos);
      
        if($unDatos->un_grupo >= 1){
             
            $arregloDatos[grupo_label]="[$unDatos->un_grupo] ";
        }else{
           $arregloDatos[grupo_label]=""; 
        }
        //$arregloDatos[grupo_label]="[$unDatos->un_grupo] ";
        $this->setValores(&$arregloDatos,$unDatos,$unaPlantilla);
        $arregloDatos[tipo_retiro_filtro]=$arregloDatos[tipo_retiro];
        if($arregloDatos[tipo_movimiento]<>3){
            $this->sePuedeBorrar(&$arregloDatos,$unDatos);
       }
        $this->mantenerDatos($arregloDatos,$unaPlantilla);

    }
    
   function sePuedeBorrar($arregloDatos,$unDatos){
    // Si ya hay retiros no se permite borrar el registro
      
       $unaConsulta=new Levante();
        //var_dump($unDatos);
        $arregloDatos[cod_item]=$unDatos->item;
        $unaConsulta->hayMovimientos($arregloDatos);
        $unaConsulta->fetch();
        
        $arregloDatos[label]="";
        //echo "xx".$arregloDatos[borrar];
        if($unaConsulta->tipo_movimiento/1 > 0)
        {
            $arregloDatos[label]  ="X";
            $arregloDatos[estilo] ="ui-state-highlight";
            $arregloDatos[mensaje]="ya hay mercancia en tipo de movimiento: $unaConsulta->movimiento ,no se permite eliminar registros para no alterarar el inventario. reverse este movimiento para poder modificar el movimiento ";
            
        }else{
             $arregloDatos[label] ="<a href='#' class='signup borrar_id' title='Quitar Levante {n}' id='$arregloDatos[n]' cursor><img src='integrado/imagenes/borrar.gif' width='15' height='15' border='1'  ></a>";  
            
        }    
   }
	
   function setValores($arregloDatos,$datos,$plantilla)
   { 
       
    
      $arregloDatos[cantidad_nonac]=number_format(abs($datos->cantidad_nonac),DECIMALES,".",""); // se formatea para evitar error de validacion javascript
      $arregloDatos[peso_nonac]    =number_format(abs($datos->peso_nonac),DECIMALES,".","");
      $arregloDatos[fob_nonac]     =number_format(abs($datos->fob_nonac),DECIMALES,".","");
     
       //  Variables de pesos cantidad y fob formateadas
       $arregloDatos[peso_naci_f]       =number_format(abs($datos->peso_naci),DECIMALES,".",",");
       $arregloDatos[peso_nonac_f]      =number_format(abs($datos->peso_nonac),DECIMALES,".",",");
       
       $arregloDatos[cant_naci_f]       =number_format(abs($datos->cantidad_naci),DECIMALES,".",",");
       $arregloDatos[cant_nonac_f]      =number_format(abs($datos->cantidad_nonac),DECIMALES,".",",");
       
       $arregloDatos[fob_naci_f]        =number_format(abs($datos->fob_naci),DECIMALES,".",",");
      
     
     
       $this->total_fob            =$this->total_fob+$datos->fob_nonac;
       $arregloDatos[total_fob]    =number_format(abs($this->total_fob),DECIMALES,".",",");
       $arregloDatos[fob_saldo_f]  =number_format(abs($fob),DECIMALES,".",",");
       $arregloDatos[fob_f]        =number_format(abs($datos->fob_nonac),DECIMALES,".",",");
       $arregloDatos[cif_f]        =number_format(abs($datos->cif),DECIMALES,".",",");
       
        //totales pesos formateados
     
        $this->tot_peso_nac    =$this->tot_peso_nac + abs($datos->peso_naci);
        $arregloDatos[tot_peso_nac]=$this->tot_peso_nac;
        $arregloDatos[tot_peso_nacf]=number_format($this->tot_peso_nac,DECIMALES,".",",")  ;
                                                             
        $this->tot_peso_nonac    =$this->tot_peso_nonac + abs($datos->peso_nonac);
        $arregloDatos[tot_peso_nonac]=$this->tot_peso_nonac ;
        $arregloDatos[tot_peso_nonacf]=number_format($this->tot_peso_nonac,DECIMALES,".",",")  ;
        
        // totales Fob formateados
        $this->tot_fob_nac    =$this->tot_fob_nac + abs($datos->fob_naci);
        $arregloDatos[tot_fob_nac]=number_format($this->tot_fob_nac,DECIMALES,".",",");
        
        $this->tot_fob_nonac    =$this->tot_fob_nonac + abs($datos->fob_nonac);
        $arregloDatos[tot_fob_nonac]=number_format($this->tot_fob_nonac,DECIMALES,".",",");
        
        $arregloDatos[fob_nonac_f]=number_format(abs($datos->fob_nonac),DECIMALES,".",",");
        
        //totales cantidades formateados
        $this->tot_cant_nac    =$this->tot_cant_nac + abs($datos->cantidad_naci);
        $arregloDatos[tot_cant_nac]=number_format($this->tot_cant_nac,DECIMALES,".",",");
        
        $this->tot_cant_nonac    =$this->tot_cant_nonac + abs($datos->cantidad_nonac);
        $arregloDatos[tot_cant_nonac]=number_format($this->tot_cant_nonac,DECIMALES,",",",");
        $arregloDatos[t_cant_nonac]  =$this->tot_cant_nonac;
        // Aqui se formatean las cifras y se muestra valor absoluto para el caso de retiros
        $arregloDatos[peso_f]       =number_format(abs($datos->peso_naci),DECIMALES,".",",");
        $arregloDatos[cantidad_f]   =number_format(abs($datos->cantidad_naci),DECIMALES,".",",");
        //$arregloDatos[fob_f]        =number_format(abs($datos->fob_naci),DECIMALES,".",",");
          // $arregloDatos[sn]           =" | [SN] ";
        //cif  formateados
        $this->tot_cif    =$this->tot_cif + $datos->cif;
        $arregloDatos[tot_cif]=number_format(abs($this->tot_cif),DECIMALES,".",",");
        
        
        if(empty($arregloDatos[tipo_retiro_filtro])){$arregloDatos[tipo_retiro_filtro]=$arregloDatos[tipo_retiro];}
        if(empty($arregloDatos[tipo_retiro_filtro])){$arregloDatos[tipo_retiro_filtro]=$arregloDatos[tipo_movimiento];}

        switch($arregloDatos[tipo_retiro_filtro] ) {
            case 1:
            case 0: 
                $arregloDatos[fob_saldo]     ="0";
                $arregloDatos[peso_nonac_f]  ="";
                $arregloDatos[cant_nonac_f]  ="";
                $arregloDatos[fob_nonac_f]   ="";   
                $arregloDatos[tot_peso_nonac]="";
                $arregloDatos[tot_cant_nonac]="";
                $arregloDatos[tot_fob_nonac] ="";
                $arregloDatos[fob_saldo_f] ="";
                $arregloDatos[total_fob] ="";
                $arregloDatos[fob_f] ="";
                $arregloDatos[ext] ="";
               
            break;
            case 2:// reexportacion
            case 8:// producto para Proceso
            case 9:// producto para Ensamble  
            case 7:// producto para Ensamble 
                $arregloDatos[sn]                   =" | [EXT] ";
                $arregloDatos[snt]                  =" | [EXT] ";
                $arregloDatos[sn_aux]               =" | [EXT] ";
                $arregloDatos[type_nonac]           ="text";
                $arregloDatos[cantidad_nonaci_aux]  =$datos->cantidad_nonaci;
                $arregloDatos[peso_nonaci_aux]      =$datos->peso_nonaci;
                $arregloDatos[fob_nonaci_aux]       =$datos->fob_nonaci;
                //$arregloDatos[type_nonac]           ="text";
                $arregloDatos[ext] ="/FOB";
            break;
            
        }
    }
    
    function listaInventario($arregloDatos,$datos,$plantilla)
    {  
        // se trae el grupo
        $this->setValores(&$arregloDatos,$datos,$plantilla);
        $this->mantenerDatos($arregloDatos,$plantilla);
    }
    
    
    function getInventario($arregloDatos,$datos,$plantilla)
    { 
       
        $this->setValores(&$arregloDatos,$datos,$plantilla);
         $this->mantenerDatos($arregloDatos,$plantilla);
    }
    
    function getParaCosteo($arregloDatos,$datos,$plantilla)
    { 
        
         $this->setValores(&$arregloDatos,$datos,$plantilla);
         $this->mantenerDatos($arregloDatos,$plantilla);
    }
    
    
    function getParaAdicionales($arregloDatos,$datos,$plantilla)
    { 
        
        $arregloDatos[tabla]		='embalajes';
	$arregloDatos[labelLista]	='listaEmbalajes';
	$this->getLista($arregloDatos,NULL,$plantilla);
         //$this->setValores(&$arregloDatos,$datos,$plantilla);
         $this->mantenerDatos($arregloDatos,$plantilla);
    }
    
    function getParaProceso($arregloDatos,$datos,$plantilla)
    {
        
        //$this->getMercancia($arregloDatos,$datos,$plantilla);// cuando es nacionalizacion
        $this->setValores(&$arregloDatos,$datos,$plantilla);
         $this->mantenerDatos($arregloDatos,$plantilla);
    }
    
    function getInvParaRetiro($arregloDatos,$datos,$plantilla)
    {  
   
       
        $arregloDatos[type_nonac]           ="hidden";
        $arregloDatos[cantidad_nonaci_aux]  ="0";
        $arregloDatos[peso_nonaci_aux]      ="0";
        $arregloDatos[fob_nonaci_aux]       ="0";
        //aqui se decide si se deja editar o no la mercancia sin nacionalizar;
        $this->setValores(&$arregloDatos,$datos,$plantilla);
        /*
        switch($arregloDatos[tipo_retiro] ) {
            case 1:
                     $arregloDatos[peso_nonac_f]           ="";
                     $arregloDatos[cant_nonac_f]           ="";
                     
                      $arregloDatos[tot_peso_nonac]="";
                      $arregloDatos[tot_cant_nonac]="";
                      $arregloDatos[tot_fob_nonac] ="";
            break;
            case 2:// reexportacion
                $arregloDatos[sn]           =" |  [SN]";
                $arregloDatos[snt]          =" |  [SN]";
                 
            break;
            
        }*/
        
        
        $this->mantenerDatos($arregloDatos,$plantilla);
    }
    
    
    function listarLevantes($arregloDatos,&$datos,&$plantilla)
    {       
        $plantilla->setVariable('img_editar'	,'layer--pencil.png');
        switch($datos->tipo_movimiento){  
          case 2:
              $arregloDatos[doc_placa]       =$datos->lev_documento;
              $arregloDatos[aduana_conductor]=$datos->nombre_aduana;
          break;
          case 3:  
          case 8:  
          case 9:
          case 7:
              $arregloDatos[doc_placa]=$datos->placa;
               $arregloDatos[aduana_conductor]=$datos->conductor_nombre;
          break;
        }
        
         $this->mantenerDatos($arregloDatos,$plantilla);
        
    }

    function getToolbar($arregloDatos,&$datos,&$plantilla)
    {

        $this->mantenerDatos($arregloDatos,$plantilla);
    }
    
    function traeLevante($arregloDatos,&$datos,&$plantilla)
    {

        $this->mantenerDatos($arregloDatos,$plantilla);
    }
    
    //Pinta el cuerpo del retiro
    function getRetiro($arregloDatos,&$datos,&$plantilla)
    {
       
        $arregloDatos[mostrar]		=0;
        $arregloDatos[plantilla]	='levanteRemesaCuerpo.html';
        $arregloDatos[thisFunction]	='getCuerpoRetiro';
        $arregloDatos[cuerpoRetiro]     = $this->setFuncion($arregloDatos,&$unDatos); 
        $this->mantenerDatos($arregloDatos,$plantilla);
    }
    
    //Pinta el cuerpo del retiro
    function matriz($arregloDatos,&$datos,&$plantilla)
    {
       
        $arregloDatos[mostrar]		=0;
        $arregloDatos[plantilla]	='levanteMatrizCuerpo.html';
        $arregloDatos[thisFunction]	='getCuerpoRetiro';
        $arregloDatos[cuerpoRetiro]     = $this->setFuncion($arregloDatos,&$unDatos); 
        $this->mantenerDatos($arregloDatos,$plantilla);
    }
    
    
        
    function getTitulo($arregloDatos)
    {
        
        if(empty($arregloDatos[doc_filtro])) {$arregloDatos[doc_filtro]=$arregloDatos[documento_filtro] ;}
        switch($arregloDatos[tipo_movimiento]) 
        {
            
            case 2:
                $arregloDatos[titulo]=" LEVANTE: ";
            break;
            case 3:
               
                $arregloDatos[titulo]=" RETIRO: $arregloDatos[tipo_retiro_label]";
            break;
            case 7:
               
                $arregloDatos[titulo]=" ALISTAMIENTO: $arregloDatos[tipo_retiro_label]";
            break;
            case 8:
               
                $arregloDatos[titulo]=" PROCESO: $arregloDatos[tipo_retiro_label]";
            break;
             case 9:
               
                $arregloDatos[titulo]=" ENSAMBLE: $arregloDatos[tipo_retiro_label]";
            break;
        }
         switch($arregloDatos[tipo_retiro]) 
        {
            case 2:
                $arregloDatos[titulo].=" REEX ";
            break;   
        }
        if(!empty($arregloDatos[por_cuenta_filtro])){
            $unLevante = new Levante();
            $unLevante->getCliente($arregloDatos);
            $unLevante->fetch();
            $arregloDatos[titulo].="[".$unLevante->numero_documento."]".$unLevante->razon_social;
        }
        if(!empty($arregloDatos[doc_filtro])){
            $arregloDatos[titulo].=", DOCUMENTO: $arregloDatos[doc_filtro]";
        }
        
        if(!empty($arregloDatos[orden_filtro])){
            $arregloDatos[titulo].=", ORDEN: $arregloDatos[orden_filtro]";
        }
        if(!empty($arregloDatos[arribo_filtro])){
            $arregloDatos[titulo].=", ARRIBO: $arregloDatos[arribo_filtro]";
        }
	
    } 
  }


?>