<?php

require_once ("HTML/Template/IT.php");
require_once ("Funciones.php");
require_once ("CargueReferenciasDatos.php");

class CargueReferenciasPresentacion {

    var $datos;
    var $plantilla;
    var $total;

    function CargueReferenciasPresentacion(&$datos) {
        $this->datos = &$datos;
        $this->plantilla = new HTML_Template_IT();
        $this->mensaje_color;
    }

    //Funcion que coloca los datos que vienen del formulario
    function mantenerDatos($arregloCampos, &$plantilla) {
        //var_dump($arregloCampos);  
        $plantilla = &$plantilla;
        if (is_array($arregloCampos)) {
            foreach ($arregloCampos as $key => $value) {

                $plantilla->setVariable($key, $value);
            }
        }
    }

    //Funcion que coloca los datos que vienen de la BD
    function setDatos($arregloDatos, &$datos, &$plantilla) {

        foreach ($datos as $key => $value) {

            $plantilla->setVariable($key, $value);
        }
    }

    function getLista($arregloDatos, $seleccion, &$plantilla) {

        $unaLista = new Cheques();
        $lista = $unaLista->lista($arregloDatos[tabla], $arregloDatos[condicion], $arregloDatos[campoCondicion]);
        $lista = armaSelect($lista, $seleccion, '[seleccione]');
        $plantilla->setVariable($arregloDatos[labelLista], $lista);
    }

