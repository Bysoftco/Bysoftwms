<?php
require_once("InterfaseDatos.php");
require_once("InterfasePresentacion.php");
//require_once("ReporteExcelWO.php");
require_once("InventarioDatos.php");

class InterfaseLogica {
  var $datos;
  var $pantalla;
		
  function InterfaseLogica() {
    $this->datos = new Interfase();
    $this->pantalla = new InterfasePresentacion($this->datos);
  }

  function maestro($arregloDatos) {
    $arregloDatos['tab_seleccionado'] = 0;  
    $arregloDatos['plantillaFiltro'] = "levanteFiltroNacionalizar.html";
    $this->pantalla->maestro($arregloDatos);
  }
        
  function getPreInterfase($arregloDatos) {
    $arregloDatos['plantilla_conceptos'] = 'InterfaseConceptos.html';
    $this->pantalla->preInterfase($arregloDatos);
  }
        
  // Función que genera el número Oficial de la Interfase y la cierra
  function setNuevaInterfase($arregloDatos) {
    $arregloDatos['funcion'] = 'Oficial';
    $unConsecutivo = new Interfase();
    $arregloDatos['num_Interfase'] = $unConsecutivo->numeroInterfase($arregloDatos);
            
    $this->datos->setNuevaInterfase($arregloDatos);
    $this->getInterfaseCabezaInfo($arregloDatos);
  }

  // Función que genera el número Oficial de la Interfase y la cierra
  function consultaInterfase($arregloDatos) {
    if($arregloDatos['accion'] == "habilitar"){ // se habilita la Interfase para impresión
      $this->datos->habilitaReimpresion($arregloDatos);  
    }
    $arregloDatos['mostar'] = "0";
    $arregloDatos['plantilla'] = 'InterfaseToolbar.html';
    $arregloDatos['thisFunction'] = 'getToolbar';  
    //$arregloDatos['toolbar'] = $this->pantalla->setFuncion($arregloDatos,$this->datos);
    $arregloDatos['toolbar'] = $this->pantalla->cargaPlantilla($arregloDatos);

    $arregloDatos['mostrar'] = 1;
    $this->datos->setNuevaInterfase($arregloDatos);
    $this->getInterfaseCabezaInfo($arregloDatos);
  }
        
  function getInterfaseCuerpoInfo($arregloDatos) {
    $arregloDatos['plantilla'] = 'InterfaseConceptosInfo.html';
    $arregloDatos['thisFunction'] = 'getConceptos';  
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
        
  function maestroConsulta($arregloDatos) {
    $this->titulo($arregloDatos);
    switch($arregloDatos['accion']) {
      case "new":
        $this->cerrarInterfase($arregloDatos);
        break;
      case "consulta":
        break;
    }
       
    $arregloDatos['metodoAux'] = 'maestroConsulta';
    $this->pantalla->paraGenerarInterfase($arregloDatos);
  }
    
  function cerrarInterfase(&$arregloDatos) {
    $unaInterfas = new Interfase();
    $unaInterfas->existeInterfase($arregloDatos);
    $rows = $unaInterfas->db->countRows();
    if($rows == 0) {
      $unaInterfas->cerrarInterfase($arregloDatos);
    } else {
      $arregloDatos['mensaje'] = "El nombre de interfase $arregloDatos[interfase_filtro] ya existe, elija otro para cerrar la nueva interfase";
      $arregloDatos['estilo'] = 'ui-state-error';
    }
  }
   
  function paraGenerarInterfase($arregloDatos) {
    $arregloDatos['titulo'] = $this->titulo($arregloDatos);
    $arregloDatos['metodoAux'] = 'maestroConsulta';
    $this->pantalla->paraGenerarInterfase($arregloDatos);
  }
   
  //Método que retorna el listado de Ordenes para Interfaser Consulta de Ordenes
  function paraInterfaser($arregloDatos) {
    $arregloDatos['mostrar'] = 1;
    $arregloDatos['plantilla'] = 'InterfaseListadoParaInterfaser.html';
    $arregloDatos['thisFunction'] = 'getParaInterfaser';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
    
  //Método que retorna el listado de Ordenes para Interfaser Consulta de Remesas
  function paraInterfaserRemesas($arregloDatos) {
    $arregloDatos['tipo_movimiento'] = 3; // 3 Retiros
    $arregloDatos['mostrar'] = 1;
    $arregloDatos['plantilla'] = 'InterfaseListadoParaInterfaserRemesa.html';
    $arregloDatos['thisFunction'] = 'getParaInterfaserRemesa';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
    
  //Método que retorna el listado de Ordenes para Interfaser Consulta de Remesas
  function getInterfase($arregloDatos) {
    $this->datos->getInterfase($arregloDatos);	
    $this->pantalla->generaInterfase($arregloDatos);		
  }

  // Exporta Interfaz WorldOffice en Excel
  function generaExcel($arregloDatos) {
    $f = Date("YmdHi");
    $arregloDatos['titulo'] = str_replace('Interface ', '', $arregloDatos['interfase'])."-".$f;
    $unExcel = new reporteExcelWO();
    $unExcel->generarExcel($arregloDatos);
  } 
    
  //Función que retorna un título
  function titulo($arregloDatos) {
    $titulo = '';

    if(!empty($arregloDatos['por_cuenta_filtro'])) {
      $arregloDatos['por_cuenta'] = $arregloDatos['por_cuenta_filtro'];
      $unDato = new Interfase();
      $unDato->existeCliente($arregloDatos);
      $unDato = $unDato->db->fetch();
      $titulo = $unDato->razon_social;
    }
    if(!empty($arregloDatos['interfase_filtro'])) {
      $arregloDatos['titulo'] = $arregloDatos['interfase_filtro'];
    }
    if(!empty($arregloDatos['fechaDesde']) AND !empty($arregloDatos['fechaHasta'])) {
      $arregloDatos['titulo'] .= " DEL ".$arregloDatos['fechaDesde']." AL $arregloDatos[fechaHasta]";
    }
    $arregloDatos['titulo'] = ucwords(strtolower($arregloDatos['titulo']));
  }
}
?>