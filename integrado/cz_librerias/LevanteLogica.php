<?php
require_once("LevanteDatos.php");
require_once("LevantePresentacion.php");
require_once("InventarioDatos.php");
require_once("OrdenDatos.php");
require_once("ReporteExcel.php");
require_once("OrdenLogica.php");

class LevanteLogica {
  var $datos;
  var $pantalla;
		
  function LevanteLogica() {
    $this->datos =& new Levante();
    $this->pantalla =& new LevantePresentacion($this->datos);
    //VERSION 20032017
  }
  
  function nacionalizar($arregloDatos) {
    $arregloDatos[tab_seleccionado] = 0;  
    $arregloDatos[plantillaFiltro] = "levanteFiltroNacionalizar.html";
    $this->pantalla->maestro($arregloDatos);
  }
  
  function retiros($arregloDatos) {
    $arregloDatos[required] = "required";
    $arregloDatos[metodo_aux] = "newRetiro";
    $arregloDatos[tab_seleccionado] = 0;  
    $arregloDatos[plantillaFiltro] = "levanteFiltroRetiro.html";
    $this->pantalla->maestro($arregloDatos); 
  }
  
  function pedidoFiltro($arregloDatos) {
    $arregloDatos[required] = "";
    $arregloDatos[mostrarFiltroRetiro] = "none";
    $arregloDatos[metodo_aux] = "newPedido";
    $arregloDatos[tab_seleccionado] = 0;  
    $arregloDatos[plantillaFiltro] = "levanteFiltroRetiro.html";
    $this->pantalla->maestro($arregloDatos); 
  }
  
  function proceso($arregloDatos) {
    $arregloDatos[tab_seleccionado] = 0;  
    $arregloDatos[plantillaFiltro] = "levanteFiltroProceso.html";
    $this->pantalla->maestro($arregloDatos); 
  }
  
  function controlarTransaccion(&$arregloDatos) {
  //var_dump($arregloDatos);
    //Aqui se decide el tipo de Formulario Para el movimiento Seleccionado
    //El método y plantilla  se condicionan por el formulario levanteFiltro según selección del combo transacción 
    $arregloDatos[type_nonac] = "text";
    $arregloDatos[v_aux_nonac] = "";
    $arregloDatos[v_aux_nac] = ""; 

    switch(trim($arregloDatos[tipo_movimiento])) {
      case 2: // Nacionalizacion
        $arregloDatos[movimiento] = "1,2,3,7,10,15,16,19,30";
        $arregloDatos[plantillaMercanciaCuerpo] = "levanteListadoMercancia.html";
        $arregloDatos[metodoMercanciaCuerpo] = "listaInventario";

        $arregloDatos[plantillaCabeza] = "levanteCabeza.html";
        $arregloDatos[metodoCabeza] = "getCabezaLevante";
        $arregloDatos[metodoCabezaEnvia] = "updateLevanteCabeza";
        //Metodos para enviar formulario despues Cuerpo
        $arregloDatos[setMetodo] = "addItemLevante";
        $arregloDatos[type_nac] = "hidden"; // No deja selecionar piezas  nacionales
        $arregloDatos[v_aux_nac] = "0";
        
        $arregloDatos[plantillaCuerpo] = "levanteCuerpo.html";
        $arregloDatos[metodoCuerpo] = "getCuerpoLevante";
        //Metodos para enviar formulario despues Cabeza
        break;
      case 3: // Retiro
      case 7: // Alistamiento
        $arregloDatos[movimiento] = "1,2,3,7,10,15,16,19,30"; 
        $arregloDatos[plantillaMercanciaCuerpo] = "levanteListadoMercanciaRetiro.html";
        $arregloDatos[metodoMercanciaCuerpo] = "getInvParaRetiro";
        
        $arregloDatos[plantillaCabeza] = "levanteCabezaRetiro.html";
        $arregloDatos[metodoCabeza] = "getCabezaLevante";
        $arregloDatos[metodoCabezaEnvia] = "updateRetiroCabeza";
        
        $arregloDatos[plantillaCuerpo] = "levanteCuerpoRetiro.html";
        $arregloDatos[metodoCuerpo] = "getCuerpoRetiro";
        //Metodos para enviar formulario despues
        $arregloDatos[setMetodo] = "addItemRetiro";
        $arregloDatos[type_nonac] = "hidden"; // No deja selecionar piezas  nacionales
        $arregloDatos[v_aux_nonac] = "0";
        break;
      case 8: //Envia Productos en Proceso
        $arregloDatos[movimiento] = "1,2,3,7,10,15,16,19,30"; 
        $arregloDatos[plantillaMercanciaCuerpo] = "levanteListadoMercanciaRetiro.html";
        $arregloDatos[metodoMercanciaCuerpo] = "getInvParaRetiro";
        
        $arregloDatos[plantillaCabeza] = "levanteCabezaRetiro.html";
        $arregloDatos[metodoCabeza] = "getCabezaLevante";
        $arregloDatos[metodoCabezaEnvia] = "updateRetiroCabeza";
        
        $arregloDatos[plantillaCuerpo] = "levanteCuerpoRetiro.html";
        $arregloDatos[metodoCuerpo] = "getCuerpoRetiro";
        //Metodos para enviar formulario después
        $arregloDatos[setMetodo] = "addItemRetiro";
        $arregloDatos[type_nonac] = "hidden"; // No deja selecionar piezas  nacionales
        $arregloDatos[v_aux_nonac] = "0";
        break;
      case 13: //Enndoso
        $arregloDatos[movimiento] = "1,2,3,7,10,15,16,19,30";
        $arregloDatos[plantillaMercanciaCuerpo] = "levanteListadoMercanciaRetiro.html";
        $arregloDatos[metodoMercanciaCuerpo] = "getInvParaRetiro";
        
        $arregloDatos[plantillaCabeza] = "levanteCabezaEndoso.html";
        $arregloDatos[metodoCabeza] = "getCabezaLevante";
        $arregloDatos[metodoCabezaEnvia] = "updateRetiroCabeza";
        
        $arregloDatos[plantillaCuerpo] = "levanteCuerpoRetiro.html";
        $arregloDatos[metodoCuerpo] = "getCuerpoRetiro";
        //Metodos para enviar formulario despues
        $arregloDatos[setMetodo] = "addItemRetiro";
        $arregloDatos[type_nonac] = "hidden"; // No deja selecionar piezas  nacionales
        $arregloDatos[v_aux_nonac] = "0";
        break;
      case 9: // Producto Ensamblado
        $arregloDatos[verRetiroRapido]='none';	
        $arregloDatos[movimiento] = "8,9";
        $arregloDatos[plantillaMercanciaCuerpo] = "levanteListadoMercanciaRetiro.html";
        $arregloDatos[metodoMercanciaCuerpo] = "getParaProceso";
        
        $arregloDatos[plantillaCabeza] = "levanteCabezaProceso.html";
        $arregloDatos[metodoCabeza] = "getCabezaProceso";
        $arregloDatos[metodoCabezaEnvia] = "updateProcesoCabeza";
        
        $arregloDatos[plantillaCuerpo] = "levanteCuerpoRetiro.html";
        $arregloDatos[metodoCuerpo] = "getCuerpoRetiro";
        //Metodos para enviar formulario despues
        $arregloDatos[setMetodo] = "addItemEnsamble";
        break;
		 case 16: // Retiro de Alistamientos
		 case 18:
		 	$arregloDatos[movimiento] = "16,18"; 
        	$arregloDatos[plantillaMercanciaCuerpo] = "levanteListadoMercanciaRetiro.html";
        	$arregloDatos[metodoMercanciaCuerpo] = "getInvParaRetiro";
			
        
        	$arregloDatos[plantillaCabeza] = "levanteCabezaRetiro.html";
        	$arregloDatos[metodoCabeza] = "getCabezaLevante";
        	$arregloDatos[metodoCabezaEnvia] = "updateRetiroCabeza";
        
        	$arregloDatos[plantillaCuerpo] = "levanteCuerpoRetiro.html";
        	$arregloDatos[metodoCuerpo] = "getCuerpoRetiro";
        	//Metodos para enviar formulario despues
        	$arregloDatos[setMetodo] = "addItemRetiro";
        	$arregloDatos[type_nonac] = "hidden"; // No deja selecionar piezas  nacionales
        	$arregloDatos[v_aux_nonac] = "0";
		break;
		 case 17: // Retiro de Rechazados
		 	//echo "XXXX".$arregloDatos[tipo_movimiento];die();
		 	$arregloDatos[movimiento] = "16,17,18"; 
        	$arregloDatos[plantillaMercanciaCuerpo] = "levanteListadoMercanciaRetiro.html";
        	$arregloDatos[metodoMercanciaCuerpo] = "getInvParaRetiro";
			
        
        	$arregloDatos[plantillaCabeza] = "levanteCabezaRetiro.html";
        	$arregloDatos[metodoCabeza] = "getCabezaLevante";
        	$arregloDatos[metodoCabezaEnvia] = "updateRetiroCabeza";
        
        	$arregloDatos[plantillaCuerpo] = "levanteCuerpoRetiro.html";
        	$arregloDatos[metodoCuerpo] = "getCuerpoRetiro";
        	//Metodos para enviar formulario despues
        	$arregloDatos[setMetodo] = "addItemRetiro";
        	$arregloDatos[type_nonac] = "hidden"; // No deja selecionar piezas  nacionales
        	$arregloDatos[v_aux_nonac] = "0";
		break;
      default: // Cualquier movimiento que no este definido se presenta como retiro
        $arregloDatos[movimiento] = "1,2,3,7,10,15,16,19,30"; 
        $arregloDatos[plantillaMercanciaCuerpo] = "levanteListadoMercanciaRetiro.html";
        $arregloDatos[metodoMercanciaCuerpo] = "getInvParaRetiro";
        
        $arregloDatos[plantillaCabeza] = "levanteCabezaRetiro.html";
        $arregloDatos[metodoCabeza] = "getCabezaLevante";
        $arregloDatos[metodoCabezaEnvia] = "updateRetiroCabeza";
        
        $arregloDatos[plantillaCuerpo] = "levanteCuerpoRetiro.html";
        $arregloDatos[metodoCuerpo] = "getCuerpoRetiro";
        //Metodos para enviar formulario despues
        $arregloDatos[setMetodo] = "addItemRetiro";
        $arregloDatos[type_nonac] = "hidden"; // No deja selecionar piezas  nacionales
        $arregloDatos[v_aux_nonac] = "0";
    }
  }
  
