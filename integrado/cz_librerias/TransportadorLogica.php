<?php
require_once("ReporteExcel.php");
require_once("TransportadorDatos.php");
require_once("TransportadorPresentacion.php");

class TransportadorLogica  {
	var $datos;
	var $pantalla;
		
	function TransportadorLogica () {
		$this->datos =& new Transportador();
		$this->pantalla =& new TransportadorPresentacion($this->datos);
	}		
			
	function filtro($arregloDatos) {
		$arregloDatos[mostrar] = 1;
		$arregloDatos[plantilla] = 'transportadorFiltro.html';
		$arregloDatos[thisFunction] = 'filtro';
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}
            
	function titulo($arregloDatos) {
		if(!empty($arregloDatos[cliente])) {
			$arregloDatos[titulo] = "Cliente ".$arregloDatos[cliente];
		}
                
		if(!empty($arregloDatos[fecha_inicio])) {
			$arregloDatos[titulo] = " Desde ".$arregloDatos[fecha_inicio]." Hasta.$arregloDatos[fecha_fin]";
		}
	}
            
	function getSubpartida($arregloDatos){
		$this->titulo($arregloDatos);
		$arregloDatos[mostrar] = 1;
		$arregloDatos[plantilla] = 'SubpartidaSubpartidaInventario.html';
		$arregloDatos[thisFunction]= 'inventario';
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}
	
	function getUnTransportador($arregloDatos) {
		$arregloDatos[mostrar] = 1;
		$arregloDatos[plantilla] = 'transportadorFormulario.html';
		$arregloDatos[thisFunction] = 'getUnTransportador';
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}
	
	function updateTransportador($arregloDatos) {
		$this->datos->updateTransportador($arregloDatos); 
			  
		$arregloDatos[mostar] = "0";
		$arregloDatos[plantilla] = 'transportadorToolbar.html';
		$arregloDatos[thisFunction] = 'getToolbar';
		$arregloDatos[toolbarLevante]= $this->pantalla->setFuncion($arregloDatos,$this->datos);
			  
		$arregloDatos[mostar] = "1";
		$arregloDatos[plantilla] = 'transportadorListado.html';
		$arregloDatos[thisFunction] = 'getListado';
		echo $this->pantalla->setFuncion($arregloDatos,$this->datos);                
	}
			
	function findTransportador($arregloDatos) {
		$unaConsulta = new Transportador();

		$unaConsulta->findCliente($arregloDatos);
		$arregloDatos[q] = strtolower($_GET["q"]);

		while($unaConsulta->fetch()) {
			$nombre = utf8_encode(trim($unaConsulta->nombre));
			echo "$nombre|$unaConsulta->codigo|	$nombre\n";
		}
		if($unaConsulta->N == 0) {
			echo "No hay Resultados|0\n";
		}
	}

	function generaExcel ($arregloDatos) {
		$arregloDatos[excel] = 1;
		$arregloDatos['titulo'] = 'Transportador '.$arregloDatos[titulo];
		$arregloDatos['sql'] = $this->datos->getInventario($arregloDatos);
		// echo $arregloDatos['sql'];die();
		$unExcel = new SubpartidaExcel($arregloDatos);
		$unExcel->generarExcel();
	}
	
	function maestro($arregloDatos) {
		$this->pantalla->maestro($arregloDatos);
	}
	
	function getListado($arregloDatos) {
		//$this->titulo(&$arregloDatos);
		$arregloDatos[mostar] = "0";
		$arregloDatos[plantilla] = 'transportadorToolbar.html';
		$arregloDatos[thisFunction] = 'getToolbar';
		$arregloDatos[toolbarLevante] = $this->pantalla->setFuncion($arregloDatos,$this->datos);
				
		$arregloDatos[mostrar] = 1;
		$arregloDatos[plantilla] = 'transportadorListado.html';
		$arregloDatos[thisFunction] = 'getListado';
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
				
		//$this->pantalla->maestro($arregloDatos);
	}
	
	function getFormaNueva($arregloDatos) {
		$arregloDatos[plantilla] = 'transportadorFormNueva.html';
		$arregloDatos[thisFunction] = 'getFormaNueva';
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}
			
	function addTransportador($arregloDatos) {
		$this->datos->addTransportador($arregloDatos);
				
		$arregloDatos[mostar] = "0";
		$arregloDatos[plantilla] = 'transportadorToolbar.html';
		$arregloDatos[thisFunction] = 'getToolbar';
		$arregloDatos[toolbarLevante] = $this->pantalla->setFuncion($arregloDatos,$this->datos);
					
		$arregloDatos[mostar] = "1";
		$arregloDatos[plantilla] = 'transportadorListado.html';
		$arregloDatos[thisFunction] = 'getListado';
		echo $this->pantalla->setFuncion($arregloDatos,$this->datos);
	}
			
	function deleteTransportador($arregloDatos) {
		$this->datos->deleteTransportador($arregloDatos);
                
		$arregloDatos[mostar] = "0";
		$arregloDatos[plantilla] = 'transportadorToolbar.html';
		$arregloDatos[thisFunction] = 'getToolbar';
		$arregloDatos[toolbarLevante]= $this->pantalla->setFuncion($arregloDatos,$this->datos);
					
		$arregloDatos[mostar] = "1";
		$arregloDatos[plantilla] = 'transportadorListado.html';
		$arregloDatos[thisFunction] = 'getListado';
		echo $this->pantalla->setFuncion($arregloDatos,$this->datos);
	}
}	
?>
