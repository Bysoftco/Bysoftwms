<?php
require_once("LevanteDatos.php");
require_once("LevantePresentacion.php");
require_once("InventarioDatos.php");
require_once("OrdenDatos.php");
require_once("InventarioDatos.php");
require_once("ReporteExcel.php");

class LevanteLogica  
{
    var $datos;
    var $pantalla;
		
    function LevanteLogica () 
    {
        $this->datos = &new Levante();
	$this->pantalla = &new LevantePresentacion($this->datos);
    }
    function nacionalizar($arregloDatos){
         $arregloDatos[tab_seleccionado]  =0;  
        $arregloDatos[plantillaFiltro]="levanteFiltroNacionalizar.html";
        $this->pantalla->maestro($arregloDatos);
    }
    function retiros($arregloDatos){
        $arregloDatos[tab_seleccionado]  =0;  
        $arregloDatos[plantillaFiltro]="levanteFiltroRetiro.html";
        $this->pantalla->maestro($arregloDatos); 
    }
    
    function proceso($arregloDatos){
       
        $arregloDatos[tab_seleccionado]  =0;  
        $arregloDatos[plantillaFiltro]="levanteFiltroProceso.html";
        $this->pantalla->maestro($arregloDatos); 
    }	
  
    
    function controlarTransaccion($arregloDatos) 
    {
     //var_dump($arregloDatos); 
//Aqui se deside el tipo de Formulario Para el movimiento Seleccionado
       // El metodo y plantilla  se condicionan por el formulario levanteFiltro segun seleccion del combo transaccion 
       $arregloDatos[type_nonac]      ="text";
       $arregloDatos[v_aux_nonac]     ="";
       $arregloDatos[v_aux_nac]       =""; 
        switch(trim($arregloDatos[tipo_movimiento]))
        {
          
          case 2: // Nacionalizacion
             
              $arregloDatos[movimiento]               ="1,2,30,3,7";
             
              $arregloDatos[plantillaMercanciaCuerpo] ="levanteListadoMercancia.html";
              $arregloDatos[metodoMercanciaCuerpo]    ="listaInventario";
              //$arregloDatos[metodoMercanciaCuerpo]    ="getInvParaProceso";
               
              $arregloDatos[plantillaCabeza]          ="levanteCabeza.html";
              $arregloDatos[metodoCabeza]             ="getCabezaLevante";
              $arregloDatos[metodoCabezaEnvia]        ="updateLevanteCabeza";
              //Metodos para enviar formulario despues Cuerpo
              $arregloDatos[setMetodo]                ="addItemLevante";
              $arregloDatos[type_nac]                 ="hidden"; // No deja selecionar piezas  nacionales
              $arregloDatos[v_aux_nac]                ="0";
              
              $arregloDatos[plantillaCuerpo]          ="levanteCuerpo.html";
              $arregloDatos[metodoCuerpo]             ="getCuerpoLevante";
              //Metodos para enviar formulario despues Cabeza
          break;
          case 3: // Retiro
          case 7: // Alistamiento
            
              $arregloDatos[movimiento]               ="1,2,30,3,7"; 
              //$arregloDatos[having]                   =" peso_naci > 0  OR  peso_nonac > 0";
              $arregloDatos[plantillaMercanciaCuerpo] ="levanteListadoMercanciaRetiro.html";
              $arregloDatos[metodoMercanciaCuerpo]    ="getInvParaRetiro";
              
              $arregloDatos[plantillaCabeza]          ="levanteCabezaRetiro.html";
              $arregloDatos[metodoCabeza]             ="getCabezaLevante";
              $arregloDatos[metodoCabezaEnvia]        ="updateRetiroCabeza";
              
               $arregloDatos[plantillaCuerpo]         ="levanteCuerpoRetiro.html";
               $arregloDatos[metodoCuerpo]            ="getCuerpoRetiro";
               //Metodos para enviar formulario despues
              $arregloDatos[setMetodo]                ="addItemRetiro";
              $arregloDatos[type_nonac]               ="hidden"; // No deja selecionar piezas  nacionales
              $arregloDatos[v_aux_nonac]              ="0";
              
          break;
      
          case 8: //Envia Productos en Proceso
              $arregloDatos[movimiento]               ="1,2,30,3,7"; 
              //$arregloDatos[having]                   =" peso_naci > 0  OR  peso_nonac > 0";
              $arregloDatos[plantillaMercanciaCuerpo] ="levanteListadoMercanciaRetiro.html";
              $arregloDatos[metodoMercanciaCuerpo]    ="getInvParaRetiro";
              
              $arregloDatos[plantillaCabeza]          ="levanteCabezaRetiro.html";
              $arregloDatos[metodoCabeza]             ="getCabezaLevante";
              $arregloDatos[metodoCabezaEnvia]        ="updateRetiroCabeza";
              
              $arregloDatos[plantillaCuerpo]         ="levanteCuerpoRetiro.html";
              $arregloDatos[metodoCuerpo]            ="getCuerpoRetiro";
              //Metodos para enviar formulario despues
              $arregloDatos[setMetodo]                ="addItemRetiro";
              $arregloDatos[type_nonac]               ="hidden"; // No deja selecionar piezas  nacionales
              $arregloDatos[v_aux_nonac]              ="0";
          break;
          case 9: // Producto Ensamblado
              $arregloDatos[movimiento]               ="8,9";
              //$arregloDatos[having]                   =" peso_naci > 0  OR  peso_nonac > 0";
              $arregloDatos[plantillaMercanciaCuerpo] ="levanteListadoMercanciaRetiro.html";
              $arregloDatos[metodoMercanciaCuerpo]    ="getParaProceso";
              
              $arregloDatos[plantillaCabeza]          ="levanteCabezaProceso.html";
              $arregloDatos[metodoCabeza]             ="getCabezaProceso";
              $arregloDatos[metodoCabezaEnvia]        ="updateProcesoCabeza";
              
              $arregloDatos[plantillaCuerpo]          ="levanteCuerpoRetiro.html";
              $arregloDatos[metodoCuerpo]             ="getCuerpoRetiro";
              //Metodos para enviar formulario despues
              $arregloDatos[setMetodo]                ="addItemEnsamble";
              
              
          break;
        }
        
    }
    
    
    function consultaLevante($arregloDatos) 
    {
        $arregloDatos[cliente]=$arregloDatos[por_cuenta_filtro];
        $this->controlarTransaccion(&$arregloDatos);
        $this->pantalla->maestro($arregloDatos);
    }
    