  function consultaLevante($arregloDatos) {
    $arregloDatos[cliente] = $arregloDatos[por_cuenta_filtro];
    $this->controlarTransaccion($arregloDatos);
    $this->pantalla->maestro($arregloDatos);
  }
  
  function newLevante($arregloDatos) {
   //var_dump($arregloDatos);
   
    $arregloDatos[cliente] = $arregloDatos[por_cuenta_filtro];
    $arregloDatos[tipo_movimiento] = 2;
    
    $this->datos->getLevante($arregloDatos);  // por documento hay un solo movimiento
    if($this->datos->N > 0) { // Si ya existe el levante
      $this->datos->fetch();
      $arregloDatos[id_levante] = $this->datos->codigo;
      $arregloDatos[tab_seleccionado] = 0; 
    } else {
      $arregloDatos[id_levante] = $this->datos->newLevante($arregloDatos);
      $arregloDatos[tab_seleccionado] = 1; 
    }
    
    $this->controlarTransaccion($arregloDatos);
    $this->pantalla->maestro($arregloDatos);
  }
  
  function newProceso($arregloDatos) {
    $arregloDatos[tipo_movimiento] = 9;
    $arregloDatos[id_levante] = $this->datos->newLevante($arregloDatos);// siempre se crea un nuevo movimiento
    $arregloDatos[tab_seleccionado] = 1; 
    $this->controlarTransaccion($arregloDatos);
    $this->pantalla->maestro($arregloDatos);
  }
  
