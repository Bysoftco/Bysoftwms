<?php
//require_once("ReporteExcel.php");
require_once("UbicacionDatos.php");
require_once("UbicacionPresentacion.php");

class UbicacionLogica {
	var $datos;
	var $pantalla;
		
	function UbicacionLogica() {
		$this->datos = new Ubicacion();
		$this->pantalla = new UbicacionPresentacion($this->datos);
	}
			
	function filtro($arregloDatos) {
		$arregloDatos['mostrar'] = 1;
		$arregloDatos['plantilla'] = 'ubicacionFiltro.html';
		$arregloDatos['thisFunction'] = 'filtro';
		$this->pantalla->cargaPlantilla($arregloDatos);
	}
            
	function titulo($arregloDatos) {
		if(!empty($arregloDatos['cliente'])) {
			$arregloDatos['titulo'] ="Cliente ".$arregloDatos['cliente'];
		}

		if(!empty($arregloDatos['fecha_inicio'])) {
			$arregloDatos['titulo'] = " Desde ".$arregloDatos['fecha_inicio']." Hasta ".$arregloDatos['fecha_fin'];
		}
	}
            
	function getUbicacion($arregloDatos) {
		$this->titulo($arregloDatos);
		$arregloDatos['mostrar'] = 1;
		$arregloDatos['plantilla'] = 'SubpartidaSubpartidaInventario.html';
		$arregloDatos['thisFunction'] = 'inventario';
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function getUnaUbicacion($arregloDatos) {
		$arregloDatos['mostrar'] = 1;
		$arregloDatos['plantilla'] = 'ubicacionFormulario.html';
		$arregloDatos['thisFunction'] = 'getUnaUbicacion';
		$this->pantalla->setFuncion($arregloDatos,$this->datos); 
	}

	function updateUbicacion($arregloDatos) {
		$this->datos->updateUbicacion($arregloDatos); 

		$arregloDatos['mostar'] = "0";
		$arregloDatos['plantilla'] = 'ubicacionToolbar.html';
		$arregloDatos['thisFunction'] = 'getToolbar';
		$arregloDatos['toolbarLevante'] = $this->pantalla->cargaPlantilla($arregloDatos);

		$arregloDatos['mostar'] = "1";
		$arregloDatos['plantilla'] = 'ubicacionListado.html';
		$arregloDatos['thisFunction'] = 'getListado';
		echo $this->pantalla->setFuncion($arregloDatos,$this->datos);  
	}
			
	function findUbicacion($arregloDatos) {
		$unaConsulta = new Ubicacion();
		$arregloDatos['q'] = strtolower($_GET["q"]);
		$unaConsulta->findCliente($arregloDatos);

    $filas = $unaConsulta->db->countRows();
    while($obj=$unaConsulta->db->fetch()) {
    	$nombre = utf8_encode(trim($obj->nombre));
			echo "$nombre|$obj->posiciones|	$nombre\n";
    }
    if($filas == 0) {
    	echo "No hay Resultados|0\n";
    }
	}

	function generaExcel($arregloDatos) {
		$arregloDatos['excel'] = 1;
		$arregloDatos['titulo'] = 'Ubicacion '.$arregloDatos[titulo];
		$arregloDatos['sql'] = $this->datos->getInventario($arregloDatos);
		// echo $arregloDatos['sql'];die();
		$unExcel = new UbicacionExcel($arregloDatos);
		$unExcel->generarExcel();
	}

	function maestro($arregloDatos) {
		$this->pantalla->maestro($arregloDatos);
	}

	function getListado($arregloDatos) {
		$arregloDatos['mostar'] = "0";
		$arregloDatos['plantilla'] = 'ubicacionToolbar.html';
		$arregloDatos['thisFunction'] = 'getToolbar';
		$arregloDatos['toolbarLevante'] = $this->pantalla->cargaPlantilla($arregloDatos);				
								
		$arregloDatos['mostrar'] = 1;
		$arregloDatos['plantilla'] = 'ubicacionListado.html';
		$arregloDatos['thisFunction'] = 'getListado';
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function getFormaNueva($arregloDatos) {
		$arregloDatos['plantilla'] = 'ubicacionFormNueva.html';
		$arregloDatos['thisFunction'] = 'getFormaNueva';
		$this->pantalla->cargaPlantilla($arregloDatos);
	}
			
	function addUbicacion($arregloDatos) {
		$this->datos->addUbicacion($arregloDatos);

		$arregloDatos['mostar'] = "0";
		$arregloDatos['plantilla'] = 'ubicacionToolbar.html';
		$arregloDatos['thisFunction'] = 'getToolbar';
		$arregloDatos['toolbarLevante'] = $this->pantalla->cargaPlantilla($arregloDatos);

		$arregloDatos['mostar'] = "1";
		$arregloDatos['plantilla'] = 'ubicacionListado.html';
		$arregloDatos['thisFunction'] = 'getListado';
		echo $this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function deleteUbicacion($arregloDatos) {
		$this->datos->deleteUbicacion($arregloDatos);
	          
		$arregloDatos['mostar'] = "0";
		$arregloDatos['plantilla'] = 'ubicacionToolbar.html';
		$arregloDatos['thisFunction'] = 'getToolbar';
		$arregloDatos['toolbarLevante'] = $this->pantalla->cargaPlantilla($arregloDatos);
			
		$arregloDatos['mostar'] = "1";
		$arregloDatos['plantilla'] = 'ubicacionListado.html';
		$arregloDatos['thisFunction'] = 'getListado';
		echo $this->pantalla->setFuncion($arregloDatos,$this->datos);		 				
	}          
} 	
?>