<?php
//require_once("IndicadoresExcel.php");
require_once("IndicadoresDatos.php");
require_once("IndicadoresPresentacion.php");

class IndicadoresLogica  {
	var $datos;
	var $pantalla;
		
	function IndicadoresLogica() {
		$this->datos =& new Indicadores();
		$this->pantalla =& new IndicadoresPresentacion($this->datos);
	}

	function filtroPrincipal($arregloDatos) {
		$arregloDatos[mostrar] = 1;
		$arregloDatos[plantilla] = 'IndicadoresPrincipal.html';
		$arregloDatos[thisFunction] = 'filtronPrincipal';
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}
			
	function maestroIndicadores($arregloDatos) {
		//$arregloDatos['titulo'] = $this->titulo($arregloDatos);
		$arregloDatos['accion'] = 'defectuosa';
		$arregloDatos['metodoEnvia'] = 'maestroDefectuosa';
		$arregloDatos['metodoAux'] = 'maestroConsulta';
			
		$this->pantalla->maestroIndicadores($arregloDatos);
	}
		
	function filtro($arregloDatos) {
		//	echo "ready";
		for($i=2015; $i <= date("Y"); $i++) {
			$anios[$i]=$i;
		}
  
		//$unaLista 	= new Inventario();
		//$lista		= $unaLista->lista("tipos_remesas");
		$arregloDatos[selectAnios] = armaSelect($anios,'[seleccione]',NULL);
		//$plantilla->setVariable("listaTiposRemesa", $lista);
	
		//$arregloDatos[anios]='';
		$arregloDatos[mostrar] = 1;
		$arregloDatos[plantilla] = 'indicadoresFiltroClientes.html';
		$arregloDatos[thisFunction] = 'filtro';
		$arregloDatos[thisFunctionAux] = 'filtro';
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}
		 
	function indicadorCliente($arregloDatos) {
		//var_dump($arregloDatos);
		if(!empty($arregloDatos[cliente])) {
			$this->datos->indicadorUnCliente($arregloDatos);
		} else {
			$this->datos->indicadorCliente($arregloDatos);
		}
		  
		$arregloDatos[titulo]=$this->titulo($arregloDatos);
		echo $arregloDatos[titulo];
		$this->pantalla->indicadorCliente($arregloDatos);	
	}
		  
	function indicadorIngresos($arregloDatos) {
		$this->datos->indicadorIngresos($arregloDatos);
		$this->pantalla->indicadorIngresos($arregloDatos);	
	}
		  
	function titulo(&$arregloDatos) {
		if(!empty($arregloDatos[cliente])) {
			$arregloDatos[titulo] = "Cliente ".$arregloDatos[cliente];
		}
                
		if(!empty($arregloDatos[fecha_inicio])) {
			$arregloDatos[titulo] = " Desde ".$arregloDatos[fecha_inicio]." Hasta.$arregloDatos[fecha_fin]";
		}
	}
            
	function generaExcel ($arregloDatos) {
		$arregloDatos[excel] = 1;
		$arregloDatos['titulo'] = 'Indicadores '.$arregloDatos[titulo];
		$arregloDatos['sql'] = $this->datos->getInventario($arregloDatos);
		// echo $arregloDatos['sql'];die();
		$unExcel = new IndicadoresExcel($arregloDatos);
		$unExcel->generarExcel();
	} 
}
?>