    function newLevante($arregloDatos) 
    {
	$arregloDatos[cliente]=$arregloDatos[por_cuenta_filtro];
        $arregloDatos[tipo_movimiento]=2;
        
        $this->datos->getLevante($arregloDatos);  // por documeto hay un solo movimiento
        if($this->datos->N > 0) { // Si ya existe el levante
            $this->datos->fetch();
            $arregloDatos[id_levante]=$this->datos->codigo;
            $arregloDatos[tab_seleccionado]  =0; 
        }else{
            $arregloDatos[id_levante]=$this->datos->newLevante(&$arregloDatos);
            $arregloDatos[tab_seleccionado]  =1; 
        }
        
        $this->controlarTransaccion(&$arregloDatos);
        $this->pantalla->maestro($arregloDatos);
    }
    
    function newProceso($arregloDatos) 
    {
 
        $arregloDatos[tipo_movimiento]   =9;
        $arregloDatos[id_levante]=$this->datos->newLevante(&$arregloDatos);// siempre se crea un nuevo movimiento
        $arregloDatos[tab_seleccionado]  =1; 
        $this->controlarTransaccion(&$arregloDatos);
        $this->pantalla->maestro($arregloDatos);
    }
    
      function impresion($arregloDatos) 
    {
	
        $arregloDatos[mostrar]          =1;
	$arregloDatos[plantilla]	='levanteRemesaRetiro.html';
	$arregloDatos[thisFunction]	='getRetiro'; 
	$this->pantalla->setFuncion($arregloDatos,&$this->datos);
    }
    
       function matriz($arregloDatos) 
    {
	
        $arregloDatos[mostrar]          =1;
	$arregloDatos[plantilla]	='levanteMatrizCabeza.html';
	$arregloDatos[thisFunction]	='matriz'; 
	$this->pantalla->setFuncion($arregloDatos,&$this->datos);
    }
    
