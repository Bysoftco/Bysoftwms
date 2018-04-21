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

    function editarCargueReferencias($arregloDatos) {


        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = "CargueReferenciasFormaActualizar.html";
        $arregloDatos[thisFunction] = 'listarCargueReferencias';
        $arregloDatos[band] = "1";
        $this->pantalla->setFuncion($arregloDatos, &$this->datos);
    }

    function rfiltroCargueReferencias($arregloDatos) {
        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = "CargueReferenciasFiltro.html";
        $arregloDatos[thisFunction] = 'filtro';
        $this->pantalla->setFuncion($arregloDatos, &$this->datos);
    }

    function updateCargueReferencias($arregloDatos) {

        $this->datos->updateCargueReferencias(&$arregloDatos);


        unset($arregloDatos[codigo_id]);


        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = "CargueReferenciasListado.html";
        $arregloDatos[thisFunction] = 'listarCargueReferencias';
        $this->pantalla->setFuncion($arregloDatos, &$this->datos);
    }

    function borrarCargueReferencias($arregloDatos) {

        $extr = new CargueReferencias();
        $extr->getCargueReferencias($arregloDatos);

        while ($extr->fetch()) {
            $arregloDatos[registrom] = $extr->Fecha . ', ' . $extr->tip_doc . ', ' . $extr->num_doc . ', ' . $extr->val_doc . ',' . $extr->cod_alu . ', ' . $extr->ano_ref . ', ' . $extr->per_ref . ', ' . $extr->tip_ref . ', ' . $extr->Tip_error . ', ' . $extr->des_error . ', ' . $extr->val_ext . ', ' . $extr->cod_con . ', ' . $extr->digito . ', ' . $extr->cod_suc . ', ' . $extr->cod_cco . ', ' . $extr->cod_cl1 . ', ' . $extr->semestr . ', ' . $extr->ciclo . ', ' . $extr->ano_alu . ', ' . $extr->cod_ban . ', ' . $extr->num_ext . ', ' . $extr->val_cons . ', ' . $extr->impuesto . ', ' . $extr->fec_proc . ', ' . $extr->descrip . ', ' . $extr->num_ing . ', ' . $extr->fec_ing . ', ' . $extr->ind_032 . ', ' . $extr->id_cxcpl;
        }
        $this->datos->borrarCargueReferencias(&$arregloDatos);
        unset($arregloDatos[codigo_id]);
      
        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = "CargueReferenciasListado.html";
        $arregloDatos[thisFunction] = 'listarCargueReferencias';
        $this->pantalla->setFuncion(&$arregloDatos, &$this->datos);
    }

    function updateExtConfirmar($arregloDatos) {

        $this->datos->updateExtConfirmar(&$arregloDatos);




        unset($arregloDatos[codigo_id]);
        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = "CargueReferenciasListado.html";
        $arregloDatos[thisFunction] = 'listarCargueReferencias';
        $this->pantalla->setFuncion($arregloDatos, &$this->datos);
    }

    function titulo($arregloDatos) {
        $vartitulo = "Filtrado por: ";
        // 
        if (!empty($arregloDatos[numero])) {
            $vartitulo.="Numero de Documento " . $arregloDatos[numero];
        }
        if (!empty($arregloDatos[fecha_inicio]) && !empty($arregloDatos[fecha_fin])) {
            $vartitulo.=" Fecha entre " . $arregloDatos[fecha_inicio] . " y " . $arregloDatos[fecha_fin];
        }
        if (!empty($arregloDatos[banco])) {
            $vartitulo.=" Banco " . $arregloDatos[banco];
        }
        if (!empty($arregloDatos[valor])) {
            $vartitulo.=" Valor " . $arregloDatos[valor];
        }
        $arregloDatos[titulo] = $vartitulo;
        return ucwords(strtolower($titulo));
    }

    function reporte($arregloDatos) {
        $titulo = "Reporte del CargueReferencias ";
        if (!empty($arregloDatos[desde])) {
            $titulo.=" $arregloDatos[desde] hasta $arregloDatos[hasta]";
        }

        $arregloDatos['titulo'] = $titulo;
        $arregloDatos['sql'] = $this->datos->reporte($arregloDatos);
        $unExcel = new ReporteExcel($arregloDatos);
        $unExcel->generarExcel();
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

        if ($file->subir('archivo', '_files/bancos/', $subir, false)) {
            $mensaje = 'El Archivo se subio correctamente en : ' . $file->nombreCompleto . "\n<br>";
            $mensaje .= "<pre>
				directorio           : $file->directorio
				archivo              : $file->nombre
				extension            : $file->extension
				tamaño               : $file->tamano
				ultima modificacion  : $file->fechaModificacion</pre>";

            if (strtolower($file->extension) != 'csv' && strtolower($file->extension) != 'xlsx' && strtolower($file->extension) != 'xls' && strtolower($file->extension) != 'fil' && strtolower($file->extension) != 'mrn') {
                $error = 1;
                $arregloDatos['mensaje'] = 'Formato inapropiado el archivo debe tener al guna de las siguientes extensiones: cvs, xlsx, xls';
            }
        } else {
            $error = 2;
            $arregloDatos['mensaje'] = 'Problemas al intentar subir el archivo, ' . $file->_error;
        }

        switch ($error) {
            case 0:
                //subir archhivo segun la extension
                if (strtolower($file->extension) == 'csv' && $arregloDatos[banco] == 6103) {

                    $resultado = strpos($file->nombre, '3081');
                    if ($resultado !== FALSE) {
                        $archivo = fopen("./$file->nombreCompleto", "r");
                        $arregloDatos[nomarchivo] = $file->nombre;
                        $this->pantalla->crearPreuploadDocumentoscsv(&$arregloDatos, $archivo);
                    } else {
                        $arregloDatos['estilo'] = 'ui-state-error';
                        $arregloDatos['mensaje'] = 'Debe seleccionar el archivo correspondiente para cada banco.';
                        $this->pantalla->maestroCarga(&$arregloDatos);
                    }
                } else if (strtolower($file->extension) == 'csv' && $arregloDatos[banco] == 6113) {
                    $resultado = strpos($file->nombre, '0751');
                    if ($resultado !== FALSE) {
                        $archivo = fopen("./$file->nombreCompleto", "r");
                        $arregloDatos[nomarchivo] = $file->nombre;
                        $this->pantalla->crearPreuploadDocumentoscsv1(&$arregloDatos, $archivo);
                    } else {
                        $arregloDatos['estilo'] = 'ui-state-error';
                        $arregloDatos['mensaje'] = 'Debe seleccionar el archivo correspondiente para cada banco.';
                        $this->pantalla->maestroCarga(&$arregloDatos);
                    }
                } else if (strtolower($file->extension) == 'fil' && $arregloDatos[banco] == 5012) {
                    $archivo = fopen("./$file->nombreCompleto", "r");
                    $arregloDatos[nomarchivo] = $file->nombre;
                    $this->pantalla->crearPreuploadDocumentoscsv2(&$arregloDatos, $archivo);
                } else if (strtolower($file->extension) == 'mrn' && $arregloDatos[banco] == 1317) {
                    $archivo = fopen("./$file->nombreCompleto", "r");
                    $arregloDatos[nomarchivo] = $file->nombre;
                    $this->pantalla->crearPreuploadDocumentoscsv3(&$arregloDatos, $archivo);
                } else if (strtolower($file->extension) == 'csv' && $arregloDatos[banco] == 1) {
                    $resultado = strpos($file->nombre, 'TransactionsReport');
                    if ($resultado !== FALSE) {
                        $archivo = fopen("./$file->nombreCompleto", "r");
                        $arregloDatos[nomarchivo] = $file->nombre;
                        $this->pantalla->crearPreuploadDocumentoscsv4(&$arregloDatos, $archivo);
                    } else {
                        $arregloDatos['estilo'] = 'ui-state-error';
                        $arregloDatos['mensaje'] = 'Debe seleccionar el archivo correspondiente para cada banco.';
                        $this->pantalla->maestroCarga(&$arregloDatos);
                    }
                } else if (strtolower($file->extension) == 'csv' && $arregloDatos[banco] == 2) {
                    $resultado = strpos($file->nombre, 'formato_estandar');
                    if ($resultado !== FALSE) {
                        $archivo = fopen("./$file->nombreCompleto", "r");
                        $arregloDatos[nomarchivo] = $file->nombre;
                        $this->pantalla->crearPreuploadDocumentoscsv5(&$arregloDatos, $archivo);
                    } else {
                        $arregloDatos['estilo'] = 'ui-state-error';
                        $arregloDatos['mensaje'] = 'Debe seleccionar el archivo correspondiente para cada banco.';
                        $this->pantalla->maestroCarga(&$arregloDatos);
                    }
                } else {
                    $arregloDatos['estilo'] = 'ui-state-error';
                    $arregloDatos['mensaje'] = 'Debe seleccionar el archivo correspondiente para cada banco.';

                    $this->pantalla->maestroCarga(&$arregloDatos);
                }

                break;
            case 1:
                $arregloDatos['estilo'] = 'ui-state-error';
                $arregloDatos['mensaje'] = 'Formato inapropiado el archivo debe tener al guna de las siguientes extensiones: cvs, xlsx,xls';
                $this->pantalla->maestroCarga(&$arregloDatos);
                break;
            case 2:
                $arregloDatos['estilo'] = 'ui-state-error';
                $arregloDatos['mensaje'] = 'Problemas al intentar subir el archivo, ' . $file->_error;
                $this->pantalla->maestroCarga(&$arregloDatos);
                break;
            default :
                $arregloDatos['estilo'] = 'ui-state-error';
                $arregloDatos['mensaje'] = 'El archivo ya se encuentra en el servidor';
                $this->pantalla->maestroCarga(&$arregloDatos);
        }
    }

    function crearDoc($arregloDatos) {
        $errores = 0;
        $res = 0;
        $arregloDatos[usuario] = $_SESSION['usuario'];

        foreach ($arregloDatos[carga] as $value) {
            $fecactual=FECHAACTUALTIME;
            $valores = explode(',', $value);
            $fecha = $valores[0];
            $tip_doc = $valores[1];
            $num_doc = $valores[2];
            
            if(substr($num_doc, 0, 2)== '40'){
                $num_doc='1'.$num_doc;
            }
            
            $val_doc = $valores[3];
            $tip_error = $valores[4];
            $des_error = $valores[5];
            $val_ext = $valores[6];
            $digito = $valores[7];
            $cod_ban = $valores[8];
            $tip_docnum_doc = $valores[9];
            $val_cons = $valores[10];
            $impuesto = $valores[11];
            $fec_proc = $valores[12];
            $descrip = $fecactual.'-'.$valores[13];
            $fec_ing = $valores[14];
            $ind_032 = $valores[15];


            $x = $this->datos->nuevodoc($fecha, $tip_doc, $num_doc
                    , $val_doc, $tip_error, $des_error, $val_ext
                    , $digito, $cod_ban, $tip_docnum_doc, $val_cons, $impuesto, $fec_proc, $descrip, $fec_ing, $ind_032);

            if (!$x) {
                $errores++;
            }
        }
        if ($errores >= 1) {
            $arregloDatos[mensaje] = 'La informacion no se cargo por errores en la insercion de los datos !';
            $arregloDatos[estilo] = 'ui-state-highlight';
        } else {
            $arregloDatos[mensaje] = 'La informacion se cargo Satisfactoriamente!';
            $arregloDatos[estilo] = 'ui-state-highlight';
        }

        $this->pantalla->maestroCarga(&$arregloDatos);
        //$this->filtrocarnArchivo($arregloDatos);
    }

    /** FIN* */
}

?>