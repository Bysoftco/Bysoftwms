<?php
 //echo "test";die();
require_once("FacturaDatos.php");
require_once("FacturaPresentacion.php");
require_once("ReporteExcel.php");
require_once("InventarioDatos.php");

class FacturaLogica {
  var $datos;      	
  var $pantalla;
		
  function FacturaLogica() {
    $this->datos =& new Factura();
    $this->pantalla =& new FacturaPresentacion($this->datos);
	$this->id_levante=0;
  } 

  function maestro($arregloDatos) {
 
    $arregloDatos[tab_seleccionado] = 0;  
    $arregloDatos[plantillaFiltro] = "levanteFiltroNacionalizar.html";
    $this->pantalla->maestro($arregloDatos);
  }
        
  function addPreFactura($arregloDatos) {
    $arregloDatos[plantilla_conceptos] = "facturaConceptos.html";
    if($this->datos->addCabeza($arregloDatos)) {  // Solo si se crea cabeza se crea cuerpo
      $this->datos->getConsecutivo($arregloDatos);
      for($i=1;$i<=$arregloDatos[cantidad_conceptos];$i++) {
        $this->datos->addConcepto($arregloDatos);
      }
    }
            
    $this->pantalla->preFactura($arregloDatos);
  }

  // Función que genera el número Oficial de la factura y la cierra
  function setNuevaFactura($arregloDatos) {
    $arregloDatos[funcion] = 'Oficial';
    $unConsecutivo = new Factura();
    
    if(empty($arregloDatos[num_factura])) {
      $arregloDatos[num_factura] = $unConsecutivo->numeroFactura($arregloDatos);
    }
    $this->datos->getResolucion($arregloDatos);
    $this->datos->getFirma($arregloDatos);
    $this->datos->setNuevaFactura($arregloDatos);
    $this->getFacturaCabezaInfo($arregloDatos);
  }
        
  // Muestra la factura en Forma Edición para modificarla
  function getPreFactura($arregloDatos) {
    $arregloDatos[plantilla_conceptos] = 'facturaConceptos.html';
    $this->pantalla->preFactura($arregloDatos);
  }
        
  // Función que habilita la factura para impresión
  function habilitaImpresion($arregloDatos) {
    $this->datos->habilitaReimpresion($arregloDatos);  
    $this->consultaFactura($arregloDatos);
  }
        
  // Función que anula la factura para impresión
  function anularFactura($arregloDatos) {
    $this->datos->anularFactura($arregloDatos);  
    $this->consultaFactura($arregloDatos);
  }

  // Función que abre la factura para impresión
  function abrirFactura($arregloDatos) {
    $this->datos->abrirFactura($arregloDatos);  
    $this->consultaFactura($arregloDatos);
  }
        
  // Función que consulta una Factura
  function consultaFactura($arregloDatos) {
    $arregloDatos[mostar] = "0";
    $arregloDatos[plantilla] = 'facturaToolbar.html';
    $arregloDatos[thisFunction] = 'getToolbar';  
    $arregloDatos[toolbar] = $this->pantalla->setFuncion($arregloDatos,$this->datos);
            
    $arregloDatos[mostrar] = 1;
    $this->getFacturaCabezaInfo($arregloDatos);
  }
        
