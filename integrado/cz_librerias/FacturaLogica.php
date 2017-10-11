<?php
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
}
?>