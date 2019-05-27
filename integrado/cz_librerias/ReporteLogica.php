<?php
require_once("ReporteExcel.php");
require_once("ReporteDatos.php");
require_once("ReportePresentacion.php");



	
class ReporteLogica  {
    var $datos;
    var $pantalla;
		
    function ReporteLogica () {
        $this->datos = &new Reporte();
        $this->pantalla = &new ReportePresentacion($this->datos);
    }

    function filtroPrincipal($arregloDatos) {
        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = 'reportePrincipal.html';
        $arregloDatos[thisFunction] = 'filtronPrincipal';
        $this->pantalla->setFuncion($arregloDatos,$this->datos);
    }
			
	function maestroDefectuosa($arregloDatos) {
        $arregloDatos['accion'] = 'defectuosa';
        $arregloDatos['metodoEnvia'] = 'maestroDefectuosa';
        $arregloDatos['metodoAux'] = 'maestroConsulta';
        $this->pantalla->maestroDefectuosa($arregloDatos);
  	}
		
	function maestroAcondicionados($arregloDatos) {
        $arregloDatos['accion'] = 'acondicionados';
        $arregloDatos['metodoEnvia'] = 'maestroAcondicionados';
        $arregloDatos['metodoAux'] = 'maestroConsulta';
        $this->pantalla->maestroAcondicionados($arregloDatos);
    }
            
    function filtro($arregloDatos) {
        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = 'reporteFiltro.html';
        $arregloDatos[thisFunction] = 'filtro';
        $this->pantalla->setFuncion($arregloDatos,$this->datos);
    }
            
    function titulo($arregloDatos) {
        if(!empty($arregloDatos[cliente])) {
            $arregloDatos[titulo] ="Cliente ".$arregloDatos[cliente];
        }
                
        if(!empty($arregloDatos[fecha_inicio])) {
            $arregloDatos[titulo] =" Desde ".$arregloDatos[fecha_inicio]." Hasta.$arregloDatos[fecha_fin]";
        }
    }
            
    function getReporte($arregloDatos) {
        $this->titulo($arregloDatos);
        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = 'reporteReporteInventario.html';
        $arregloDatos[thisFunction] = 'inventario';
        $this->pantalla->setFuncion($arregloDatos,$this->datos);
    }
            
    function getReportePeso($arregloDatos) {
        $this->titulo($arregloDatos);
        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = 'reporteInventarioPeso.html';
        $arregloDatos[thisFunction] = 'getInventario';
        $this->pantalla->setFuncion($arregloDatos,$this->datos);
    }
            
    function getReporteCantidad($arregloDatos) {
        $this->titulo($arregloDatos);
        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = 'reporteInventarioCantidad.html';
        $arregloDatos[thisFunction] = 'getInventario';
        $this->pantalla->setFuncion($arregloDatos,$this->datos);
    }

    function getReporteValor($arregloDatos) {
        $this->titulo($arregloDatos);
        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = 'reporteInventarioValor.html';
        $arregloDatos[thisFunction] = 'getInventario';
        $this->pantalla->setFuncion($arregloDatos,$this->datos);
    }

    function generaExcel ($arregloDatos) {
        $arregloDatos[excel] = 1;
        $arregloDatos['titulo'] = 'Reporte '.$arregloDatos[titulo];
        $arregloDatos['sql'] = $this->datos->getInventario($arregloDatos);
        $unExcel = new ReporteExcel($arregloDatos);
        $unExcel->generarExcel();
    } 
            
    function getReporteEndosos($arregloDatos) {
        $this->titulo($arregloDatos);
        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = 'reporteEndosos.html';
        $arregloDatos[thisFunction] = 'getReporteEndosos';
        $this->pantalla->setFuncion($arregloDatos,$this->datos);
    }
			
    function listadoRechazadas($arregloDatos) {
        $this->pantalla->listadoRechazadas($arregloDatos);
    }
			
	function listadoAcondicionadas($arregloDatos) {
        $this->pantalla->listadoAcondicionadas($arregloDatos);
    }
			
    function excelAcondicionamientos($arregloDatos) {
        $this->datos->excelAcondicionamientos($arregloDatos);
    }
		
    function reporteExcelRechazadas($arregloDatos) {
        $this->datos->excelRechazadas($arregloDatos);			
	}
}
?>