  function impresion($arregloDatos) {
    //Configura fecha y hora de impresión
    $arregloDatos[fecha_impresion] = date('m-d-Y');
    $hora = getdate(time());
    $horas = strlen($hora["hours"]) == 1 ? '0'.$hora["hours"] : $hora["hours"];
    $minutos = strlen($hora["minutes"]) == 1 ? '0'.$hora["minutes"] : $hora["minutes"];
    $arregloDatos[hora_impresion] = $horas.":".$minutos;
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'levanteRemesaRetiro.html';
    $arregloDatos[thisFunction] = 'getRetiro';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function getMatrizCambio($arregloDatos) {
    $arregloDatos[thisFunctionAux] = 'getMatrizCambio';
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'levanteMatrizCambio.html';
    $arregloDatos[thisFunction] = 'getMatrizCambio';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function setCambio($arregloDatos) {
    $this->datos->setCambio($arregloDatos);
  }
  
  function matriz($arregloDatos) {
    switch($arregloDatos[tipo_movimiento]) {
      case 3: // here matriz de integración
        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = 'levanteMatrizCabezaRetiro.html';
        $arregloDatos[thisFunction] = 'matrizRetiroCabeza'; 
        $this->pantalla->setFuncion($arregloDatos,$this->datos);
        break;
      case 9:
        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = 'levanteMatrizCabeza.html';
        $arregloDatos[thisFunction] = 'matriz';
        $this->pantalla->setFuncion($arregloDatos,$this->datos);
        break;
    }
  }
  
  function newPedido($arregloDatos) {
    $arregloDatos[tipo_retiro] = 7;
    $arregloDatos[tab_seleccionado] = 1;
    
    switch($arregloDatos[tipo_retiro]) {
      case 1:
      case 2: // Retiro Normal
        $arregloDatos[tipo_movimiento] = 3;
        $arregloDatos[tab_seleccionado] = 0;
        break;
      case 7: //  Alistamiento
        $arregloDatos[tipo_movimiento] = 7;
        $arregloDatos[tipo_retiro_filtro] = 8;// para que muestre extrangero
        break;
      case 8: //  proceso
        $arregloDatos[tipo_movimiento] = 8;
        $arregloDatos[tipo_retiro_filtro] = 8;// para que muestre extrangero
        break;
    }
    
    $arregloDatos[id_levante] = $this->datos->newLevante($arregloDatos);// siempre se crea un nuevo retiro
    $this->controlarTransaccion($arregloDatos);
    $this->pantalla->maestro($arregloDatos);
  }
  
  function newRetiro($arregloDatos) {
    $arregloDatos[tab_seleccionado] = 1;
    
    switch($arregloDatos[tipo_retiro]) {
      case 1:
      case 2: // Retiro Normal
      case 11: // Retiro Matriz
        $arregloDatos[tipo_movimiento] = 3;
        $arregloDatos[tab_seleccionado] = 0;
        break;
      case 7: // Alist
        $arregloDatos[tipo_movimiento] = 7;
        $arregloDatos[tipo_retiro_filtro] = 8;// para que muestre extrangero
        break;
      case 13: // Endoso
        $arregloDatos[tipo_movimiento] = 13;
        $arregloDatos[tab_seleccionado] = 0;
        break;
      case 8: // procesogetToolbar //Salida para Proceso a Transformar
        $arregloDatos[tipo_movimiento] = 8;
        $arregloDatos[tipo_retiro_filtro] = 8;// para que muestre extrangero
        break;
		case 17: // procesogetToolbar //Salida para Proceso a Transformar
        $arregloDatos[tipo_movimiento] = 17;
        $arregloDatos[tipo_retiro_filtro] = 8;// para que muestre extrangero
        break;
		
		case 15:
        $arregloDatos[tipo_movimiento] = 15;
        
        break;
		
		case 16:
        $arregloDatos[tipo_movimiento] = 16;
        $arregloDatos[tipo_retiro_filtro] = 8;// para que muestre extrangero
        break;
    }
    
    $arregloDatos[id_levante] = $this->datos->newLevante($arregloDatos);// siempre se crea un nuevo retiro
    $this->controlarTransaccion($arregloDatos);
    $this->pantalla->maestro($arregloDatos);
  }
  
  // Función que al enviar el menu Consulta muestra todos los movimientos
  function verRetiro($arregloDatos) {
  //var_dump($arregloDatos);
    $arregloDatos[tab_seleccionado] = 1;
    $this->controlarTransaccion($arregloDatos);
    $this->pantalla->maestro($arregloDatos);
  }
  
  function getLevante($arregloDatos) {
    $arregloDatos[plantilla] = 'levanteFormaConsulta.html';
    $arregloDatos[thisFunction]	= 'traeLevante';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function getItem($arregloDatos) {
  //var_dump($arregloDatos);
  
    $arregloDatos[id_movimiento] = $arregloDatos[id_levante];
    $arregloDatos[id_form] = $arregloDatos[id_form]/1;
    
    if(empty($arregloDatos[mostrar])) { $arregloDatos[mostrar] = 1; }
    $this->controlarTransaccion($arregloDatos);
    $arregloDatos[metodo] = 'consulta';
    $arregloDatos[plantilla] = 'levanteForma.html';
    $arregloDatos[thisFunction] = 'getMercancia';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function getItemRetiro($arregloDatos) {
  //var_dump($arregloDatos);
    $unLevante = new Inventario();
    $arregloDatos[ubicaciones] = implode(";",$unLevante->selectUbicacion($arregloDatos));
    $arregloDatos[id_form] = (int) $arregloDatos[id_form];
    
    if(empty($arregloDatos[mostrar])) { $arregloDatos[mostrar] = 1; }
    $this->controlarTransaccion($arregloDatos);
    $arregloDatos[metodo] = 'consulta';
    $arregloDatos[plantilla] = 'levanteFormaRetiro.html';
    $arregloDatos[thisFunction] = 'getInvParaRetiro';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  // Función que trae el item de mercancia para proceso seleccionado
  function getItemProceso($arregloDatos) {
    $unLevante = new Inventario();
    $arregloDatos[ubicaciones] = implode(";",$unLevante->selectUbicacion($arregloDatos));
    $arregloDatos[id_form] = $arregloDatos[id_form]/1;
    if(empty($arregloDatos[mostrar])) { $arregloDatos[mostrar] = 1; }
    $this->controlarTransaccion($arregloDatos);
    $arregloDatos[metodo] = 'consulta';
    $arregloDatos[plantilla] = 'levanteFormaRetiro.html';
    $arregloDatos[thisFunction] = 'getParaProceso';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function addItemAdicional($arregloDatos) {
    $unaConsulta = new Levante();
    //Se inserta el Adicional en la tabla de inventario_entradas
    $this->datos->getParaInsertar($arregloDatos);
    // se verifica si se debe agregar como nacional o como extranjero
    if($arregloDatos[tipo] == 1){// Nacional
		  $arregloDatos[peso_ext_para] = 0;
    	$arregloDatos[cantidad_ext_para] = 0;
    	$arregloDatos[fob] = 0;
    	$this->datos->addItemInventario($arregloDatos); // Se agrega registro en la tabla de inventario_entrada
    	$arregloDatos[id_item] = $unaConsulta->getConsecutivo($arregloDatos);  // Se agrega el Movimiento
    	$this->datos->addItemAdicional($arregloDatos);  // Se agrega el Movimiento
    } else { // Extranjero
      $arregloDatos[peso_ext_para] = $arregloDatos[peso_naci_para];
    	$arregloDatos[cantidad_ext_para] = $arregloDatos[cantidad_naci_para];
    	$arregloDatos[fob] = $arregloDatos[cif];
      $arregloDatos[peso_naci_aux] = 0;
      $arregloDatos[peso_naci_para] = 0;
      $arregloDatos[cantidad_naci_para] = 0;
      $arregloDatos[cif] = 0;		
    	$this->datos->addItemInventario($arregloDatos); // Se agrega registro en la tabla de inventario_entrada
    	$arregloDatos[id_item] = $unaConsulta->getConsecutivo($arregloDatos);  // Se agrega el Movimiento
    	$this->datos->addItemAdicional($arregloDatos);  // Se agrega el Movimiento
    }
    // Se lista el cuerpo del retiro se debe enviar $arregloDatos[id_levante] el id del maestro
    $arregloDatos[id_levante] = $arregloDatos[id_levante_adicional];
    $arregloDatos[mostrar] = 1;
    $arregloDatos[id_item] = NULL;
    $arregloDatos[plantilla] = 'levanteCuerpoRetiro.html';
    $arregloDatos[thisFunction] = 'getCuerpoRetiro';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function addItemAjuste($arregloDatos) {
    $arregloDatos[producto_adicional] = 4;  //referencia por default
    $unaConsulta = new Levante();
    
	if(empty($arregloDatos[cantidad_ajuste])){
		$arregloDatos[cantidad_ajuste]=0;
	}
    //Se inserta el Adicional en la tabla de inventario_entradas
    $this->datos->getParaInsertar($arregloDatos);
   
    //determina si inserta el registro como nacional o como extranjero
    if($arregloDatos[tipo_ajuste] == 24) {
      $arregloDatos[peso_naci_para] = $arregloDatos[peso_ajuste];
      $arregloDatos[cantidad_naci_para] = $arregloDatos[cantidad_ajuste];
      $arregloDatos[cif] = $arregloDatos[valor];
      $arregloDatos[peso_ext_para] = 0;
      $arregloDatos[cantidad_ext_para] = 0;
      $arregloDatos[fob] = 0;
    } else {
      $arregloDatos[peso_naci_para] = 0;
      $arregloDatos[cantidad_naci_para] = 0;
      $arregloDatos[cif] = 0;
      $arregloDatos[peso_ext_para] = $arregloDatos[peso_ajuste];
      $arregloDatos[cantidad_ext_para] = $arregloDatos[cantidad_ajuste];
      $arregloDatos[fob] = $arregloDatos[valor];
      $arregloDatos[peso_naci_aux] = 0;
    }
    $this->datos->addItemInventario($arregloDatos); // Se agrega registro en la tabla de inventario_entrada
    $arregloDatos[id_item] = $unaConsulta->getConsecutivo($arregloDatos);  // Se agrega el Movimiento
    $this->datos->addItemAdicional($arregloDatos);  // Se agrega el Movimiento
    
    // Se lista el cuerpo del retiro se debe enviar $arregloDatos[id_levante]el id del maestro
    $arregloDatos[id_levante] = $arregloDatos[id_levante_adicional];
    $arregloDatos[mostrar] = 1;
    $arregloDatos[id_item] = NULL;
    $arregloDatos[plantilla] = 'levanteCuerpoRetiro.html';
    $arregloDatos[thisFunction] = 'getCuerpoRetiro';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function addItemLevante($arregloDatos) {
    $arregloDatos[tipo_movimiento] = 2;
    $this->controlarTransaccion($arregloDatos);
    
    $this->datos->newDeclaracion($arregloDatos);
    $this->datos->addItemLevante($arregloDatos);
    $this->datos->getAcomulaCif($arregloDatos);
    
    $this->getMercancia($arregloDatos); 
  }
  
  // Agrega un Producto a Proceso,No mostrar detalle de los ensamblados
  function addItemProceso($arregloDatos) {
    $arregloDatos[num_levante] = $arregloDatos[id_levante];
    if(empty($arregloDatos[tipo_retiro])) {
      $arregloDatos[tipo_movimiento] = 3;
    } else {
      $arregloDatos[tipo_movimiento] = $arregloDatos[tipo_retiro];
    }
    $arregloDatos[tipo_movimiento] = 30;
    $this->datos->addItemRetiro($arregloDatos);
    $arregloDatos[tipo_movimiento] = 8;
    $this->datos->addItemProceso($arregloDatos);
    $arregloDatos[mostrar] = 1;
    $arregloDatos[id_item] = NULL;
    $this->controlarTransaccion($arregloDatos);
    unset($arregloDatos[orden]);
    $arregloDatos[plantilla] = 'levanteListadoMercanciaRetiro.html';
    $arregloDatos[thisFunction] = 'getInvParaRetiro';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  // Agrega un Producto a Proceso
  function addItemEnsamble($arregloDatos) {
    $this->controlarTransaccion($arregloDatos);
    $arregloDatos[tipo_movimiento] = 9;
    $this->datos->addItemRetiro($arregloDatos);
	
   
    unset($arregloDatos[orden]);
    $arregloDatos[mostrar] = 1;
    $arregloDatos[id_item] = NULL;
    $arregloDatos[plantilla] = 'levanteListadoMercanciaRetiro.html';
    $arregloDatos[thisFunction] = 'getParaProceso';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function addItemRetiro($arregloDatos) {
 //var_dump($arregloDatos);
    // Si es endoso se procede a obtener el do anterior
    if($arregloDatos[tipo_movimiento] == 13) {
      $this->datos->addIDOAnt($arregloDatos);
      $this->datos->updateDoc($arregloDatos);
    }
    $this->controlarTransaccion($arregloDatos);
	
	switch($arregloDatos[tipo_movimiento]) {
		  case 16:
		  	
		  		$arregloDatos[tipo_movimiento]='18';
		  		$this->datos->addItemRetiroAcondicionamiento($arregloDatos); 
				//echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
				//$arregloDatos[tipo_movimiento]='18';
				//$this->datos->addItemRetiro($arregloDatos); 
				
		  break;
		  case 17:
		  	$this->datos->addItemRetiroAlistamiento($arregloDatos);
			//$arregloDatos[tipo_movimiento]='18';
			//$this->datos->addItemRetiro($arregloDatos); // no hay necesidad porque la opracion 17 no esta en los  select
		  break;
		  default:
		  	$this->datos->addItemRetiro($arregloDatos);
         break;
	}
	
    /*
      Inmediatamente despues de hacer el retiro, se verifica si el registro  queda o no con inventario
      si no queda con inventario se RETIRAN LAS UBICACIONES AUTOMATICAMETE 15052017/
    */
    $verificaInventario = new Levante();

	$arregloDatos[having] = " HAVING peso_nonac  > 0 OR peso_naci > 0 ";
	$arregloDatos[where] .=" AND  ie.orden='$arregloDatos[orden]'"; // filtro por referencia
	$arregloDatos[GroupBy] = "orden";  // 
	$arregloDatos[movimiento] = "1,2,3,7,10,15,30"; 
		
	
	if($arregloDatos[tipo_movimiento]==15){
		$arregloDatos[movimiento] = "1,2,3,7,10,15,16,30"; 
	}
		
	if($arregloDatos[tipo_movimiento]==17){
		$arregloDatos[movimiento] = "16,17"; 
	}
	
	if($arregloDatos[tipo_movimiento]==16){
		
		$arregloDatos[movimiento] = "16,18"; // aqui pegar nueva operacion que resta
	}
	if($arregloDatos[tipo_movimiento]==18){
		
		$arregloDatos[movimiento] = "16,18"; // aqui pegar nueva operacion que resta
	}
	
    $verificaInventario->getInvParaProceso($arregloDatos);
    $verificaInventario->fetch();
    $cantidad_retirada_naci=$verificaInventario->cantidad_naci; //-$arregloDatos[cantidad_naci_para];
    $cantidad_retirada_ext=$verificaInventario->cantidad_nonac; //-$arregloDatos[cantidad_nonaci_para];
	$arregloDatos[cantidad_retirada_naci]=$verificaInventario->cantidad_naci;
	$arregloDatos[cantidad_retirada_ext]=$verificaInventario->cantidad_nonac;
	 $unRetiroUbicacion = new Levante();
     

    //if($cantidad_retirada_naci <=0 && $cantidad_retirada_ext <=0) {
	if($verificaInventario->N==0) {
		$unRetiroUbicacion->borrarUbicacionesInventario($arregloDatos); 
    }
    unset($arregloDatos[cod_ref]);
    unset($arregloDatos[orden_retiro]);
    // Genera el DO Asignado Full
    if(empty($arregloDatos[orden])) {
      $arregloDatos[orden]=$arregloDatos[do_asignado_aux];
    }
    $arregloDatos[do_asignado_full] = $this->datos->sigla($arregloDatos)."-".$arregloDatos[orden];
    $arregloDatos[por_cuenta] = $arregloDatos[por_cuenta_filtro];
    $arregloDatos[do_asignado] = $arregloDatos[orden];
	
    // Genera valores positivos para Cantidad, Peso y CIF
    if($arregloDatos[cantidad_naci_para] != 0) $arreglo[cantidad] = abs($arregloDatos[cantidad_naci_para]);
    if($arregloDatos[cantidad_nonaci_para] != 0) $arreglo[cantidad] = abs($arregloDatos[cantidad_nonaci_para]);
    if($arregloDatos[peso_naci_para] != 0) $arreglo[peso] = abs($arregloDatos[peso_naci_para]);
    if($arregloDatos[peso_nonaci_para] != 0) $arreglo[peso] = abs($arregloDatos[peso_nonaci_para]);
    if($arregloDatos[cif_ret] != 0) $arreglo[valor] = abs($arregloDatos[cif_ret]);
    if($arregloDatos[fob_ret] != 0) $arreglo[valor] = abs($arregloDatos[fob_ret]);
    // Determina formatos de visualización
    $arregloDatos[cantidad] = number_format($arreglo[cantidad],2);
    $arregloDatos[peso] = number_format($arreglo[peso],2);
    $arregloDatos[valor] = number_format($arreglo[valor],2);
    // **********************************************************************************************
    //Una vez registrado el Retiro en la tabla inventario_movimientos se envía el email de Tracking.
    $arregloDatos[plantilla_mail] = "mailTrackingRetiro.html";
    $arregloDatos[asunto_mail] = "Retiro ".$arregloDatos[tipo_retiro_label]." para el DO: ".$arregloDatos[do_asignado_full];
    $this->envioMail($arregloDatos);
    // **********************************************************************************************
    if($arregloDatos[aplicaMatriz]) {
      $this->addItemMatriz($arregloDatos);
    }
    if($arregloDatos[tipo_movimiento] == 13) {// es endoso, DEBE QUEDAR CON SIGNO NEGATIVO
      $arregloDatos[tipo_movimiento] = 30;
      $this->datos->addItemRetiro($arregloDatos); //linea para probar
    }
    unset($arregloDatos[orden]);
	unset($arregloDatos[where]);
    $arregloDatos[mostrar] = 1;
    $arregloDatos[id_item] = NULL;
    $arregloDatos[plantilla] = 'levanteListadoMercanciaRetiro.html';
    $arregloDatos[thisFunction] = 'getInvParaRetiro';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function addItemMatriz($arregloDatos) {
    $unaAccion= new Levante();
    
    $arregloDatos[tipo_movimiento] = 11;
    $cod_maestro = $arregloDatos[cod_maestro_pro];
    $peso_ext_pro = $arregloDatos[peso_ext_pro];
    $cant_nal_pro = $arregloDatos[cant_nal_pro];
    $peso_nal_pro = $arregloDatos[peso_nal_pro];
    $fob_ret = $arregloDatos[fob_ret_pro];
    $cif_ret = $arregloDatos[cif_ret_pro];
    $id_entrada = $arregloDatos[id_entrada];
    
    foreach($arregloDatos[cant_nonac_pro] as $key => $value) {
      $arregloDatos[cod_maestro] = $cod_maestro[$key];
      $arregloDatos[id_entrada] = $id_entrada[$key];
      $arregloDatos[cant_ext] = $value;
      $arregloDatos[peso_ext_pro] = $peso_ext_pro[$key];
      $arregloDatos[cant_nal_pro] = $cant_nal_pro[$key];
      $arregloDatos[peso_nal_pro] = $peso_nal_pro[$key];
      $arregloDatos[fob_ret] = $fob_ret[$key];
      $arregloDatos[cif_ret] = $cif_ret[$key];
      $arregloDatos[cif_pro] = 0;
      $arregloDatos[fob_pro] = 0;
      
      $unaAccion->addItemMatriz($arregloDatos);
    }
  }
  
  function getCuerpoLevante($arregloDatos) {
    $arregloDatos[id_consulta_levante] = $arregloDatos[id_levante];
    // Contamos el número de levantes
    $unConteo = new Levante();
    $unConteo->ultimoGrupo($arregloDatos);
    $unConteo->cuentaDeclaraciones($arregloDatos);
    
    $this->controlarTransaccion($arregloDatos);
    $arregloDatos[plantilla] = $arregloDatos[plantillaCuerpo];
    $arregloDatos[thisFunction] = $arregloDatos[metodoCuerpo] ;
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  // Función Encargada de Pintar el Cuerpo de inventario para retiro
  function getCuerpoRetiro($arregloDatos) {
    $this->controlarTransaccion($arregloDatos);
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'levanteListadoMercanciaRetiro.html';
    $arregloDatos[thisFunction] = 'getInvParaRetiro';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  // Función Encargada de Pintar el Cuerpo de inventario para retiro
  function getCuerpoRetiroProceso($arregloDatos) {
    $this->controlarTransaccion($arregloDatos);
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'levanteListadoMercanciaRetiro.html';
    $arregloDatos[thisFunction] = 'getParaProceso';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  // Función Encargada de Pintar el listado pendiente de costear
  function getParaCosteo($arregloDatos) {
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'levanteListadoCosteo.html';
    $arregloDatos[thisFunction] = 'getInventario';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  // Función Encargada de Pintar el formulario Para adicionales
  function getParaAdicionales($arregloDatos) {
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'levanteFormaAdicionales.html';
    $arregloDatos[thisFunctionAux] = 'getParaAdicionales';
    $arregloDatos[thisFunction] = 'getParaAdicionales';
    
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  // Función Encargada de Pintar el formulario Para adicionales
  function getParaAjustes($arregloDatos) {
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'levanteFormaAjustes.html';
    $arregloDatos[thisFunctionAux] = 'getParaAdicionales';
    $arregloDatos[thisFunction] = 'getParaAdicionales';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function getCabezaLevante($arregloDatos) {
    $this->controlarTransaccion($arregloDatos);
    $arregloDatos[plantilla] = $arregloDatos[plantillaCabeza];
    $arregloDatos[thisFunction] = $arregloDatos[metodoCabeza];
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function remesaRetiro($arregloDatos) {
    $arregloDatos[plantilla] = 'levanteRemesaRetiro.html';
    $arregloDatos[thisFunction] = 'getCuerpoLevante';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function getMercancia($arregloDatos) {
    $this->controlarTransaccion($arregloDatos);
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'levanteListadoMercancia.html';
    $arregloDatos[thisFunction] = 'listaInventario';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

  function getMercanciaBusquedaReferencia($arregloDatos) {
    $this->controlarTransaccion($arregloDatos);
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = $arregloDatos[plantillaMercanciaCuerpo];
    $arregloDatos[thisFunction] = $arregloDatos[metodoMercanciaCuerpo];
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

  function retiroDirecto($arregloDatos) {
    $arregloDatos[do_asignado_aux] = '0000000';
    $arregloDatos[por_cuenta] = '0000000';
    $arregloDatos[doc_tte] = '0000000';
    $arregloDatos[remite] = '0000000';
	
    $this->addItemRetiro ($arregloDatos);
  }
  
  function updateLevanteCabeza($arregloDatos) {
    //Se Confirma si es parcial para aumentar el grupo
    if($arregloDatos[parcial]) {
      $arregloDatos[num_grupo] = $this->datos->ultimoGrupoCreado($arregloDatos) + 1;
      $this->datos->updateConteoParciales($arregloDatos);
    } else { // si no marco como parcial se hace de nuevo el conteo y se actualiza
      $gruposcreados = $this->datos->ultimoGrupoCreado($arregloDatos);
      $grupossolicitados = $this->datos->ultimoGrupo($arregloDatos);
      if(grupossolicitados > $gruposcreados) { // se decidio no crear el parcial
        $arregloDatos[num_grupo] = $gruposcreados; // se actualiza el numero real de grupos
        $this->datos->updateConteoParciales($arregloDatos);
      }
    }
    $arregloDatos[mostrar] = 1;
    $this->datos->setCabezaLevante($arregloDatos);
    $arregloDatos[plantilla] = 'levanteCabeza.html';
    $arregloDatos[thisFunction] = 'getCabezaLevante';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function updateRetiroCabeza($arregloDatos) {
    $arregloDatos[plantilla] = 'levanteCabezaRetiro.html';
    if($arregloDatos[tipo_retiro_m] == 13) {
      if($arregloDatos[cierre]) {
        $arregloDatos[por_cuenta] = $arregloDatos[destinatario];
        // se procede  a hacer el movimiento y crear el nuevo DO
        $unaOrden = new Orden();
        $unItem = new Inventario();
        $arregloDatos[tipo_documento] = 'REM';   // tipo de documento remision
        $arregloDatos[tipo_operacion] = '13';   // tipo de operacion  ENDOSO
        $do_asignado = $unaOrden->getConsecutivo($arregloDatos);
        $arregloDatos[do_asignado] = $do_asignado[0];
        if(!$unaOrden->addOrden($arregloDatos)) {
          $unaOrden->addArribo($arregloDatos);
          $arregloDatos[arribo] = $this->datos->getConsecutivo($arregloDatos);
          // se trae inventario entrada
          $this->datos->getDatosInventario($arregloDatos);
          $this->datos->updateDatosEndoso($arregloDatos); // se actualiza el arribo
          $arregloDatos[id_item] = $this->datos->getIdInventario($arregloDatos);// se obtiene el ID de inventario entrada
          $unItem->saveItem($arregloDatos);  // Se actualiza la Tabla Inventario Entrada
        }
      }//fin
      $arregloDatos[plantilla] = 'levanteCabezaEndoso.html';
    }
    // si tipo_retiro_m = 13 es un endoso
    if($arregloDatos[nuevo_estado]) {
      $this->datos->cambiaMovimientoCabeza($arregloDatos);
      $this->datos->cambiaMovimientoCuerpo($arregloDatos);
    }
    $this->controlarTransaccion($arregloDatos);
    $arregloDatos[mostrar] = 1;
    $this->datos->setCabezaLevante($arregloDatos);
    $arregloDatos[thisFunction] = 'getCabezaLevante';
    $this->datos->setCabezaLevante($arregloDatos);
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function updateProcesoCabeza($arregloDatos) {
    if($arregloDatos[cierre]) {  // Se procede a la creacion de la nueva Orden
      $arregloDatos[bodega] = 1;
      $unaOrden = new Orden();
      $unItem = new Inventario();
      $arregloDatos[tipo_documento] = 'REM';   // tipo de documento remision
      $arregloDatos[tipo_operacion]= '2';   // tipo de operacion  REINGRESO P-TERMINADOS
      $do_asignado = $unaOrden->getConsecutivo($arregloDatos);
      $arregloDatos[do_asignado] = $do_asignado[0];
      
      if(!$unaOrden->addOrden($arregloDatos)) {
        $unaOrden->addArribo($arregloDatos);
        $arregloDatos[arribo] = $this->datos->getConsecutivo($arregloDatos);
        // Se ajustan variables para la tabla inventario entrada
        $arregloDatos[peso_bruto] = $arregloDatos[peso_ext];
        $unaOrden->updateArribo($arregloDatos);
        //Se actualiza la tabla de movimientos con las cantiades de ext y nac como , se obtiene el ID de inventario_entradas
        $arregloDatos[inventario_entrada] = $this->datos->getIdInventario($arregloDatos);
        $arregloDatos[id_item] = $arregloDatos[inventario_entrada];
        $arregloDatos[peso] = $arregloDatos[tot_peso_nonac] + $arregloDatos[tot_peso_nac];
        $unItem->saveItem($arregloDatos);  // Se actualiza la Tabla Inventario Entarda
        $this->datos->updateMovimiento($arregloDatos);
        //$arregloDatos[tipo_movimiento] = 30; // Retiro comodin
        //$this->datos->addItemRetiro($arregloDatos); 16/11/2016 no se requiere hacer el ajuste ya que cada componente del producto hace su retiro
      }
    }
    $this->controlarTransaccion($arregloDatos);
    $arregloDatos[mostrar] = 1;
    $arregloDatos[sia] = $arregloDatos[por_cuenta];
    $arregloDatos[producto] = $arregloDatos[referencia];
    $this->datos->setCabezaLevante($arregloDatos);
    $arregloDatos[plantilla] = 'levanteCabezaProceso.html';
    $arregloDatos[thisFunction] = 'getCabezaProceso';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function delMercanciaLevante($arregloDatos) {
    $this->controlarTransaccion($arregloDatos);
    $this->datos->delMercanciaLevante($arregloDatos);
    //grupo_borrado se verifica si se borro todo el parcial de un grupo para restar 1
    $unaConsulta = new Levante();
    $unaConsulta->getConteoParciales($arregloDatos);
    if($unaConsulta->N == 0) {
      $unaConsulta->menosConteoParciales($arregloDatos); // resta al conteo de parciales
    }
    
    $arregloDatos[plantilla] = 'levanteCuerpo.html';
    $arregloDatos[thisFunction] = 'getCuerpoLevante';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function delMercancia($arregloDatos) {
    // Para reversar las UBICACIONES BORRADAS FREDY
    $unaReversion = new Levante();
    $unaReversion->reversarRetiroUbicaciones($arregloDatos);
    $this->controlarTransaccion($arregloDatos);
    $this->datos->delMercancia($arregloDatos);
    $arregloDatos[plantilla] = 'levanteCuerpoRetiro.html';
    $arregloDatos[thisFunction] = 'getCuerpoRetiro';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function existeCliente($arregloDatos) {
    $unaConsulta = new Levante();
    $unaConsulta->existeCliente($arregloDatos);
    
    if($unaConsulta->N == 0) {
      echo $unaConsulta->N;
      die();
    }
    $unaConsulta->existeLevante($arregloDatos);
    if($unaConsulta->N > 0) {
      echo 10;
      die();
    }
    echo 1;
  }
  
  function existeLevante($arregloDatos) {
    $unaConsulta = new Levante();
    $unaConsulta->existeLevante($arregloDatos);
    echo $unaConsulta->N;
  }
  
  function titulo($arregloDatos) {
    $unDato = new Levante();
    $titulo = '';
    
    if(!empty($arregloDatos['por_cuenta_filtro'])) {
      $arregloDatos[por_cuenta] = $arregloDatos[por_cuenta_filtro];
      $unDato->existeCliente($arregloDatos);
      $unDato->fetch();
      $titulo = $unDato->razon_social;
    }
    if(!empty($arregloDatos[ubicacion_filtro])) {
      $titulo .= " ubicaciòn ".$unDato->dato('do_bodegas','codigo',$arregloDatos[ubicacion_filtro]);
    }
    if(!empty($arregloDatos[estado_filtro])) {
      $titulo .= " estado ".$unDato->dato('do_estados','codigo',$arregloDatos[estado_filtro]);
    }
    if(!empty($arregloDatos[fecha_inicio]) and !empty($arregloDatos[fecha_fin])) {
      $titulo .= " desde ".$arregloDatos[fecha_inicio]." hasta ".$arregloDatos[fecha_fin];
    }
    if(!empty($arregloDatos[doc_filtro])) {
      $titulo .= " documento ".$arregloDatos[doc_filtro];
    }
    if(!empty($arregloDatos[do_filtro])) {
      $titulo .= " Do ".$arregloDatos[do_filtro];
    }
    
    return ucwords(strtolower($titulo));
  }
  
  function maestroConsulta($arregloDatos) {
    if(!empty($arregloDatos['accion'])) {
      $arregloDatos['mostrarFiltroEstado'] = 'none';
    }
    $arregloDatos['titulo'] = $this->titulo($arregloDatos);
    $arregloDatos['metodoAux'] = 'maestroConsulta';
    $this->pantalla->maestroConsulta($arregloDatos);
  }
  
  function pedido($arregloDatos) {
    if(!empty($arregloDatos['accion'])) {
      $arregloDatos['mostrarFiltroEstado'] = 'none';
    }
    $arregloDatos['titulo'] = $this->titulo($arregloDatos);
    $arregloDatos['metodoAux'] = 'maestroConsulta';
    $this->pantalla->maestroConsulta($arregloDatos);
  }
  
  function imprimeLevante($arregloDatos) {
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'levanteRemesaRetiro.html';
    $arregloDatos[thisFunction] = 'listaInventario';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function banderaMatriz($arregloDatos) {
    $n = $this->datos->banderaMatriz($arregloDatos);
    echo $n;
  }
  
  function setCosteo($arregloDatos) {
    $fobs = $arregloDatos[fob];
    foreach($arregloDatos[item] as $key => $value) {
      $arregloDatos[fob] = $fobs[$key];
      $arregloDatos[id_item] = $value;
      $this->datos->setCosteo($arregloDatos);
    }
  }
  
  function findConductor($arregloDatos) {
    $unaConsulta = new Levante();
    $unaConsulta->findConductor($arregloDatos);
    $arregloDatos[q] = strtolower($_GET["q"]);
    while($unaConsulta->fetch()) {
      $nombre = '['.trim($unaConsulta->codigo).']'.trim($unaConsulta->nombre);
      echo "$nombre|$unaConsulta->codigo|$unaConsulta->nombre\n";
    }
    if($unaConsulta->N == 0) {
      echo "No hay Resultados|0\n";
    }
  }
  
  function findDocumento($arregloDatos) {
    $unaConsulta = new Levante();
    $unaConsulta->findDocumento($arregloDatos);
    $arregloDatos[q] = strtolower($_GET["q"]);
    while($unaConsulta->fetch()) {
      $nombre = trim($unaConsulta->doc_tte)." [ORDEN] ".trim($unaConsulta->do_asignado);
      echo "$nombre|$unaConsulta->doc_tte|$unaConsulta->do_asignado\n";
    }
    if($unaConsulta->N == 0) {
      echo "No hay Resultados|0\n";
    }
  }
  
  function findBodega($arregloDatos) {
    $unaConsulta = new Levante();
    $unaConsulta->findBodega($arregloDatos);
    $arregloDatos[q] = strtolower($_GET["q"]);
    while($unaConsulta->fetch()) {
      $nombre = '['.trim($unaConsulta->codigo).']'.trim($unaConsulta->nombre);
      echo "$nombre|$unaConsulta->codigo|$unaConsulta->nombre|$unaConsulta->direccion|$unaConsulta->codigo\n";
    }
    if($unaConsulta->N == 0) {
      echo "No hay Resultados|0\n";
    }
  }
  
  function findPosicion($arregloDatos) {
    $unaConsulta = new Levante();
    $unIva = new Levante();
    $unIva->getIva( $arregloDatos);
    $unIva->fetch();
    $iva = $unIva->iva;
    $unaConsulta->findPosicion($arregloDatos);
    $arregloDatos[q] = strtolower($_GET["q"]);

    while($unaConsulta->fetch()) {
      $nombre = '['.trim($unaConsulta->codigo).']'.trim($unaConsulta->nombre);
      echo "$nombre|$unaConsulta->codigo|$unaConsulta->arancel|$unaConsulta->direccion|$unaConsulta->codigo|$unaConsulta->codigo|$iva\n";
    }
    if($unaConsulta->N == 0) {
      echo "No hay Resultados|0\n";
    }
  }
  
  function findCiudad($arregloDatos) {
    $unaConsulta = new Levante();
    $unaConsulta->findCiudad($arregloDatos);
    $arregloDatos[q] = strtolower($_GET["q"]);
    while($unaConsulta->fetch()) {
      $nombre = '['.trim($unaConsulta->codigo).']'.trim($unaConsulta->nombre);
      echo "$nombre|$unaConsulta->codigo|$unaConsulta->nombre|$unaConsulta->direccion|$unaConsulta->codigo\n";
    }
    if($unaConsulta->N==0) {
      echo "No hay Resultados|0\n";
    }
  }
  
  function envioMail($arregloDatos) {
    $arregloDatos['remite'] = 'blogistic@grupobysoft.com';
    $remite = array('email' => $arregloDatos['remite'],'nombre' => $arregloDatos['remite']);
		$destino = array('email'  => $arregloDatos['email'],'nombre' => $arregloDatos['email']);				

		require_once('EnvioMail.php');
		$mail = new EnvioMail();

		$mail->cuerpo($arregloDatos[plantilla_mail],$arregloDatos[plantilla_mail],$arregloDatos);
		$mail->cargarCabecera($destino, $remite, $arregloDatos[asunto_mail]);
		//Procedimiento de Envío de mail y validación de envío correcto
		$arregloDatos[info] = $mail->enviarEmail() ? -1 : 0;
		$this->pantalla->mostrarMensaje($arregloDatos);
	}
	
	function traeCiudades($arregloDatos) {
		$this->datos->traeCiudades($arregloDatos);
	}
	
	function imprimeRechazados($arregloDatos) {
    
 	$arregloDatos[plantilla] = 'levanteCuerpo.html';
    $arregloDatos[thisFunction] = 'getCuerpoLevante';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
	
}
?>