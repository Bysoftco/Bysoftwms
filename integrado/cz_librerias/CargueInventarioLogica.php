<?php

require_once("CargueInventarioDatos.php");
require_once("CargueInventarioPresentacion.php");
require_once("ReporteExcel.php");
require_once("Archivo.php");

class CargueInventarioLogica {

    var $datos;
    var $pantalla;
    var $adjuntados;
    var $no_adjuntados;

    function CargueInventarioLogica() {
        $this->datos = &new CargueInventario();

        $this->pantalla = &new CargueInventarioPresentacion($this->datos);
    }

    function mfiltro($arregloDatos) {
        $this->pantalla->mfiltro($arregloDatos);
    }

    function maestro($arregloDatos) {

        $this->pantalla->maestro($arregloDatos);
    }

    function listarCargueInventario($arregloDatos) {
        $this->titulo(&$arregloDatos);
        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = "CargueInventarioListado.html";
        $arregloDatos[thisFunction] = 'listarCargueInventario';
        $this->pantalla->setFuncion($arregloDatos, &$this->datos);
    }

    
    function filtroCargueInventario($arregloDatos) {
        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = "CargueInventarioFiltro.html";
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
        $arregloDatos['titulo'] = "Lista de CargueInventarios " . ucfirst($arregloDatos[titulo]);
        $arregloDatos['sql'] = $this->datos->listarCargueInventario($arregloDatos);

        $unExcel = new ReporteExcel($arregloDatos);
        $unExcel->generarExcel();
    }

    /*     * FUNCIONES PARA CARGAR BANCOS* */

    function maestroCarga($arregloDatos) {

        $this->pantalla->maestroCarga($arregloDatos);
    }
	
	function setInventario($arregloDatos) {
		 $this->datos->setInventario($arregloDatos);
		 // echo $arregloDatos[sql];
		 echo $arregloDatos[registro];
	}

    function filtrocarnArchivo($arregloDatos) {

        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = "CargueInventarioDocumentosCarga.html";
        $arregloDatos[thisFunction] = 'filtroCarga';
        $arregloDatos[thisFunctionAux] = 'filtroCarga';

        $this->pantalla->cargaPlantilla($arregloDatos, &$this->datos);
    }

    function uploadArchivoInventario($arregloDatos) {

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