<?php
require_once("SubpartidaDatos.php");
require_once("SubpartidaPresentacion.php");
//require_once("ReporteExcel.php");

class SubpartidaLogica {
  var $datos;
  var $pantalla;
		
  function SubpartidaLogica() {
    $this->datos = new Subpartida();
    $this->pantalla = new SubpartidaPresentacion($this->datos);
  }

  function filtro($arregloDatos) {
    $arregloDatos['mostrar'] = 1;
    $arregloDatos['plantilla'] = 'subpartidaFiltro.html';
    $arregloDatos['thisFunction'] = 'filtro';
    //$this->pantalla->setFuncion($arregloDatos,$this->datos);
    $this->pantalla->cargaPlantilla($arregloDatos);
  }
            
  function titulo($arregloDatos) {
    if(!empty($arregloDatos['cliente'])) {
      $arregloDatos['titulo'] = "Cliente ".$arregloDatos['cliente'];
    }
            
    if(!empty($arregloDatos['fecha_inicio'])) {
      $arregloDatos['titulo'] =" Desde ".$arregloDatos['fecha_inicio']." Hasta.$arregloDatos[fecha_fin]";
    }
  }
            
  function getSubpartida($arregloDatos) {
    $this->titulo($arregloDatos);
    $arregloDatos['mostrar'] = 1;
    $arregloDatos['plantilla'] = 'SubpartidaSubpartidaInventario.html';
    $arregloDatos['thisFunction'] = 'inventario';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

  function getUnaSubpartida($arregloDatos) {
    $arregloDatos['mostrar'] = 1;
    $arregloDatos['plantilla'] = 'subpartidaFormulario.html';
    $arregloDatos['thisFunction'] = 'getUnaSubpartida';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

	function updateSubpartida($arregloDatos) {
    $this->datos->updateSubpartida($arregloDatos);
	  
    $arregloDatos['mostar'] = "0";
    $arregloDatos['plantilla'] = 'subPartidaToolbar.html';
    $arregloDatos['thisFunction'] = 'getToolbar';
    //$arregloDatos['toolbarLevante']= $this->pantalla->setFuncion($arregloDatos, $this->datos);
    $arregloDatos['toolbarLevante'] = $this->pantalla->cargaPlantilla($arregloDatos);
		
    $arregloDatos['mostar'] = "1";
    $arregloDatos['plantilla'] = 'subpartidaListado.html';
    $arregloDatos['thisFunction'] = 'getListado';
    echo $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
			
	function findSupartida($arregloDatos) {
    $unaConsulta = new Subpartida();
    $arregloDatos['q'] = strtolower($_GET["q"]);
    $unaConsulta->findCliente($arregloDatos);

    $filas = $unaConsulta->db->countRows();
    while($obj=$unaConsulta->db->fetch()) {
    	$nombre = utf8_encode(trim($obj->descripcion));
    	echo "$nombre|$obj->subpartida|	$nombre\n";
    }
    if($filas == 0) {
    	echo "No hay Resultados|0\n";
    }
	}

  function generaExcel($arregloDatos) {
    $arregloDatos['excel'] = 1;
    $arregloDatos['titulo'] = 'Subpartida '.$arregloDatos['titulo'];
    $arregloDatos['sql'] = $this->datos->getInventario($arregloDatos);
    $unExcel = new SubpartidaExcel($arregloDatos);
    $unExcel->generarExcel();
  }

  function maestro($arregloDatos) {
    $this->pantalla->maestro($arregloDatos);
  }

  function getListado($arregloDatos) {
    $arregloDatos['mostar'] = "0";
    $arregloDatos['plantilla'] = 'subPartidaToolbar.html';
    $arregloDatos['thisFunction'] = 'getToolbar';
    //$arregloDatos['toolbarLevante']= $this->pantalla->setFuncion($arregloDatos, $this->datos);
    $arregloDatos['toolbarLevante']= $this->pantalla->cargaPlantilla($arregloDatos);				
						
    $arregloDatos['mostrar'] = 1;
    $arregloDatos['plantilla'] = 'subpartidaListado.html';
    $arregloDatos['thisFunction'] = 'getListado';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

  function getFormaNueva($arregloDatos) {
    $arregloDatos['plantilla'] = 'subpartidaFormNueva.html';
    $arregloDatos['thisFunction'] = 'getFormaNueva';
    //$this->pantalla->setFuncion($arregloDatos,$this->datos);
    $this->pantalla->cargaPlantilla($arregloDatos);
  }
			
  function addSubpartida($arregloDatos) {
    $this->datos->addSubpartida($arregloDatos);
		
    $arregloDatos['mostar'] = "0";
    $arregloDatos['plantilla'] = 'subPartidaToolbar.html';
    $arregloDatos['thisFunction'] = 'getToolbar';
    //$arregloDatos['toolbarLevante']= $this->pantalla->setFuncion($arregloDatos, $this->datos);
    $arregloDatos['toolbarLevante']= $this->pantalla->cargaPlantilla($arregloDatos);
		
    $arregloDatos['mostar'] = "1";
    $arregloDatos['plantilla'] = 'subpartidaListado.html';
    $arregloDatos['thisFunction'] = 'getListado';
    echo $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

  function deleteSubpartida($arregloDatos) {
    $this->datos->deleteSubpartida($arregloDatos);
            
    $arregloDatos['mostar'] = "0";
    $arregloDatos['plantilla'] = 'subPartidaToolbar.html';
    $arregloDatos['thisFunction'] = 'getToolbar';
    //$arregloDatos['toolbarLevante']= $this->pantalla->setFuncion($arregloDatos, $this->datos);
    $arregloDatos['toolbarLevante'] = $this->pantalla->cargaPlantilla($arregloDatos);
			
    $arregloDatos['mostar'] = "1";
    $arregloDatos['plantilla'] = 'subpartidaListado.html';
    $arregloDatos['thisFunction'] = 'getListado';
    echo $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
}			
?>