     function newRetiro($arregloDatos) 
    {
         //var_dump($arregloDatos);
         switch($arregloDatos[tipo_retiro]){
            case 1:
            case 2:   // Retiro Normal
                $arregloDatos[tipo_movimiento]   =3;
            break;
            case 7: //  Alistamiento
                $arregloDatos[tipo_movimiento]   =7;
                $arregloDatos[tipo_retiro_filtro]=8;// para que muestre extrangero
                   
            break;
           
            case 8: //  proceso
                $arregloDatos[tipo_movimiento]   =8;
                $arregloDatos[tipo_retiro_filtro]=8;// para que muestre extrangero
                //var_dump($arregloDatos);    
            break;
        }
        
        $arregloDatos[id_levante]=$this->datos->newLevante(&$arregloDatos);// siempre se crea un nuevo retiro
        $arregloDatos[tab_seleccionado]  =1; 
       
        $this->controlarTransaccion(&$arregloDatos);
        $this->pantalla->maestro($arregloDatos);
    }
    
    // Funcion que al enviar el menu Consulta muestra todos los movimientos
     function verRetiro($arregloDatos) 
    {
        //$arregloDatos[tipo_retiro]=$arregloDatos[tipo_movimiento];// getInvParaRetiro requiere el parametro para listar mercancia
        $arregloDatos[tab_seleccionado]  =1; 
        $this->controlarTransaccion(&$arregloDatos);
        $this->pantalla->maestro($arregloDatos);
    }


    
     function getLevante($arregloDatos) 
    {
        //$arregloDatos[metodo]		='consulta';
	$arregloDatos[plantilla]	='levanteFormaConsulta.html';
	$arregloDatos[thisFunction]	='traeLevante'; 
	$this->pantalla->setFuncion($arregloDatos,&$this->datos);
		
    }
    
    function getItem($arregloDatos) 
    {
	//var_dump($arregloDatos);
        //$unLevante = new Inventario();
        //$arregloDatos[ubicaciones]      =implode(";",$unLevante->selectUbicacion($arregloDatos));
        $arregloDatos[id_movimiento]=$arregloDatos[id_levante];
        $arregloDatos[id_form]=$arregloDatos[id_form]/1;
	if(empty($arregloDatos[mostrar])){$arregloDatos[mostrar]=1;}
        $this->controlarTransaccion(&$arregloDatos);
        $arregloDatos[metodo]		='consulta';
	$arregloDatos[plantilla]	='levanteForma.html';
	$arregloDatos[thisFunction]	='getMercancia'; 
        $this->pantalla->setFuncion($arregloDatos,&$this->datos);
    }
    
    
    
    function getItemRetiro($arregloDatos) 
    {
        //var_dump($arregloDatos);// 2 nacional
        $unLevante = new Inventario();
        $arregloDatos[ubicaciones]      =implode(";",$unLevante->selectUbicacion($arregloDatos));
        $arregloDatos[id_form]=$arregloDatos[id_form]/1;
	if(empty($arregloDatos[mostrar])){$arregloDatos[mostrar]=1;}
        $this->controlarTransaccion(&$arregloDatos);
        $arregloDatos[metodo]		='consulta';
	$arregloDatos[plantilla]	='levanteFormaRetiro.html';
	$arregloDatos[thisFunction]	='getInvParaRetiro'; 
	$this->pantalla->setFuncion($arregloDatos,&$this->datos);
    }
    
   // Funcion que trae el item de mercancia para proceso seleccionado
    function getItemProceso($arregloDatos) 
    {
        
        $unLevante = new Inventario();
        $arregloDatos[ubicaciones]      =implode(";",$unLevante->selectUbicacion($arregloDatos));
        $arregloDatos[id_form]=$arregloDatos[id_form]/1;
	if(empty($arregloDatos[mostrar])){$arregloDatos[mostrar]=1;}
        $this->controlarTransaccion(&$arregloDatos);
        $arregloDatos[metodo]		='consulta';
	$arregloDatos[plantilla]	='levanteFormaRetiro.html';
	$arregloDatos[thisFunction]	='getParaProceso'; 
	$this->pantalla->setFuncion($arregloDatos,&$this->datos);
    }
		