    function cargaPlantilla($arregloDatos) {

        $unAplicaciones = new CargueReferencias();
        $formularioPlantilla = new HTML_Template_IT();
        $formularioPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla], false, false);
        $formularioPlantilla->setVariable('comodin', ' ');
        $this->mantenerDatos($arregloDatos, &$formularioPlantilla);

        $this->$arregloDatos[thisFunction]($arregloDatos, $this->datos, $formularioPlantilla);
        if ($arregloDatos[mostrar]) {
            $formularioPlantilla->show(); //ajax
        } else {
            return $formularioPlantilla->get(); // php
        }
    }

    // Arma cada Formulario o funcion en pantalla
    function setFuncion($arregloDatos, $unDatos) {
        $unDatos = new CargueReferencias();
        header('Content-type: text/html; charset=iso-8859-1');
        $unDatos->$arregloDatos[thisFunction]($arregloDatos);
        //var_dump($arregloDatos);
        $this->plantilla->setVariable('mensaje', $arregloDatos[mensaje]);
        $this->plantilla->setVariable('estilo', $arregloDatos[estilo]);


        $unaPlantilla = new HTML_Template_IT();
        $unaPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla], true, true);
        $unaPlantilla->setVariable('comodin', " ");
        $this->mantenerDatos($arregloDatos, $unaPlantilla);
        if ($arregloDatos[thisFunctionAux]) {
            //$unDatos->fetch();  no puede ir aqui
            $this->$arregloDatos[thisFunctionAux]($arregloDatos, &$unDatos, $unaPlantilla);
        }
        $n = 0;
        while ($unDatos->fetch()) {
            if ($n % 2) {
                $odd = 'odd';
            } else {
                $odd = '';
            }
            $n = $n + 1;
            $unaPlantilla->setCurrentBlock('ROW');
            $this->setDatos($arregloDatos, $unDatos, $unaPlantilla);
            $this->$arregloDatos[thisFunction]($arregloDatos, $unDatos, $unaPlantilla);
            $unaPlantilla->setVariable('n', $n);
            $unaPlantilla->setVariable('odd', $odd);
            $unaPlantilla->parseCurrentBlock();
        }
        if ($unDatos->N == 0) {
            $unaPlantilla->setVariable('mensaje', 'No hay registros para listar ');
            $unaPlantilla->setVariable('estilo', 'ui-state-error');
        }
        $unaPlantilla->setVariable('num_registros', $unDatos->N);

        if ($arregloDatos[mostrar]) {

            $unaPlantilla->show();
        } else {
            return $unaPlantilla->get();
        }
    }

    function maestro($arregloDatos) {
        $this->plantilla->loadTemplateFile(PLANTILLAS . 'cargueReferenciasMaestro.html', false, false);

        $arregloDatos[mostrar] = 0; //  en php o ajax

        $arregloDatos[plantilla] = 'cargueReferenciasFiltro.html'; //plantilla a la que me dirijo

        $arregloDatos[thisFunction] = 'filtro'; // funcion que debe estar en presentacion y datos

        $htmlCargueReferenciasFormulario = $this->cargaPlantilla($arregloDatos, &$unDatos); //variable a la que le asigno la plantilla

        $this->plantilla->setVariable('filtro_entrada', $htmlCargueReferenciasFormulario); //funcion que recibe nombre etiqueta donde carga{xxx} y la plantilla
        //Setvariable es la que carga la plantilla en las llaves que se ponen en la ventana
        $this->plantilla->show(); //muestra la ventana
    }

    function filtro($arregloDatos, $datos, $formularioPlantilla) {
        
    }

    function listarCargueReferencias($arregloDatos, $datos, $formularioPlantilla) {

        $arregloDatos[num_doc] = rtrim($datos->num_doc);
        $arregloDatos[val_doc] = rtrim($datos->val_doc);
        if ($datos->Tip_error == 1) {
            $arregloDatos[borrarreg] = '<a href="javascript: borrar(' . $datos->id_cxcpl . ')"   title="Borrar registro" style="display:' . $arregloDatos[ocultarLink] . ';"  cursor> <img src="imagenes/borrar.png" border="0"  > </a> ';
        }
        $arregloDatos[des_error] = rtrim($datos->des_error);
        if ($arregloDatos[band] != "1") {
            $valor = $datos->val_doc;
            $arregloDatos[val_doc] = number_format($valor);
        }

        $this->mantenerDatos($arregloDatos, &$formularioPlantilla);
    }

    function filtroConsulta($arregloDatos) {
        $unEstado = new CargueReferencias();
        $estado = $unEstado->getEstados();
        $selectEstado = armaSelect($estado, NULL, '[Estado]');
        $this->plantilla->loadTemplateFile(PLANTILLAS . 'CargueReferenciasFiltroReporte.html', false, false);
        $this->plantilla->setVariable('selectEstado', $selectEstado);
        $this->plantilla->show();
    }

    function updateExtConfirmar($arregloDatos, $datos, $formularioPlantilla) {
        $this->mantenerDatos($arregloDatos, &$formularioPlantilla);
    }

    /*     * FUNCIONES PARA CARGAR BANCOS* */

    function maestroCarga($arregloDatos) {

        $this->plantilla->loadTemplateFile(PLANTILLAS . 'CargueReferenciasCargaMaestro.html', true, true);
        $arregloDatos[mostrar] = 0;
        $arregloDatos[abrirv] = '1';
        $this->mantenerDatos($arregloDatos, $this->plantilla);
        $arregloDatos[plantilla] = 'CargueReferenciasDocumentosCarga.html';
        $arregloDatos[thisFunction] = 'filtroCarga';

        $htmlFormulario = $this->cargaPlantilla($arregloDatos, &$unDatos);
        $this->plantilla->setVariable('entrada', $htmlFormulario);
        $this->plantilla->show();
    }

    function filtroCarga($arregloDatos, $datos, $plantilla) {
        $sedes = new CargueReferencias();
        $lista = $sedes->listadoBancos();

        $lista = armaSelect($lista, NULL, '[seleccione]');
        $plantilla->setVariable('selectBanco', $lista);
        $this->mantenerDatos($arregloDatos, $plantilla);
    }

    function crearPreuploadDocumentoscsv($arregloDatos, $archivo) {
        $this->plantilla->loadTemplateFile(PLANTILLAS . 'CargueReferenciasCargaMaestro.html', true, true);
        $arregloDatos[mostrar] = 0;
        $htmlFormulario = $this->obtendatos($arregloDatos, $archivo);
        $this->plantilla->setVariable('auxt', $htmlFormulario);
        $this->plantilla->show();
    }

    function obtendatos($arregloDatos, $archivo) {
        //var_dump($arregloDatos);
        $unaPlantilla = new HTML_Template_IT();
        $unaPlantilla->loadTemplateFile(PLANTILLAS . 'CargueReferenciasDocumentosListadoCarga.html', true, true);

        $n = 0;
        $status = '';
        $valfor = 0;
        $cuentaError = 0;
        $cont = 0;
        while (!feof($archivo)) {

            list($Fecha_aplicacion, $Fecha_transaccion, $Descripcion, $documento,
                    $Origen, $Valor_Total, $Valor_Efectivo, $Valor_Cheque, $Debito_Credito,
                    $Codigo_Transaccion) = explode(",", $linea);
            $linea = fgets($archivo, 4092);
            $estado = '';
            if ($cont >= 3) {

                if ($n % 2) {
                    $odd = 'odd';
                } else {
                    $odd = '';
                }
                $n = $n + 1;
                $unaPlantilla->setCurrentBlock('ROW');

                $arregloDatos[n] = $n;
                //$Fecha=substr($Fecha_transaccion,6, 2).'/'.substr($Fecha_transaccion, 4, 2).'/'.substr($Fecha_transaccion, 0, 4);
                //$arregloDatos[fecha]=$Fecha;
                $Fecha = substr($Fecha_transaccion, 0, 4) . '/' . substr($Fecha_transaccion, 4, 2) . '/' . substr($Fecha_transaccion, 6, 2);
                $arregloDatos[fecha] = $Fecha;
                $mes = date('m', strtotime($Fecha));
                $annio = date('Y', strtotime($Fecha));
                if ($annio < date('Y')) {
                    $arregloDatos[alerta] = 'La fecha de este registro es del año anterior.';
                    $arregloDatos[color] = "color:#00F;";
                } else if ($annio > date('Y')) {
                    $arregloDatos[alerta] = 'La fecha del registro no puede ser mayor a la fecha actual.';
                    $arregloDatos[color] = "color:#F00;";
                    $cuentaError = 1;
                } else if ($annio == date('Y') && $mes == date('m')) {
                    $arregloDatos[alerta] = '';
                    $arregloDatos[color] = "";
                } else if ($annio == date('Y') && $mes < date('m')) {
                    $arregloDatos[alerta] = 'La fecha de este registro es de meses anteriores.';
                    $arregloDatos[color] = "color:#00F;";
                } else if ($annio == date('Y') && $mes > date('m')) {
                    $arregloDatos[alerta] = 'La fecha de este registro es de un mes posterior.';
                    $arregloDatos[color] = "color:#F00;";
                    $cuentaError = 1;
                } else {
                    unset($arregloDatos[alerta]);
                }

                if (substr($documento, 0, 1) == 8 || substr($documento, 0, 1) == 9) {
                    $tip_doc = '04' . substr($documento, 0, 1);
                } else if (substr($documento, 0, 1) == 1) {
                    $tip_doc = '02' . substr($documento, 0, 1);
                } else if (substr($documento, 0, 1) == 2) {
                    $tip_doc = '03' . substr($documento, 0, 1);
                } else {
                    $tip_doc = substr($documento, 0, 1);
                }
                $arregloDatos[tip_doc] = $tip_doc;

                $num_doc = substr($documento, 1, 10);
                $arregloDatos[num_doc] = $num_doc;




                $val_doc = $Valor_Total;
                $arregloDatos[val_doc] = $val_doc;
                $arregloDatos[val_doc1] = number_format(trim($val_doc));

                $tip_error = 1;
                $arregloDatos[tip_error] = $tip_error;

                $des_error = '';
                $arregloDatos[des_error] = $des_error;

                $val_ext = 0;
                $arregloDatos[val_ext] = $val_ext;

                $digito = substr($documento, 11, 12);
                $arregloDatos[digito] = $digito;

                $cod_ban = $arregloDatos[banco];
                $arregloDatos[cod_ban] = $cod_ban;

                $tip_docnum_doc = $tip_doc . $num_doc;
                $arregloDatos[tip_docnum_doc] = $tip_docnum_doc;

                $val_cons = $val_doc;
                $arregloDatos[val_cons] = $val_cons;
                $arregloDatos[val_cons1] = number_format(trim($val_cons));

                $impuesto = 0;
                $arregloDatos[impuesto] = $impuesto;

                $fec_proc = FECHAACTUAL;
                $arregloDatos[fec_proc] = $fec_proc;

                $descrip = $arregloDatos[nomarchivo];
                $arregloDatos[descrip] = $descrip;

                $fec_ing = $Fecha;
                $arregloDatos[fec_ing] = $fec_ing;

                $ind_032 = 0;
                $arregloDatos[ind_032] = $ind_032;

                $unaPlantilla->parseCurrentBlock();
                $arregloDatos[odd] = $odd;
                $this->mantenerDatos($arregloDatos, $unaPlantilla);
            }

            $cont = $cont + 1;
        }
        if ($cuentaError == 1) {
            $arregloDatos[mostrarBotonCrear] = 'none';
            $arregloDatos[carg] = '0';
        } else {
            $arregloDatos[mostrarBotonCrear] = 'block';
            $arregloDatos[carg] = '1';
        }

        $this->mantenerDatos($arregloDatos, $unaPlantilla);

        return $unaPlantilla->get();
    }

    function crearPreuploadDocumentoscsv1($arregloDatos, $archivo) {
        $this->plantilla->loadTemplateFile(PLANTILLAS . 'CargueReferenciasCargaMaestro.html', true, true);
        $arregloDatos[mostrar] = 0;
        $htmlFormulario = $this->obtendatos1($arregloDatos, $archivo);
        $this->plantilla->setVariable('auxt', $htmlFormulario);
        $this->plantilla->show();
    }

    function obtendatos1($arregloDatos, $archivo) {
        $unaPlantilla = new HTML_Template_IT();
        $unaPlantilla->loadTemplateFile(PLANTILLAS . 'CargueReferenciasDocumentosListadoCarga.html', true, true);

        $n = 0;
        $status = '';
        $valfor = 0;
        $cuentaError = 0;
        $cont = 0;
        while (!feof($archivo)) {

            list($Fecha_aplicacion, $Fecha_transaccion, $Descripcion, $documento,
                    $Origen, $Valor_Total, $Valor_Efectivo, $Valor_Cheque, $Debito_Credito,
                    $Codigo_Transaccion) = explode(",", $linea);
            $linea = fgets($archivo, 4092);
            $estado = '';
            if ($cont >= 3) {

                if ($n % 2) {
                    $odd = 'odd';
                } else {
                    $odd = '';
                }
                $n = $n + 1;
                $unaPlantilla->setCurrentBlock('ROW');

                $arregloDatos[n] = $n;
                $Fecha = substr($Fecha_transaccion, 0, 4) . '/' . substr($Fecha_transaccion, 4, 2) . '/' . substr($Fecha_transaccion, 6, 2);
                $arregloDatos[fecha] = $Fecha;
                $mes = date('m', strtotime($Fecha));
                $annio = date('Y', strtotime($Fecha));
                if ($annio < date('Y')) {
                    $arregloDatos[alerta] = 'La fecha de este registro es del año anterior.';
                    $arregloDatos[color] = "color:#00F;";
                } else if ($annio > date('Y')) {
                    $arregloDatos[alerta] = 'La fecha del registro no puede ser mayor a la fecha actual.';
                    $arregloDatos[color] = "color:#F00;";
                    $cuentaError = 1;
                } else if ($annio == date('Y') && $mes == date('m')) {
                    $arregloDatos[alerta] = '';
                    $arregloDatos[color] = "";
                } else if ($annio == date('Y') && $mes < date('m')) {
                    $arregloDatos[alerta] = 'La fecha de este registro es de meses anteriores.';
                    $arregloDatos[color] = "color:#00F;";
                } else if ($annio == date('Y') && $mes > date('m')) {
                    $arregloDatos[alerta] = 'La fecha de este registro es de un mes posterior.';
                    $arregloDatos[color] = "color:#F00;";
                    $cuentaError = 1;
                } else {
                    unset($arregloDatos[alerta]);
                }

                if (substr($documento, 0, 1) == 8 || substr($documento, 0, 1) == 9) {
                    $tip_doc = '04' . substr($documento, 0, 1);
                } else if (substr($documento, 0, 1) == 1) {
                    $tip_doc = '02' . substr($documento, 0, 1);
                } else if (substr($documento, 0, 1) == 2) {
                    $tip_doc = '03' . substr($documento, 0, 1);
                } else {
                    $tip_doc = substr($documento, 0, 1);
                }
                $arregloDatos[tip_doc] = $tip_doc;

                $num_doc = substr($documento, 1, 10);
                $arregloDatos[num_doc] = $num_doc;

                $val_doc = $Valor_Total;
                $arregloDatos[val_doc] = $val_doc;
                $arregloDatos[val_doc1] = number_format(trim($val_doc));

                $tip_error = 1;
                $arregloDatos[tip_error] = $tip_error;

                $des_error = '';
                $arregloDatos[des_error] = $des_error;

                $val_ext = 0;
                $arregloDatos[val_ext] = $val_ext;

                $digito = substr($documento, 11, 12);
                $arregloDatos[digito] = $digito;

                $cod_ban = $arregloDatos[banco];
                $arregloDatos[cod_ban] = $cod_ban;

                $tip_docnum_doc = $tip_doc . $num_doc;
                $arregloDatos[tip_docnum_doc] = $tip_docnum_doc;

                $val_cons = $val_doc;
                $arregloDatos[val_cons] = $val_cons;
                $arregloDatos[val_cons1] = number_format(trim($val_cons));

                $impuesto = 0;
                $arregloDatos[impuesto] = $impuesto;

                $fec_proc = FECHAACTUAL;
                $arregloDatos[fec_proc] = $fec_proc;

                $descrip = $arregloDatos[nomarchivo];
                $arregloDatos[descrip] = $descrip;

                $fec_ing = $Fecha;
                $arregloDatos[fec_ing] = $fec_ing;

                $ind_032 = 0;
                $arregloDatos[ind_032] = $ind_032;

                $unaPlantilla->parseCurrentBlock();
                $arregloDatos[odd] = $odd;
                $this->mantenerDatos($arregloDatos, $unaPlantilla);
            }

            $cont = $cont + 1;
        }
        if ($cuentaError == 1) {
            $arregloDatos[mostrarBotonCrear] = 'none';
            $arregloDatos[carg] = '0';
        } else {
            $arregloDatos[mostrarBotonCrear] = 'block';
            $arregloDatos[carg] = '1';
        }
        $this->mantenerDatos($arregloDatos, $unaPlantilla);

        return $unaPlantilla->get();
    }

    function crearPreuploadDocumentoscsv2($arregloDatos, $archivo) {
        $this->plantilla->loadTemplateFile(PLANTILLAS . 'CargueReferenciasCargaMaestro.html', true, true);
        $arregloDatos[mostrar] = 0;
        $htmlFormulario = $this->obtendatos2($arregloDatos, $archivo);
        $this->plantilla->setVariable('auxt', $htmlFormulario);
        $this->plantilla->show();
    }

    function obtendatos2($arregloDatos, $archivo) {
        $unaPlantilla = new HTML_Template_IT();
        $unaPlantilla->loadTemplateFile(PLANTILLAS . 'CargueReferenciasDocumentosListadoCarga.html', true, true);
        /* CREACION DE VECTORES CONSULTA */
        $unaValidacion = new CargueReferencias();
        $pagos = $unaValidacion->listadoPagos();

        /*         * ******************************** */
        $n = 0;
        $status = '';
        $valfor = 0;
        $cuentaError = 0;
        $cont = 0;
        while (!feof($archivo)) {
            //fecha     x      y    z     documento   d  valor total  t       m
            //10012014,20412,CAJA,LOCAL,02110214177752,,3281000.00,  Canje, 759.22
            list($Fecha_transaccion, $x, $y, $z, $documento, $d, $Valor_Total, $t, $t) = explode(",", $linea);
            $linea = fgets($archivo, 4092);
            $estado = '';

            if ($Fecha_transaccion != '') {


                if ($n % 2) {
                    $odd = 'odd';
                } else {
                    $odd = '';
                }
                $n = $n + 1;
                $unaPlantilla->setCurrentBlock('ROW');

                $arregloDatos[n] = $n;

                $Fecha = substr($Fecha_transaccion, 4, 4) . '/' . substr($Fecha_transaccion, 2, 2) . '/' . substr($Fecha_transaccion, 0, 2);
                $arregloDatos[fecha] = $Fecha;
                $mes = date('m', strtotime($Fecha));
                $annio = date('Y', strtotime($Fecha));
                if ($annio < date('Y')) {
                    $arregloDatos[alerta] = 'La fecha de este registro es del año anterior.';
                    $arregloDatos[color] = "color:#00F;";
                } else if ($annio > date('Y')) {
                    $arregloDatos[alerta] = 'La fecha del registro no puede ser mayor a la fecha actual.';
                    $arregloDatos[color] = "color:#F00;";
                    $cuentaError = 1;
                } else if ($annio == date('Y') && $mes == date('m')) {
                    $arregloDatos[alerta] = '';
                    $arregloDatos[color] = "";
                } else if ($annio == date('Y') && $mes < date('m')) {
                    $arregloDatos[alerta] = 'La fecha de este registro es de meses anteriores.';
                    $arregloDatos[color] = "color:#00F;";
                } else if ($annio == date('Y') && $mes > date('m')) {
                    $arregloDatos[alerta] = 'La fecha de este registro es de un mes posterior.';
                    $arregloDatos[color] = "color:#F00;";
                    $cuentaError = 1;
                } else {
                    unset($arregloDatos[alerta]);
                }

                $tip_doc = substr($documento, 0, 3);
                $arregloDatos[tip_doc] = $tip_doc;

                $num_doc = substr($documento, 3, 10);
                $arregloDatos[num_doc] = $num_doc;

                $cmp = $Fecha . $tip_doc . $num_doc;
                foreach ($pagos as $clave => $valor) {
                    if ($valor == $cmp) {
                        $arregloDatos[color] = "color:#F00;";
                        $arregloDatos[msj] = 'El documento YA EXISTE.';
                    }
                }



                $val_doc = $Valor_Total;
                $arregloDatos[val_doc] = $val_doc;
                $arregloDatos[val_doc1] = number_format(trim($val_doc));

                $tip_error = 1;
                $arregloDatos[tip_error] = $tip_error;

                $des_error = '';
                $arregloDatos[des_error] = $des_error;

                $val_ext = 0;
                $arregloDatos[val_ext] = $val_ext;

                $digito = substr($documento, 13, 14);
                $arregloDatos[digito] = $digito;

                $cod_ban = $arregloDatos[banco];
                $arregloDatos[cod_ban] = $cod_ban;

                $tip_docnum_doc = $tip_doc . $num_doc;
                $arregloDatos[tip_docnum_doc] = $tip_docnum_doc;

                $val_cons = $val_doc;
                $arregloDatos[val_cons] = $val_cons;
                $arregloDatos[val_cons1] = number_format(trim($val_cons));

                $impuesto = 0;
                $arregloDatos[impuesto] = $impuesto;

                $fec_proc = FECHAACTUAL;
                $arregloDatos[fec_proc] = $fec_proc;

                $descrip = $arregloDatos[nomarchivo];
                $arregloDatos[descrip] = $descrip;

                $fec_ing = $Fecha;
                $arregloDatos[fec_ing] = $fec_ing;

                $ind_032 = 0;
                $arregloDatos[ind_032] = $ind_032;

                $unaPlantilla->parseCurrentBlock();
                $arregloDatos[odd] = $odd;
                $this->mantenerDatos($arregloDatos, $unaPlantilla);
            }
        }
        $arregloDatos[mostrarBotonCrear] = 'block';
        $arregloDatos[carg] = '1';
        $this->mantenerDatos($arregloDatos, $unaPlantilla);

        return $unaPlantilla->get();
    }

    function crearPreuploadDocumentoscsv3($arregloDatos, $archivo) {
        $this->plantilla->loadTemplateFile(PLANTILLAS . 'CargueReferenciasCargaMaestro.html', true, true);
        $arregloDatos[mostrar] = 0;
        $htmlFormulario = $this->obtendatos3($arregloDatos, $archivo);
        $this->plantilla->setVariable('auxt', $htmlFormulario);
        $this->plantilla->show();
    }

    function obtendatos3($arregloDatos, $archivo) {
        $unaPlantilla = new HTML_Template_IT();
        $unaPlantilla->loadTemplateFile(PLANTILLAS . 'CargueReferenciasDocumentosListadoCarga.html', true, true);

        $n = 0;
        $status = '';
        $valfor = 0;
        $cuentaError = 0;
        $cont = 0;
        while (!feof($archivo)) {
            if ($cont >= 3) {
                if ($n % 2) {
                    $odd = 'odd';
                } else {
                    $odd = '';
                }

                $unaPlantilla->setCurrentBlock('ROW');

                $arregloDatos[n] = $n;

                //list($Fecha_transaccion,$x,$y,$z,$documento,$d,$Valor_Total,$t,$t) = explode(",", $linea);
                $linea = fgets($archivo, 4092);

                if (strlen($linea) > 135) {
                    $resultado = strpos($linea, 'TF');
                    if ($resultado !== FALSE) {
                        $n = $n + 1;
                        $Fecha = substr($linea, 2, 4) . '/' . substr($linea, 6, 2) . '/' . substr($linea, 8, 2);
                        $arregloDatos[fecha] = $Fecha;

                        $Fecha1 = substr($linea, 2, 4) . '/' . substr($linea, 6, 2) . '/' . substr($linea, 8, 2);
                        $arregloDatos[fecha1] = $Fecha1;



                        $mes = date('m', strtotime($Fecha));
                        $annio = date('Y', strtotime($Fecha));
                        if ($annio < date('Y')) {
                            $arregloDatos[alerta] = 'La fecha de este registro es del año anterior.';
                            $arregloDatos[color] = "color:#00F;";
                        } else if ($annio > date('Y')) {
                            $arregloDatos[alerta] = 'La fecha del registro no puede ser mayor a la fecha actual.';
                            $arregloDatos[color] = "color:#F00;";
                            $cuentaError = 1;
                        } else if ($annio == date('Y') && $mes == date('m')) {
                            $arregloDatos[alerta] = '';
                            $arregloDatos[color] = "";
                        } else if ($annio == date('Y') && $mes < date('m')) {
                            $arregloDatos[alerta] = 'La fecha de este registro es de meses anteriores.';
                            $arregloDatos[color] = "color:#00F;";
                        } else if ($annio == date('Y') && $mes > date('m')) {
                            $arregloDatos[alerta] = 'La fecha de este registro es de un mes posterior.';
                            $arregloDatos[color] = "color:#F00;";
                            $cuentaError = 1;
                        } else {
                            unset($arregloDatos[alerta]);
                        }

                        $tip_doc = substr($linea, 101, 3);
                        $arregloDatos[tip_doc] = $tip_doc;

                        $num_doc = substr($linea, 104, 10);
                        $arregloDatos[num_doc] = $num_doc;

                        $Valor_Total = substr($linea, 53, 12);
                        $val_doc = (int) $Valor_Total;
                        $arregloDatos[val_doc] = $val_doc;
                        $arregloDatos[val_doc1] = number_format(trim($val_doc));

                        $tip_error = 1;
                        $arregloDatos[tip_error] = $tip_error;

                        $des_error = '';
                        $arregloDatos[des_error] = $des_error;

                        $val_ext = 0;
                        $arregloDatos[val_ext] = $val_ext;

                        $digito = substr($linea, 114, 1);
                        $arregloDatos[digito] = $digito;

                        $cod_ban = $arregloDatos[banco];
                        $arregloDatos[cod_ban] = $cod_ban;

                        $tip_docnum_doc = $tip_doc . $num_doc;
                        $arregloDatos[tip_docnum_doc] = $tip_docnum_doc;

                        $val_cons = $val_doc;
                        $arregloDatos[val_cons] = $val_cons;
                        $arregloDatos[val_cons1] = number_format(trim($val_cons));

                        $impuesto = 0;
                        $arregloDatos[impuesto] = $impuesto;

                        $fec_proc = FECHAACTUAL;
                        $arregloDatos[fec_proc] = $fec_proc;

                        $descrip = $arregloDatos[nomarchivo];
                        $arregloDatos[descrip] = $descrip;

                        $fec_ing = $Fecha;
                        $arregloDatos[fec_ing] = $fec_ing;

                        $ind_032 = 0;
                        $arregloDatos[ind_032] = $ind_032;

                        $unaPlantilla->parseCurrentBlock();
                        $arregloDatos[odd] = $odd;
                        $this->mantenerDatos($arregloDatos, $unaPlantilla);
                    }
                }
            }

            $cont = $cont + 1;
        }
        if ($cuentaError == 1) {
            $arregloDatos[mostrarBotonCrear] = 'none';
            $arregloDatos[carg] = '0';
        } else {
            $arregloDatos[mostrarBotonCrear] = 'block';
            $arregloDatos[carg] = '1';
        }
        $this->mantenerDatos($arregloDatos, $unaPlantilla);

        return $unaPlantilla->get();
    }

    function crearPreuploadDocumentoscsv4($arregloDatos, $archivo) {
        $this->plantilla->loadTemplateFile(PLANTILLAS . 'CargueReferenciasCargaMaestro.html', true, true);
        $arregloDatos[mostrar] = 0;
        $htmlFormulario = $this->obtendatos4($arregloDatos, $archivo);
        $this->plantilla->setVariable('auxt', $htmlFormulario);
        $this->plantilla->show();
    }

    function obtendatos4($arregloDatos, $archivo) {
        //var_dump($arregloDatos);
        $unaPlantilla = new HTML_Template_IT();
        $unaPlantilla->loadTemplateFile(PLANTILLAS . 'CargueReferenciasDocumentosListadoCarga.html', true, true);

        $n = 0;
        $status = '';
        $valfor = 0;
        $cuentaError = 0;
        $cont = 0;
        while (!feof($archivo)) {
            $linea = fgets($archivo, 4092);

            $resultado = strpos($linea, ',');
            if ($resultado !== FALSE) {
                list($TicketId, $Reference1, $TransValue, $EtyCode, $SrvDesc, $SrvCode, $EtyName, $SoliciteDate, $PaymentSystem,
                        $FIName, $StaCode, $Notified, $TrazabilityCode, $UserMail, $Alert, $Invoice, $UserID) = explode(",", $linea);
            } else {
                list($TicketId, $Reference1, $TransValue, $EtyCode, $SrvDesc, $SrvCode, $EtyName, $SoliciteDate, $PaymentSystem,
                        $FIName, $StaCode, $Notified, $TrazabilityCode, $UserMail, $Alert, $Invoice, $UserID) = explode(";", $linea);
            }

//echo $StaCode.' '.$num_doc. ' '.$TransValue.'<BR>';
//echo    $linea.'<BR>';

            $estado = '';
            if ($StaCode == 'OK') {




                if ($n % 2) {
                    $odd = 'odd';
                } else {
                    $odd = '';
                }
                $n = $n + 1;
                $unaPlantilla->setCurrentBlock('ROW');

                $arregloDatos[n] = $n;
                if ($resultado !== FALSE) {

                    $SoliciteDate = explode('/', $SoliciteDate);
                    if ($SoliciteDate[0] < 10) {
                        $mes = '0' . $SoliciteDate[0];
                    } else {
                        $mes = $SoliciteDate[0];
                    }
                    if ($SoliciteDate[1] < 10) {
                        $dia = '0' . $SoliciteDate[1];
                    } else {
                        $dia = $SoliciteDate[1];
                    }
                    $annio = substr($SoliciteDate[2], 0, 4);

                    $Fecha = $annio . '/' . $mes . '/' . $dia;
                    $arregloDatos[fecha] = $Fecha;
                } else {
                    /* $SoliciteDate=  trim($SoliciteDate);
                      $Fecha=$date->format('Y/m/d');
                      $arregloDatos[fecha]=$Fecha; */

                    $SoliciteDate = explode('/', $SoliciteDate);

                    $mes = $SoliciteDate[0];

                    $dia = $SoliciteDate[1];

                    $annio = substr($SoliciteDate[2], 0, 4);

                    $Fecha = $annio . '/' . $mes . '/' . $dia;
                    $arregloDatos[fecha] = $Fecha;
                }

                $mes = date('m', strtotime($Fecha));
                $annio = date('Y', strtotime($Fecha));
                if ($annio < date('Y')) {
                    $arregloDatos[alerta] = 'La fecha de este registro es del año anterior.';
                    $arregloDatos[color] = "color:#00F;";
                } else if ($annio > date('Y')) {
                    $arregloDatos[alerta] = 'La fecha del registro no puede ser mayor a la fecha actual.';
                    $arregloDatos[color] = "color:#F00;";
                    $cuentaError = 1;
                } else if ($annio == date('Y') && $mes == date('m')) {
                    $arregloDatos[alerta] = '';
                    $arregloDatos[color] = "";
                } else if ($annio == date('Y') && $mes < date('m')) {
                    $arregloDatos[alerta] = 'La fecha de este registro es de meses anteriores.';
                    $arregloDatos[color] = "color:#00F;";
                } else if ($annio == date('Y') && $mes > date('m')) {
                    $arregloDatos[alerta] = 'La fecha de este registro es de un mes posterior.';
                    $arregloDatos[color] = "color:#F00;";
                    $cuentaError = 1;
                } else {
                    unset($arregloDatos[alerta]);
                }

                $tip_doc = substr($Reference1, 0, 3);

                $arregloDatos[tip_doc] = $tip_doc;

                $num_doc = substr($Reference1, 3, 10);
                $arregloDatos[num_doc] = $num_doc;

                $val_doc = $TransValue;
                $arregloDatos[val_doc] = $val_doc;
                $arregloDatos[val_doc1] = number_format(trim($val_doc));

                $tip_error = 1;
                $arregloDatos[tip_error] = $tip_error;

                $des_error = '';
                $arregloDatos[des_error] = $des_error;

                $val_ext = 0;
                $arregloDatos[val_ext] = $val_ext;

                $digito = substr($Reference1, 14, 1);
                $arregloDatos[digito] = $digito;

                if ($PaymentSystem == 1) {
                    /*if (trim($FIName) == 'VISA' || trim($FIName) == 'MASTERCARD' || trim($FIName) == 'CREDENCIAL') {
                        $cod_ban = '0106';
                    } */
                    $visa = "VISA";
                    $resultado1 = strpos($FIName, $visa);
                    
                    $mastercard = "MASTERCARD";
                    $resultado2 = strpos($FIName, $mastercard);
                    
                    $credencial = "CREDENCIAL";
                    $resultado3 = strpos($FIName, $credencial);
                    
                    if ($resultado1 !== FALSE || $resultado2 !== FALSE || $resultado3 !== FALSE || trim($FIName) == 'VISA' || trim($FIName) == 'MASTERCARD' || trim($FIName) == 'CREDENCIAL' ) {
                        $cod_ban = '0106';
                    }else {
                        $cod_ban = '1318';
                    }
                } else {
                    $cod_ban = '1317';
                }
                ///echo $FIName.' '.$cod_ban.'---';

                $arregloDatos[cod_ban] = $cod_ban;

                $tip_docnum_doc = $tip_doc . $num_doc;
                $arregloDatos[tip_docnum_doc] = $tip_docnum_doc;

                $val_cons = $val_doc;
                $arregloDatos[val_cons] = $val_cons;
                $arregloDatos[val_cons1] = number_format(trim($val_cons));

                $impuesto = 0;
                $arregloDatos[impuesto] = $impuesto;

                $fec_proc = FECHAACTUAL;
                $arregloDatos[fec_proc] = $fec_proc;

                $descrip = $arregloDatos[nomarchivo];
                $arregloDatos[descrip] = $descrip;

                $fec_ing = $Fecha;
                $arregloDatos[fec_ing] = $fec_ing;

                $ind_032 = 0;
                $arregloDatos[ind_032] = $ind_032;

                $unaPlantilla->parseCurrentBlock();
                $arregloDatos[odd] = $odd;
                $this->mantenerDatos($arregloDatos, $unaPlantilla);
            }

            $cont = $cont + 1;
        }
        if ($cuentaError == 1) {
            $arregloDatos[mostrarBotonCrear] = 'none';
            $arregloDatos[carg] = '0';
        } else {
            $arregloDatos[mostrarBotonCrear] = 'block';
            $arregloDatos[carg] = '1';
        }
        $this->mantenerDatos($arregloDatos, $unaPlantilla);

        return $unaPlantilla->get();
    }

    function checkDateTime($data) {
        if (date('Y/m/d', strtotime($data)) == $data) {
            return true;
        } else {
            return false;
        }
    }

    function crearPreuploadDocumentoscsv5($arregloDatos, $archivo) {
        $this->plantilla->loadTemplateFile(PLANTILLAS . 'CargueReferenciasCargaMaestro.html', true, true);
        $arregloDatos[mostrar] = 0;
        $htmlFormulario = $this->obtendatos5($arregloDatos, $archivo);
        $this->plantilla->setVariable('auxt', $htmlFormulario);
        $this->plantilla->show();
    }

    function obtendatos5($arregloDatos, $archivo) {
        //var_dump($arregloDatos);
        $unaPlantilla = new HTML_Template_IT();
        $unaPlantilla->loadTemplateFile(PLANTILLAS . 'CargueReferenciasDocumentosListadoCarga.html', true, true);

        $n = 0;
        $status = '';
        $valfor = 0;
        $cuentaError = 0;
        $cont = 0;
        while (!feof($archivo)) {
            $linea = fgets($archivo, 4092);
            list($Fecha, $tip_doc, $num_doc, $val_doc, $cod_con, $digito, $cod_ban,
                    $val_cons ) = explode(";", $linea);

            $estado = '';
            if ($cont >= 1) {

                if ($n % 2) {
                    $odd = 'odd';
                } else {
                    $odd = '';
                }
                $n = $n + 1;
                $unaPlantilla->setCurrentBlock('ROW');

                $arregloDatos[n] = $n;
                //$Fecha=substr($Fecha_transaccion,6, 2).'/'.substr($Fecha_transaccion, 4, 2).'/'.substr($Fecha_transaccion, 0, 4);
                //$arregloDatos[fecha]=$Fecha;


                if ($this->checkDateTime($Fecha) == false) {
                    $arregloDatos[alerta] = 'La fecha no tiene el formato adecuado aaaa/mm/dd .';
                    $arregloDatos[color] = "color:#F00;";
                    $cuentaError = 1;
                } else {
                    $arregloDatos[fecha] = $Fecha;
                    $mes = date('m', strtotime($Fecha));
                    $annio = date('Y', strtotime($Fecha));
                    if ($annio < date('Y')) {
                        $arregloDatos[alerta] = 'La fecha de este registro es del año anterior.';
                        $arregloDatos[color] = "color:#00F;";
                    } else if ($annio > date('Y')) {
                        $arregloDatos[alerta] = 'La fecha del registro no puede ser mayor a la fecha actual.';
                        $arregloDatos[color] = "color:#F00;";
                        $cuentaError = 1;
                    } else if ($annio == date('Y') && $mes == date('m')) {
                        $arregloDatos[alerta] = '';
                        $arregloDatos[color] = "";
                    } else if ($annio == date('Y') && $mes < date('m')) {
                        $arregloDatos[alerta] = 'La fecha de este registro es de meses anteriores.';
                        $arregloDatos[color] = "color:#00F;";
                    } else if ($annio == date('Y') && $mes > date('m')) {
                        $arregloDatos[alerta] = 'La fecha de este registro es de un mes posterior.';
                        $arregloDatos[color] = "color:#F00;";
                        //$cuentaError = 1;
                    } else {
                        unset($arregloDatos[alerta]);
                    }
                }

                $arregloDatos[tip_doc] = $tip_doc;

                $arregloDatos[num_doc] = $num_doc;


                $arregloDatos[val_doc] = $val_doc;
                $arregloDatos[val_doc1] = number_format(trim($val_doc));

                $tip_error = 1;
                $arregloDatos[tip_error] = $tip_error;

                $des_error = '';
                $arregloDatos[des_error] = $des_error;

                $val_ext = 0;
                $arregloDatos[val_ext] = $val_ext;

                $digito = substr($documento, 11, 12);
                $arregloDatos[digito] = $digito;

                $cod_ban; //validar
                $arregloDatos[cod_ban] = $cod_ban;

                $tip_docnum_doc = $tip_doc . $num_doc;
                $arregloDatos[tip_docnum_doc] = $tip_docnum_doc;

                $val_cons = $val_doc;
                $arregloDatos[val_cons] = $val_cons;
                $arregloDatos[val_cons1] = number_format(trim($val_cons));

                $impuesto = 0;
                $arregloDatos[impuesto] = $impuesto;

                $fec_proc = FECHAACTUAL;
                $arregloDatos[fec_proc] = $fec_proc;
                //$_SESSION[usuario]='91480338';
                $descrip = $arregloDatos[nomarchivo];
                $arregloDatos[descrip] = $_SESSION[usuario] . $descrip;

                $fec_ing = $Fecha;
                $arregloDatos[fec_ing] = $fec_ing;

                $ind_032 = 0;
                $arregloDatos[ind_032] = $ind_032;

                $unaPlantilla->parseCurrentBlock();
                $arregloDatos[odd] = $odd;
                $this->mantenerDatos($arregloDatos, $unaPlantilla);
            }

            $cont = $cont + 1;
        }
        if ($cuentaError == 1) {
            $arregloDatos[mostrarBotonCrear] = 'none';
            $arregloDatos[carg] = '0';
        } else {
            $arregloDatos[mostrarBotonCrear] = 'block';
            $arregloDatos[carg] = '1';
        }

        $this->mantenerDatos($arregloDatos, $unaPlantilla);

        return $unaPlantilla->get();
    }

    /*     * FIN* */
}

?>