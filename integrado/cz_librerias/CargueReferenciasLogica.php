<?php

require_once("CargueReferenciasDatos.php");
require_once("CargueReferenciasPresentacion.php");
require_once("ReporteExcel.php");
require_once("Archivo.php");

class CargueReferenciasLogica {

    var $datos;
    var $pantalla;
    var $adjuntados;
    var $no_adjuntados;

    function CargueReferenciasLogica() {
        $this->datos = &new CargueReferencias();

        $this->pantalla = &new CargueReferenciasPresentacion($this->datos);
    }

    function mfiltro($arregloDatos) {
        $this->pantalla->mfiltro($arregloDatos);
    }

    function maestro($arregloDatos) {

        $this->pantalla->maestro($arregloDatos);
    }

    function listarCargueReferencias($arregloDatos) {
        $this->titulo(&$arregloDatos);
        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = "CargueReferenciasListado.html";
        $arregloDatos[thisFunction] = 'listarCargueReferencias';
        $this->pantalla->setFuncion($arregloDatos, &$this->datos);
    }

    
    function filtroCargueReferencias($arregloDatos) {
        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = "CargueReferenciasFiltro.html";
        $arregloDatos[thisFunction] = 'filtro';
        $this->pantalla->setFuncion($arregloDatos, &$this->datos);
    }


    function titulo($arregloDatos) {
        $vartitulo = "Filtrado por: ";
        // 
        if (!empty($arregloDatos[numero])) {
            $vartitulo.="Numero de Documento " . $arregloDatos[numero];
        }
       
        $arregloDatos[titulo] = $vartitulo;
        return ucwords(strtolower($titulo));
    }

    function excel($arregloDatos) {

        $this->titulo(&$arregloDatos);
        $arregloDatos[excel] = 1;
        $arregloDatos['titulo'] = "Lista de CargueReferenciass " . ucfirst($arregloDatos[titulo]);
        $arregloDatos['sql'] = $this->datos->listarCargueReferencias($arregloDatos);

        $unExcel = new ReporteExcel($arregloDatos);
        $unExcel->generarExcel();
    }

    /*     * FUNCIONES PARA CARGAR BANCOS* */

    function maestroCarga($arregloDatos) {

        $this->pantalla->maestroCarga($arregloDatos);
    }
	
	function setReferencia($arregloDatos) {
		 $this->datos->setReferencia($arregloDatos);
		 // echo $arregloDatos[sql];
		 echo $arregloDatos[registro];
	}

    function filtrocarnArchivo($arregloDatos) {

        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = "CargueReferenciasDocumentosCarga.html";
        $arregloDatos[thisFunction] = 'filtroCarga';
        $arregloDatos[thisFunctionAux] = 'filtroCarga';

        $this->pantalla->cargaPlantilla($arregloDatos, &$this->datos);
    }

    function uploadArchivoBancos($arregloDatos) {

        $file = new Archivo();
        $error = 0;
        if ($arregloDatos[dejar_subir]) {
            $subir = true;
        } else {
            $subir = false;
        }
	
         $nombreCompleto="./integrado/_files/$arregloDatos[nombre_archivo]";
       	 $archivo = fopen("$nombreCompleto", "r");
         $arregloDatos[nomarchivo] = $file->nombre;
         $this->pantalla->crearPreuploadDocumentoscsv(&$arregloDatos, $archivo);
                    
               
    }


}

?>