    function addItemAdicional($arregloDatos) 
    {
         $unaConsulta= new Levante();
        //Se inserta el Adicional en la tabla de inventario_entradas
         $this->datos->getParaInsertar(&$arregloDatos);
         $this->datos->addItemInventario($arregloDatos); // Se agrega registro en la tabla de inventario_entrada
         $arregloDatos[id_item]= $unaConsulta->getConsecutivo($arregloDatos);  // Se agrega el Movimiento
         $this->datos->addItemAdicional($arregloDatos);  // Se agrega el Movimiento
        
        
         //  Se lista el cuerpo del retiro se debe enviar $arregloDatos[id_levante]el id del maestro
        $arregloDatos[id_levante]       =$arregloDatos[id_levante_adicional];
        $arregloDatos[mostrar]          =1;
        $arregloDatos[id_item]          =NULL;
        $arregloDatos[plantilla]        ='levanteCuerpoRetiro.html';
	$arregloDatos[thisFunction]     ='getCuerpoRetiro';
        $this->pantalla->setFuncion($arregloDatos,&$this->datos);
        //inventario_entradas (arribo,orden,fecha,referencia,posicion,un_empaque) VALUES (new.arribo,new.orden,CURDATE(),1,1,1);
         
    }
    
    function addItemLevante($arregloDatos) 
    {
       $arregloDatos[tipo_movimiento]=2;
        // $arregloDatos[id_levante]=$this->datos->newLevante($arregloDatos);
       // $this->datos->newLevante(&$arregloDatos);
        $this->controlarTransaccion(&$arregloDatos);
          
        $this->datos->newDeclaracion($arregloDatos);
        $this->datos->addItemLevante(&$arregloDatos);
        $this->datos->getAcomulaCif($arregloDatos);
        //$this->getItem($arregloDatos);
        
        $this->getMercancia($arregloDatos); 
         
    }
    
   // Agrega un Producto a Proceso
    function addItemProceso($arregloDatos) 
    {
       //var_dump($arregloDatos);
        $arregloDatos[num_levante]=$arregloDatos[id_levante];
        if(empty($arregloDatos[tipo_retiro])){
            $arregloDatos[tipo_movimiento]  =3;
        }else{
             $arregloDatos[tipo_movimiento]  =$arregloDatos[tipo_retiro];
        }
        $arregloDatos[tipo_movimiento]  =30;
        $this->datos->addItemRetiro($arregloDatos);
        $arregloDatos[tipo_movimiento]  =8;
        $this->datos->addItemProceso($arregloDatos);
        $arregloDatos[mostrar]          =1;
        $arregloDatos[id_item]          =NULL;
        $this->controlarTransaccion(&$arregloDatos);
        $arregloDatos[plantilla]        ='levanteListadoMercanciaRetiro.html';
	$arregloDatos[thisFunction]     ='getInvParaRetiro';
        $this->pantalla->setFuncion($arregloDatos,&$this->datos);
        
    }
    
     // Agrega un Producto a Proceso
    function addItemEnsamble($arregloDatos) 
    {
       //var_dump($arregloDatos);
        $this->controlarTransaccion(&$arregloDatos);
        $arregloDatos[tipo_movimiento]  =9;
        $this->datos->addItemRetiro($arregloDatos);
      
        $arregloDatos[mostrar]          =1;
        $arregloDatos[id_item]          =NULL;
        $arregloDatos[plantilla]        ='levanteListadoMercanciaRetiro.html';
	$arregloDatos[thisFunction]     ='getParaProceso';
        $this->pantalla->setFuncion($arregloDatos,&$this->datos);
        
    }
    
    
    function addItemRetiro($arregloDatos) 
    {
       //var_dump($arregloDatos);
        if(empty($arregloDatos[tipo_retiro])){
            //$arregloDatos[tipo_movimiento]  =3;
        }else{
             //$arregloDatos[tipo_movimiento]  =$arregloDatos[tipo_retiro];
        }
        $this->controlarTransaccion(&$arregloDatos);
        //$arregloDatos[tipo_movimiento]  =3;
        $this->datos->addItemRetiro($arregloDatos);
        $arregloDatos[mostrar]          =1;
        $arregloDatos[id_item]          =NULL;
        $arregloDatos[plantilla]        ='levanteListadoMercanciaRetiro.html';
	$arregloDatos[thisFunction]     ='getInvParaRetiro';
        $this->pantalla->setFuncion($arregloDatos,&$this->datos);
        
    }
    
    
    