  function getFacturaCabezaInfo($arregloDatos) {
    $arregloDatos[plantilla_conceptos] = 'facturaConceptosInfo.html';
    $arregloDatos[plantilla] = 'facturaFormCabezaInfo.html';
    $arregloDatos[thisFunction] = 'getCabeza';  
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
        
  function getFacturaCuerpoInfo($arregloDatos) {
    $arregloDatos[plantilla] = 'facturaConceptosInfo.html';
    $arregloDatos[thisFunction] = 'getConceptos';  
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
        
  function impresion($arregloDatos) {
    $this->pantalla->copias($arregloDatos);
    $this->datos->reportarImpresion($arregloDatos);
  }
        
  function updateAnticipos($arregloDatos) {
    $unDato = new Factura();
    $valores = $arregloDatos[anticipos_adicionales];
    $recibo = $arregloDatos[recibos_anticipos];

    if(is_array($arregloDatos[id_anticipo])) {
      foreach ($arregloDatos[id_anticipo] as $key => $value) {
        $unDato->updateAnticipo($value,$recibo[$key],$valores[$key]);
      }
    }
  }

  // Método que Actualiza el valor de la Factura
  function updateFactura($arregloDatos) {
  	
    //Se guardan los anticipos
	
    $this->updateAnticipos($arregloDatos);
    // Si la remesa es diferente de 0 esto indica que se facturo por remesa y se debe ligar remesa y factura
    if($arregloDatos[remesa] > 0 ) {
      $this->datos->setLiveraRemesa($arregloDatos); // Se sueltan la factura de cualquier otra referencia
      $this->datos->setLigarRemesa($arregloDatos);
    }
    if($this->datos->updateCabeza($arregloDatos)) {
      $this->datos->updateInfoOrden($arregloDatos);
      $conceptos = $arregloDatos[cod_conceptos];
	  //var_dump($conceptos);
      $ivas = $arregloDatos[ivas];
      $rte_fuentes = $arregloDatos[rte_fuentes];
      $rete_icas = $arregloDatos[rete_icas];
      $rete_crees = $arregloDatos[rete_crees];
      $concep_tarifa = $arregloDatos[concep_tarifa];
      $cantidades = $arregloDatos[cantidades];
      $valores = $arregloDatos[valores];
      $vunitarios = $arregloDatos[vunitario];
      $bases = $arregloDatos[bases];
      $multiplicadores = $arregloDatos[multiplicador];
      foreach($arregloDatos[id_conceptos] as $key => $value) {
	  
	  
        $arregloDatos[id_concepto] = $value;
        $arregloDatos[cod_concepto] = $conceptos[$key];
        $arregloDatos[iva] = $ivas[$key];
        $arregloDatos[rte_fuente] = $rte_fuentes[$key];
        $arregloDatos[rete_ica] = $rete_icas[$key];
        $arregloDatos[rete_cree] = $rete_crees[$key];
        $arregloDatos[concep_tarifa] = $concep_tarifa[$key];
        $arregloDatos[cantidad] = $cantidades[$key];
        $arregloDatos[valor] = $valores[$key];
        $arregloDatos[valor_unitario] = $vunitarios[$key];
        $arregloDatos[base] = $bases[$key];
        $arregloDatos[multiplicador] = $multiplicadores[$key];
		 
		// por cada referencia se hace el retiros
	  	$this->setRetiroInventario($arregloDatos);
		
        $this->datos->updateConcepto($arregloDatos);
      }
    }
  }
        
  // Método que borra Conceptos a la Factura
  function delConcepto($arregloDatos) {
    $this->datos->delConcepto($arregloDatos);
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'facturaConceptos.html';
    $arregloDatos[thisFunction] = 'getConceptos';  
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
        
  // Método que adiciona Conceptos a la Factura
  function addConcepto($arregloDatos) {
    $this->datos->addConcepto($arregloDatos);
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'facturaConceptos.html';
    $arregloDatos[thisFunction] = 'getConceptos';  
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
        
  // Función que retorna una lista de Conceptos 
  function findConcepto($arregloDatos) {
    $unaConsulta = new Factura();
    $unaConsulta->findConcepto($arregloDatos);
    $arregloDatos[q] = strtolower($_GET["q"]);
    header( 'Content-type: text/html; charset=iso-8859-1' );
    while($unaConsulta->fetch()) {
      $nombre = trim($unaConsulta->nombre);
      echo "$nombre|$unaConsulta->codigo|$unaConsulta->iva|$unaConsulta->rte_fuente|$unaConsulta->rte_ica|$unaConsulta->rte_cree\n";
    }
    if($unaConsulta->N == 0) {
      echo "No hay Resultados|0\n";
    }
  }
    
  //Método Para Consultar Facturas
  function maestroConsulta($arregloDatos) {
    $arregloDatos['mostrarConsultaAnular'] = "block";
    $arregloDatos['titulo'] = $this->titulo($arregloDatos);
    $arregloDatos['metodoAux'] = 'maestroConsulta';
	$arregloDatos['mostrarPreFactura'] = "none";
    $this->pantalla->maestroConsulta($arregloDatos);
  }
    
  //Método Para Consultar preliquidaciones
  function preliquidacionesListado($arregloDatos) {
    $arregloDatos['preliquidacion'] = 1;
    $arregloDatos['titulo'] = $this->titulo($arregloDatos);
    $arregloDatos['metodoAux'] = 'maestroConsulta';
    $this->pantalla->maestroConsulta($arregloDatos);
  }
    
  //Método Para Consultar Facturas
  function Preliquidaciones($arregloDatos) {
    $arregloDatos['mostrarConsultaAnular'] = "none";
    $arregloDatos['titulo'] = $this->titulo($arregloDatos);
    $arregloDatos['metodoAux'] = 'preliquidacionesListado';
	$arregloDatos['mostrarFactura'] = "none";
    $this->pantalla->maestroConsulta($arregloDatos);
  }
    
  //Método Para Consultar Facturas y reimprimir
  function getReimpresion($arregloDatos) {
    $arregloDatos['mostrarConsultaAnular'] = "none";
    $arregloDatos['titulo'] = $this->titulo($arregloDatos);
    $arregloDatos['metodoAux'] = 'reimpresionListado';
    $this->pantalla->maestroConsulta($arregloDatos);
  }
    
  //Método Para Consultar Facturas y reimprimir
  function getFiltroAnular($arregloDatos) {
    $arregloDatos['mostrarConsultaAnular'] = "none";
    $arregloDatos['titulo'] = $this->titulo($arregloDatos);
    $arregloDatos['metodoAux'] = 'anularListado';
    $this->pantalla->maestroConsulta($arregloDatos);
  }
    
  //Método Para Consultar preliquidaciones
  function reimpresionListado($arregloDatos) {
    $arregloDatos['reimpresion'] = 1;
    $arregloDatos['titulo'] = $this->titulo($arregloDatos);
    $arregloDatos['metodoAux'] = 'maestroConsulta';
    $this->pantalla->maestroConsulta($arregloDatos);
  }
    
  //Método Para Consultar preliquidaciones
  function anularListado($arregloDatos) {
    $arregloDatos['reimpresion'] = 1;
    $arregloDatos['titulo'] = $this->titulo($arregloDatos);
    $arregloDatos['metodoAux'] = 'maestroConsulta';
    $this->pantalla->maestroConsulta($arregloDatos);
  }
    
  //Método que retorna el listado de Ordenes para Facturar Consulta de Ordenes
  function paraFacturar($arregloDatos) {
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'facturaListadoParaFacturar.html';
    $arregloDatos[thisFunction] = 'getParaFacturar';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
    
  //Método que retorna el formulario para captura de anticipos
  function deleteAnticipos($arregloDatos) {
    $this->datos->deleteAnticipos($arregloDatos);
    $this->getFormaAnticipos($arregloDatos);
  }
    
  //Método que retorna el formulario para captura de anticipos
  function getFormaAnticipos($arregloDatos) {
    // Se agregan los anticipos solicitados 
    for($i=1;$i<=$arregloDatos[numeroAnticipos];$i++) {
      $this->datos->addAnticipos($arregloDatos[num_factura],0,0);
    }
       
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'facturaAnticipo.html';
    $arregloDatos[thisFunction] = 'getFormaAnticipos';
    $arregloDatos[thisFunctionAux] = 'getFormaAnticipos';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
    
  //Método que retorna el listado de Ordenes para Facturar Consulta de Remesas
  function excel($arregloDatos) {
    $arregloDatos['excel'] = 999;
    $arregloDatos['sql'] = $this->datos->listarFacturas($arregloDatos);

    $arregloDatos['do_asignado'] = NULL;
    $unExcel = new ReporteExcel($arregloDatos);
    $unExcel->generarExcel();
  }
    
  //Método que retorna el listado de Ordenes para Facturar Consulta de Remesas
  function paraFacturarRemesas($arregloDatos) {
    $arregloDatos[tipo_movimiento] = 3; // 3 Retiros
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'facturaListadoParaFacturarRemesa.html';
    $arregloDatos[thisFunction]	= 'getParaFacturarRemesa';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
    
  //Función que retorna un titulo
  function titulo($arregloDatos) {
    $unDato = new Factura();
    $titulo = '';

    if(!empty($arregloDatos['por_cuenta_filtro'])) {
      $arregloDatos[por_cuenta] = $arregloDatos[por_cuenta_filtro];
      $unDato->existeCliente($arregloDatos);
      $unDato->fetch();
      $titulo = $unDato->razon_social;
    }

    if(!empty($arregloDatos[ubicacion_filtro])) {
      $titulo .= " ubicación ".$unDato->dato('do_bodegas','codigo',$arregloDatos[ubicacion_filtro]);
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
  function preforma($arregloDatos) {
   		//facturaPreliquidacion
		// pendiente quitar el false de thisFunction
   		$arregloDatos[mostrar] = 1;
    	$arregloDatos[plantilla] = 'facturaPreliquidacion.html';
		$arregloDatos[thisFunction]	= 'getFacturaPreliquidacion';
    	$this->pantalla->setFuncion($arregloDatos,$this->datos);
   }
   function  getDatosUnaOrden($arregloDatos){
   		$this->datos->getDatosUnaOrden($arregloDatos);
		$this->datos->fetch();
		$arregloDatos[datos_remesa] = "Piezas : " . trim(abs($this->datos->cantidad));
    	$arregloDatos[datos_remesa] .= "  Peso : " . trim(abs($this->datos->peso) );
    	$arregloDatos[datos_remesa] .= "  Valor : " . round($this->datos->valor);
  		echo $arregloDatos[datos_remesa];
		
  }
     function  saldoInventario($arregloDatos){
	
	 	$arregloDatos[having] = " HAVING peso_nonac  > 0 OR peso_naci > 0 ";
		$arregloDatos[where] .=" AND  ie.orden='$arregloDatos[una_orden]'"; // filtro por referencia
		$arregloDatos[GroupBy] = "orden";  // 
		$arregloDatos[movimiento] = "1,2,3,7,10,15,30"; 
		
		$this->datos->saldoInventario($arregloDatos);
		//$this->datos->fetch();
		
  		echo $arregloDatos[datos_remesa];
		
  }
  
   function  getDatosRemesa($arregloDatos){
   		$this->datos->getDatosRemesa($arregloDatos);
   }
   
   
    
	function  getInventario($arregloDatos)
	{
	//var_dump($arregloDatos);
		$arregloDatos[having] = " HAVING peso_nonac  > 0 OR peso_naci > 0 ";
		$arregloDatos[where] .=" AND  ref.codigo=$arregloDatos[referencia] "; // filtro por referencia
		$arregloDatos[GroupBy] = "codigo_referencia ";  // 
		//$arregloDatos[movimiento] = "1,2,3,7,10,15,30"; 
		$arregloDatos[movimiento] = "1,2,3,7,10,15,16,19,30";
		$unLevante = new Levante();
		$unLevante->getInvParaProceso($arregloDatos);
		$unLevante->fetch();
		header( 'Content-type: text/html; charset=iso-8859-1' );
		switch($arregloDatos[tipo_factura])
		{
			case 1:
				$cantidad=$unLevante->cantidad_nonac+$unLevante->cantidad_naci;
			break;
			case 2: //
				
				$cantidad=$unLevante->cantidad_nonac+$unLevante->cantidad_naci;
			break;
		}
		
		
		$tarifa=0;
		if($cantidad > 0){
			$this->datos->getTarifas($arregloDatos);
			$this->datos->fetch();
			switch($arregloDatos[tarifa])
			{
				case 1:  //plena
				$tarifa=$this->datos->plena;
				break;   
				case 2: //general
				$tarifa=$this->datos->agente;
				break;
				case 3: //minima
					$tarifa=$this->datos->minima;
				break;
			}
		}
		echo "$cantidad|$tarifa";
		//echo "$cantidad|$arregloDatos[tarifa]";
		//echo "|";
		//$this->datos->saldoInventario($arregloDatos);
   		//$this->datos->getInventario($arregloDatos);
   }
   
   function  setRetiroInventario($arregloDatos)
  {
//var_dump($arregloDatos);
		$arregloDatos[referencia]	=$arregloDatos[cod_concepto];
  		$arregloDatos[having] 		= " HAVING peso_nonac  > 0 OR peso_naci > 0 ";
		$arregloDatos[where] 		.=" AND  ref.codigo=$arregloDatos[cod_concepto] "; // referencia filtro por referencia
		$arregloDatos[GroupBy] 		= "  inventario_entrada,codigo_referencia "; 
		$arregloDatos[movimiento] 	= "1,2,3,7,10,15,16,19,30";
		$paraRetiro 				= new Levante();
		$paraRetiro->getInvParaProceso($arregloDatos);
		
		$cantidad_retirada=0;
		
		// Se crea la caebeza del retiro
		$paraCabezaRetiro 				= new Levante();
		$arregloDatos[tipo_movimiento] 	= 3;// tipo de movimiento rretiro
		if($this->id_levante==0){
			$arregloDatos[id_levante] 		= $paraCabezaRetiro->newLevante($arregloDatos);
			$this->id_levante=$arregloDatos[id_levante];
		}else{
			$arregloDatos[id_levante]=$this->id_levante;
		}
		
		
		$arregloDatos[doc_tte]			=$arregloDatos[num_prefactura];
		$arregloDatos[obs]				=" Retiro desde Modulo de Prefactura $arregloDatos[num_prefactura] "; 
		$paraCabezaRetiro ->setCabezaLevante($arregloDatos);
	//echo "X$arregloDatos[id_levante]";
		
		while($paraRetiro->fetch()) 
		{
			//echo " XXXXXX $paraRetiro->N  YYYYYYYYYYYYYY $arregloDatos[cod_concepto]" ;
			if($arregloDatos[acumula] < $arregloDatos[cantidad])
			{
				//echo $arregloDatos[cantidad]."<BR>";
			$cantidad_retirada					=$paraRetiro->cantidad_naci+$paraRetiro->cantidad_nonac;
			
			/*if($cantidad_retirada <= $arregloDatos[cantidad])
			{
				$cantidad_control=$arregloDatos[cantidad]-$cantidad_retirada;
				if($cantidad_retirada > $cantidad_control) // se va a retirar mas que lo indicado en la factura 
				{
					if($paraRetiro->cantidad_naci > $cantidad_control){
						$paraRetiro->cantidad_naci=$cantidad_control; // se retira el saldo necesario solo de la nacional
						$paraRetiro->cantidad_nonac=0;
					}
				}*/
				
				//var_dump($paraRetiro);
				$arregloDatos[peso_naci_para]		=$paraRetiro->peso_naci;
				$arregloDatos[peso_nonaci_para]		=$paraRetiro->peso_nonac;
				$arregloDatos[cantidad_naci_para]	=$paraRetiro->cantidad_naci;
				$arregloDatos[cantidad_nonaci_para]	=$paraRetiro->cantidad_nonac;
				$arregloDatos[cif_ret]				=$paraRetiro->cif;
				$arregloDatos[fob_ret]				=$paraRetiro->fob_nonac;
				$arregloDatos[id_item]				=$paraRetiro->inventario_entrada;
				$arregloDatos[num_levante]			=$arregloDatos[num_prefactura];
				
				//var_dump($arregloDatos);
				if($arregloDatos[para_cerrar]==1)
				{
					$this->getDecideRetiro($arregloDatos,$paraRetiro);
				}
				
				//$unRetiro = new Levante();
				//$unRetiro->addItemRetiro($arregloDatos);
			}	
			
		}
		
		
  }
  
   function  getDecideRetiro(&$arregloDatos,$paraRetiro)
  {
  	$unRetiro = new Levante();
	
  	if($paraRetiro->cantidad_naci > $paraRetiro->cantidad_nonac &&  $paraRetiro->cantidad_naci >0) // se retira de donde mas se encuentra
	{
		
		$retiro=$arregloDatos[cantidad]-$arregloDatos[acumula];
		if($paraRetiro->cantidad_naci>=$retiro ) // se puede retirar del inventario nacional hay existencias superiores
		{
			$arregloDatos[acumula]=$arregloDatos[acumula]+$retiro;
			
			$arregloDatos[cantidad_nonaci_para]=0;
			$arregloDatos[peso_nonaci_para]	   =0;
			$arregloDatos[peso_naci_para]		=$arregloDatos[peso_naci_para]/$paraRetiro->cantidad_naci*$retiro;
			
			
			$arregloDatos[cantidad_naci_para]	=$retiro;
			$unRetiro->addItemRetiro($arregloDatos);
			
		}else
		{
			
			$arregloDatos[acumula]=$arregloDatos[acumula]+$paraRetiro->cantidad_naci;
			
			$arregloDatos[cantidad_nonaci_para]	=0;
			$arregloDatos[peso_nonaci_para]		=0;
			
			$arregloDatos[cantidad_naci_para]	=$paraRetiro->cantidad_naci;
			$unRetiro->addItemRetiro($arregloDatos);
		}
		
		
		$retiro=$arregloDatos[cantidad]-$arregloDatos[acumula];
		if($paraRetiro->cantidad_nonac>=$retiro ) // se puede retirar del inventario extranjero
		{
			$arregloDatos[acumula]=$arregloDatos[acumula]+$retiro;
			
			
			$arregloDatos[cantidad_naci_para]=0;
			$arregloDatos[peso_naci_para]	 =0;
			$arregloDatos[peso_nonaci_para]	=$paraRetiro->peso_nonac/$paraRetiro->cantidad_nonac *$retiro;
			
			
			$arregloDatos[cantidad_nonaci_para]	=$retiro;
			$unRetiro->addItemRetiro($arregloDatos);
			
		}else
		{
			$arregloDatos[acumula]=$arregloDatos[acumula]+$paraRetiro->cantidad_nonac;
			
			$arregloDatos[cantidad_naci_para]=0;
			$arregloDatos[peso_naci_para]	 =0;
			
			
			$arregloDatos[cantidad_nonaci_para]	=$paraRetiro->cantidad_nonac;
			$unRetiro->addItemRetiro($arregloDatos);
		}
		
	}else   // hay mas mercancia en extrangero
	{
		
		
		$retiro=$arregloDatos[cantidad]-$arregloDatos[acumula];
		
		
		if($paraRetiro->cantidad_nonac>=$retiro ) // se puede retirar del inventario nacional hay existencias superiores
		{
			
			$arregloDatos[acumula]=$arregloDatos[acumula]+$retiro;
			
			$arregloDatos[cantidad_naci_para]	=0;
			$arregloDatos[peso_naci_para]	 =0;
			if($retiro > 0)
			{
				$arregloDatos[peso_nonaci_para]	=$paraRetiro->peso_nonac/$paraRetiro->cantidad_nonac*$retiro;
				$arregloDatos[cantidad_nonaci_para]	=$retiro;
				$unRetiro->addItemRetiro($arregloDatos);
			}
		}else
		{
			
			$arregloDatos[acumula]=$arregloDatos[acumula]+$paraRetiro->cantidad_nonac;
			
					
			$arregloDatos[cantidad_naci_para]	=0;	 // valores opuestos quedan en 0
			$arregloDatos[peso_naci_para]	 =0;
			if($paraRetiro->cantidad_nonac > 0)
			{
				
				$arregloDatos[cantidad_nonaci_para]	=$paraRetiro->cantidad_nonac;
				$unRetiro->addItemRetiro($arregloDatos);
			}	
		}
		
		
		$retiro=$arregloDatos[cantidad]-$arregloDatos[acumula];
		if($paraRetiro->cantidad_naci>=$retiro && $paraRetiro->cantidad_naci > 0 ) // se puede retirar del inventario nacional
		{
			$arregloDatos[acumula]=$arregloDatos[acumula]+$retiro;
			
			$arregloDatos[cantidad_nonaci_para]=0;  // valores opuestos quedan en 0
			$arregloDatos[peso_nonaci_para]		=0;
			$arregloDatos[peso_naci_para]		=$paraRetiro->peso_naci/$paraRetiro->cantidad_naci*$retiro;
			
			$arregloDatos[cantidad_naci_para]	=$retiro;
			$unRetiro->addItemRetiro($arregloDatos);
			
		}else
		{
			$arregloDatos[acumula]=$arregloDatos[acumula]+$paraRetiro->cantidad_naci;
			
			$arregloDatos[cantidad_nonaci_para]	=0;
			$arregloDatos[peso_nonaci_para]		=0;
			
			if($paraRetiro->cantidad_naci > 0)
			{	
				
				$arregloDatos[cantidad_naci_para]	=$paraRetiro->cantidad_naci;
				$unRetiro->addItemRetiro($arregloDatos);
			}
				
		}
		
		//
	
	}
	
  	//var_dump($paraRetiro);
  }
   
}
?>