      function getCuerpoLevante($arregloDatos) 
    {
      //var_dump($arregloDatos);
       
        $arregloDatos[id_consulta_levante]= $arregloDatos[id_levante];
        // Contamos el numero de levantes
        $unConteo= new Levante();
        $unConteo->ultimoGrupo(&$arregloDatos);
        $unConteo->cuentaDeclaraciones(&$arregloDatos);
       
       //$arregloDatos[mensaje]="";
        $this->controlarTransaccion(&$arregloDatos);
        //var_dump($arregloDatos);
        $arregloDatos[plantilla]    =$arregloDatos[plantillaCuerpo];
	//$arregloDatos[thisFunction] ='getCuerpoLevante';// No es Fijo porque se usa para varios movimientos
         $arregloDatos[thisFunction] = $arregloDatos[metodoCuerpo] ;
        $this->pantalla->setFuncion($arregloDatos,&$this->datos);
		
    }
     // Funcion Encargada de Pintar el Cuerpo de inventario para retiro
    function getCuerpoRetiro($arregloDatos) 
    {
        $this->controlarTransaccion(&$arregloDatos);
        $arregloDatos[mostrar]		=1;
        $arregloDatos[plantilla]        ='levanteListadoMercanciaRetiro.html';
	$arregloDatos[thisFunction]     ='getInvParaRetiro';
        $this->pantalla->setFuncion($arregloDatos,&$this->datos);
		
    }
    
     // Funcion Encargada de Pintar el Cuerpo de inventario para retiro
    function getCuerpoRetiroProceso($arregloDatos) 
    {
        
        $this->controlarTransaccion(&$arregloDatos); 
        $arregloDatos[mostrar]		=1;
        $arregloDatos[plantilla]        ='levanteListadoMercanciaRetiro.html';
	$arregloDatos[thisFunction]     ='getParaProceso';
        $this->pantalla->setFuncion($arregloDatos,&$this->datos);
		
    }
    
    // Funcion Encargada de Pintar el listado pendiente de costear
    function getParaCosteo($arregloDatos) 
    {
        $arregloDatos[mostrar]		=1;
        $arregloDatos[plantilla]        ='levanteListadoCosteo.html';
	$arregloDatos[thisFunction]     ='getInventario';
        $this->pantalla->setFuncion($arregloDatos,&$this->datos);
		
    }
    
     // Funcion Encargada de Pintar el formulario Para adicionales
    function getParaAdicionales($arregloDatos) 
    {
       
        $arregloDatos[mostrar]		=1;
        $arregloDatos[plantilla]        ='levanteFormaAdicionales.html';
	$arregloDatos[thisFunctionAux]  ='getParaAdicionales';
        $arregloDatos[thisFunction]     ='getParaAdicionales';
        $this->pantalla->setFuncion($arregloDatos,&$this->datos);
		
    }
    
    function getCabezaLevante($arregloDatos) 
    {
        
       
        $this->controlarTransaccion(&$arregloDatos);
        $arregloDatos[plantilla]    =$arregloDatos[plantillaCabeza];
	$arregloDatos[thisFunction] =$arregloDatos[metodoCabeza];
	$this->pantalla->setFuncion($arregloDatos,&$this->datos);
		
    }
    
     function remesaRetiro($arregloDatos) 
    {
         
        $arregloDatos[plantilla]    ='levanteRemesaRetiro.html';
	$arregloDatos[thisFunction] ='getCuerpoLevante';
        $this->pantalla->setFuncion($arregloDatos,&$this->datos);
		
    }
    
    function getMercancia($arregloDatos) 
    {
        $this->controlarTransaccion(&$arregloDatos); 
        $arregloDatos[mostrar]				=1;
        $arregloDatos[plantilla]    ='levanteListadoMercancia.html';
	$arregloDatos[thisFunction] ='listaInventario';  
	$this->pantalla->setFuncion($arregloDatos,&$this->datos);
		
    }
    
    function updateLevanteCabeza($arregloDatos) 
    {
        //Se Confirma si es parcial para aumentar el grupo
        if($arregloDatos[parcial]){
          //$this->datos->setConteoParciales($arregloDatos);
           $arregloDatos[num_grupo]=  $this->datos->ultimoGrupoCreado($arregloDatos)+1;
           $this->datos->updateConteoParciales($arregloDatos);
        }else{// si no marco como parcial se hace de nuevo el conteo y se actualiza
            $gruposcreados=$this->datos->ultimoGrupoCreado($arregloDatos) ;
            $grupossolicitados=$this->datos->ultimoGrupo($arregloDatos) ;
           
            if(grupossolicitados > $gruposcreados){ // se decidio no crear el parcial
                $arregloDatos[num_grupo]=$gruposcreados; // se actualiza el numero real de grupos
                $this->datos->updateConteoParciales($arregloDatos); 
            }
        }
        $arregloDatos[mostrar]=1;
        $this->datos->setCabezaLevante(&$arregloDatos);
        $arregloDatos[plantilla]    ='levanteCabeza.html';
	$arregloDatos[thisFunction] ='getCabezaLevante';  
	$this->pantalla->setFuncion($arregloDatos,&$this->datos);

    }
    
     function updateRetiroCabeza($arregloDatos) 
    {
        if($arregloDatos[nuevo_estado]){
            $this->datos->cambiaMovimientoCabeza($arregloDatos);
            $this->datos->cambiaMovimientoCuerpo($arregloDatos);
        }
        $this->controlarTransaccion(&$arregloDatos); 
        $arregloDatos[mostrar]=1;
        $this->datos->setCabezaLevante(&$arregloDatos);
        $arregloDatos[plantilla]    ='levanteCabezaRetiro.html';
	$arregloDatos[thisFunction] ='getCabezaLevante';  
	$this->pantalla->setFuncion($arregloDatos,&$this->datos);
		
    }
    
    
    
     function updateProcesoCabeza($arregloDatos) 
    {
        //var_dump($arregloDatos);
         if($arregloDatos[cierre]){  // Se procede a la creacion de la nueva Orden
            $arregloDatos[bodega]=1;
            $unaOrden = new Orden();
            $unItem   = new Inventario();
            $arregloDatos[tipo_documento]   ='REM';   // tipo de documento remision
            $arregloDatos[tipo_operacion]   ='2';   // tipo de operacion  REINGRESO P-TERMINADOS
            $arregloDatos[do_asignado]      =$unaOrden->getConsecutivo($arregloDatos);
            if(!$unaOrden->addOrden($arregloDatos)) {
                $unaOrden->addArribo($arregloDatos);
                $arregloDatos[arribo]       =$this->datos->getConsecutivo($arregloDatos);
                // Se ajustan variables para la tabla inventario entrada
                $arregloDatos[peso_bruto]   =$arregloDatos[peso_ext];
                $unaOrden->updateArribo($arregloDatos);
                //Se actualiza la tabla de movimientos con las cantiades de ext y nac como , se obtiene el ID de inventario_entradas
                $arregloDatos[inventario_entrada]       =$this->datos->getIdInventario($arregloDatos);
                $arregloDatos[id_item]=$arregloDatos[inventario_entrada];
                $arregloDatos[peso]=$arregloDatos[tot_peso_nonac]+$arregloDatos[tot_peso_nac];
                $unItem->saveItem($arregloDatos);  // Se actualiza la Tabla Inventario Entarda
                $this->datos->updateMovimiento($arregloDatos);
            }
            
        }

        $this->controlarTransaccion(&$arregloDatos); 
        $arregloDatos[mostrar]=1;
        $arregloDatos[sia]=$arregloDatos[por_cuenta];
        $arregloDatos[producto]=$arregloDatos[referencia];
        $this->datos->setCabezaLevante(&$arregloDatos);
        $arregloDatos[plantilla]    ='levanteCabezaProceso.html';
	$arregloDatos[thisFunction] ='getCabezaProceso';  
	$this->pantalla->setFuncion($arregloDatos,&$this->datos);
        
		
    }
    
	
    function delMercanciaLevante($arregloDatos) 
    {
        
        $this->controlarTransaccion(&$arregloDatos); 
        $this->datos->delMercanciaLevante($arregloDatos);
        //grupo_borrado se verifica si se borro todo el parcial de un grupo para restar 1
        $unaConsulta = new Levante();
        $unaConsulta->getConteoParciales($arregloDatos);
       
        if($unaConsulta->N==0){
            $unaConsulta->menosConteoParciales($arregloDatos); // resta al conteo de parciales
        }
        
        $arregloDatos[plantilla]    ='levanteCuerpo.html';
	$arregloDatos[thisFunction] ='getCuerpoLevante';  
	$this->pantalla->setFuncion($arregloDatos,&$this->datos);
		
    }
    
      function delMercancia($arregloDatos) 
    {
        $this->controlarTransaccion(&$arregloDatos); 
        $this->datos->delMercancia($arregloDatos);
        $arregloDatos[plantilla]    ='levanteCuerpoRetiro.html';
	$arregloDatos[thisFunction] ='getCuerpoRetiro';  
	$this->pantalla->setFuncion($arregloDatos,&$this->datos);
		
    }
    
    function existeCliente($arregloDatos) 
    {
        $unaConsulta= new Levante();
        $unaConsulta->existeCliente($arregloDatos);
        if($unaConsulta->N==0){
                echo $unaConsulta->N;
                die();
        }
       $unaConsulta->existeLevante($arregloDatos);

       if($unaConsulta->N > 0){
             echo 10;
             die();
       }
        echo 1;
    }
    
    function existeLevante($arregloDatos) 
    {
        $unaConsulta= new Levante();
        $unaConsulta->existeLevante($arregloDatos);
        echo $unaConsulta->N;
            
    }
    
    function titulo($arregloDatos) 
    {
        $unDato = new Levante();
        $titulo='';

        if(!empty($arregloDatos['por_cuenta_filtro'])){

                $arregloDatos[por_cuenta]=$arregloDatos[por_cuenta_filtro];
                $unDato->existeCliente($arregloDatos);
                $unDato->fetch();
                $titulo=$unDato->razon_social;
        }

        if(!empty($arregloDatos[ubicacion_filtro])){
                $titulo.=" ubicaciòn ".$unDato->dato('do_bodegas','codigo',$arregloDatos[ubicacion_filtro]);
        }

        if(!empty($arregloDatos[estado_filtro])){
                $titulo.=" estado ".$unDato->dato('do_estados','codigo',$arregloDatos[estado_filtro]);
        }

        if(!empty($arregloDatos[fecha_inicio]) and !empty($arregloDatos[fecha_fin])){
                $titulo.=" desde ".$arregloDatos[fecha_inicio]." hasta ".$arregloDatos[fecha_fin];
        }
        if(!empty($arregloDatos[doc_filtro])){
                $titulo.=" documento ".$arregloDatos[doc_filtro];
        }

        if(!empty($arregloDatos[do_filtro])){
                $titulo.=" Do ".$arregloDatos[do_filtro];
        }


        return ucwords(strtolower($titulo));
    }

    
    function maestroConsulta($arregloDatos) 
    {
			
        $arregloDatos['titulo']=$this->titulo($arregloDatos);
        $arregloDatos['metodoAux']='maestroConsulta';
        $this->pantalla->maestroConsulta($arregloDatos);
    }
    
       function imprimeLevante($arregloDatos) 
    {
			
   
        $arregloDatos[mostrar]=1;
        $arregloDatos[plantilla]    ='levanteRemesaRetiro.html';
	$arregloDatos[thisFunction] ='listaInventario';  
	$this->pantalla->setFuncion($arregloDatos,&$this->datos);
    }
    
    function  setCosteo($arregloDatos) 
    {
        $fobs=$arregloDatos[fob];
        foreach ($arregloDatos[item] as $key => $value) {
            $arregloDatos[fob]      =$fobs[$key];
            $arregloDatos[id_item]  =$value;
            $this->datos->setCosteo($arregloDatos);
           
        }
      
      //var_dump($arregloDatos);
      
    }
    
    function  findConductor($arregloDatos) {

        $unaConsulta= new Levante();
        $unaConsulta->findConductor($arregloDatos);
        $arregloDatos[q] = strtolower($_GET["q"]);
        //if (!$arregloDatos[q]) return;
        header( 'Content-type: text/html; charset=iso-8859-1' );
        while ($unaConsulta->fetch()) {
                $nombre='['.trim($unaConsulta->codigo).']'.trim($unaConsulta->nombre);
                echo "$nombre|$unaConsulta->codigo|$unaConsulta->nombre\n";
        }
        if($unaConsulta->N==0) {
                echo "No hay Resultados|0\n";
        }

    }
    
    function  findDocumento($arregloDatos) 
    {
	
        $unaConsulta= new Levante();
        $unaConsulta->findDocumento($arregloDatos);
        $arregloDatos[q] = strtolower($_GET["q"]);
        header( 'Content-type: text/html; charset=iso-8859-1' );
        while ($unaConsulta->fetch()) {
                $nombre=trim($unaConsulta->doc_tte)."-".trim($unaConsulta->do_asignado);
                echo "$nombre|$unaConsulta->doc_tte\n";
        }
        if($unaConsulta->N==0) {
                echo "No hay Resultados|0\n";
        }
			
    }
		
 }		
  	
?>