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
    function setDatos($arregloDatos, &$datos, &$plantilla) 
	{
		foreach ($datos as $key => $value) 
		{
			$plantilla->setVariable($key, $value);
        }
    }

    function getLista($arregloDatos, $seleccion, &$plantilla) 
	{
		$unaLista = new Cheques();
        $lista = $unaLista->lista($arregloDatos[tabla], $arregloDatos[condicion], $arregloDatos[campoCondicion]);
        $lista = armaSelect($lista, $seleccion, '[seleccione]');
        $plantilla->setVariable($arregloDatos[labelLista], $lista);
    }

    function cargaPlantilla($arregloDatos) 
	{
		$unAplicaciones = new CargueReferencias();
        $formularioPlantilla = new HTML_Template_IT();
        $formularioPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla], false, false);
        $formularioPlantilla->setVariable('comodin', ' ');
        $this->mantenerDatos($arregloDatos, $formularioPlantilla);

        $this->$arregloDatos[thisFunction]($arregloDatos, $this->datos, $formularioPlantilla);
        if ($arregloDatos[mostrar]) {
            $formularioPlantilla->show(); //ajax
        } else {
            return $formularioPlantilla->get(); // php
        }
    }

    // Arma cada Formulario o funcion en pantalla
    function setFuncion($arregloDatos, $unDatos) 
	{
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
            $this->$arregloDatos[thisFunctionAux]($arregloDatos, $unDatos, $unaPlantilla);
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

    function maestro($arregloDatos) 
	{
		$this->plantilla->loadTemplateFile(PLANTILLAS . 'cargueReferenciasMaestro.html', false, false);
		$arregloDatos[mostrar] = 0; //  en php o ajax
		$arregloDatos[plantilla] = 'cargueReferenciasUpload.html'; //plantilla a la que me dirijo
		$arregloDatos[thisFunction] = 'filtro'; // funcion que debe estar en presentacion y datos
		$htmlCargueReferenciasFormulario = $this->cargaPlantilla($arregloDatos); //variable a la que le asigno la plantilla
		$this->plantilla->setVariable('filtro_entrada', $htmlCargueReferenciasFormulario); //funcion que recibe nombre etiqueta donde carga{xxx} y la plantilla
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

        $this->mantenerDatos($arregloDatos, $formularioPlantilla);
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
        $this->mantenerDatos($arregloDatos, $formularioPlantilla);
    }

    /*     * FUNCIONES PARA CARGAR BANCOS* */

    function maestroCarga($arregloDatos) 
	{
		$this->plantilla->loadTemplateFile(PLANTILLAS . 'cargueReferenciasCargaMaestro.html', false, false	);
        $arregloDatos[mostrar] = 0;
        $arregloDatos[abrirv] = '1';
        $this->mantenerDatos($arregloDatos, $this->plantilla);
        $arregloDatos[plantilla] = 'cargarFoto.html';
        $arregloDatos[thisFunction] = 'filtroCarga';

        $htmlFormulario = $this->cargaPlantilla($arregloDatos);
        $this->plantilla->setVariable('entrada', $htmlFormulario);
        $this->plantilla->show();
    }

    function filtroCarga($arregloDatos, $datos, $plantilla) {
        
        $this->mantenerDatos($arregloDatos, $plantilla);
    }

    function crearPreuploadDocumentoscsv($arregloDatos, $archivo) 
	{
        $this->plantilla->loadTemplateFile(PLANTILLAS . 'CargueReferenciasCargaMaestro.html', true, true);
        $arregloDatos[mostrar] = 0;
        $htmlFormulario = $this->obtendatos($arregloDatos, $archivo);
        $this->plantilla->setVariable('auxt', $htmlFormulario);
        $this->plantilla->show();
    }

    function obtendatos($arregloDatos, $archivo) 
	{
        $unaPlantilla = new HTML_Template_IT();
        $unaPlantilla->loadTemplateFile(PLANTILLAS . 'cargueReferenciasListadoCarga.html', true, true);

        $n = 0;
        $status = '';
  		$cuentaError = 0;
        $cont = 0;
		$errores=0;
        while (!feof($archivo)) 
		{
 			$linea = fgets($archivo, 4092);
            
				list(
				 $codigo_ref, 
				 $ref_prove,
				 $nombre,
				 $observacion,
				 $cliente,
				 $parte_numero,
				 $unidad,
				 $unidad_venta,
				 $presentacion_venta,
				 $fecha_expira,
                 $vigencia, 
				 $min_stock, 
				 $lote_cosecha, 
				 $alto, 
				 $largo,
                 $ancho,
				 $serial,
				 $tipo,
				 $grupo_item,
				 $factor_conversion) = explode(";", $linea);
				 
			if($n > 0 or trim($arregloDatos[codigo_ref])<>"")
			{
			
				$estado = '';
           		if ($n % 2) 
				{
             		$odd = 'odd';
            	} else 
		    	{
             		$odd = '';
            	}
			
				$unaPlantilla->setCurrentBlock('ROW');
				$arregloDatos[n] = $n;
				//$Fecha = substr($Fecha_transaccion, 0, 4) . '/' . substr($Fecha_transaccion, 4, 2) . '/' . substr($Fecha_transaccion, 6, 2);
            	//$arregloDatos[fecha] = $Fecha;
            
            	$arregloDatos[odd] = $odd;
				
				//Se setean los datos
				$arregloDatos[codigo_ref]			=$codigo_ref;
				$arregloDatos[ref_prove]			=$ref_prove;
				$arregloDatos[nombre]				=$nombre;
				$arregloDatos[observaciones]		=$observacion;
				$arregloDatos[cliente]				=$cliente;
				$arregloDatos[parte_numero]			=$parte_numero;
				$arregloDatos[unidad]				=$unidad;
				$arregloDatos[unidad_comercial]		=$unidad_venta;
				$arregloDatos[unidad_inventario]	=$presentacion_venta;
				$arregloDatos[fecha_expira]			=$fecha_expira; 
				$arregloDatos[vigencia]				=$vigencia;
				$arregloDatos[min_stock]			=$min_stock;
                $arregloDatos[lote_cosecha]			=$lote_cosecha;
				$arregloDatos[alto]					=$alto;
				$arregloDatos[largo]				=$largo;
				$arregloDatos[ancho]				=$ancho;
				$arregloDatos[serial]				=$serial;
                $arregloDatos[tipo]					=$tipo;
				$arregloDatos[grupo_item]			=$grupo_item;
				$arregloDatos[factor_conversion]	=$factor_conversion;
					
				// se hacen las validaciones
				$unaValidacion=new CargueReferencias();
				
				
				$errorCliente=$unaValidacion->validarCliente($arregloDatos);
				$arregloDatos[alerta]="";
				if($errorCliente==0){
					$arregloDatos[alerta]="Error el cliente $arregloDatos[cliente] NO existe ";
					$errores=$errores+1;
				}
				
				$errorRef=$unaValidacion->existeReferencia($arregloDatos);
				
				if($errorRef > 0){
					$arregloDatos[alerta].="Error la referencia $arregloDatos[codigo_ref] YA existe ";
					$errores=$errores+1;
				}
				
				//$arregloDatos[alerta]="referencia ";
				
				
				
				$errorReferencia=$unaValidacion->validarUnidadComercial($arregloDatos);
				if($errorReferencia==0){
					$arregloDatos[alerta].="Error en la Unidad Comercial $arregloDatos[unidad_comercial] NO existe,  ";
					$errores=$errores+1;
				}else{
				
					$unaValidacion->fetch();
					$arregloDatos[unidad_comercial_aux]=$arregloDatos[unidad_comercial];
					$arregloDatos[unidad_comercial]=$unaValidacion->id;
					//$arregloDatos[alerta].="Se cambio Unidad Comercial $arregloDatos[presentacion_venta_aux] a $arregloDatos[presentacion_venta],  $arregloDatos[sql] ";
				}
				
				
				$errorReferencia=$unaValidacion->validarUnidadInventario($arregloDatos);
				if($errorReferencia==0){
					$arregloDatos[alerta].="Error la Unidad de inventario $arregloDatos[unidad_inventario] NO existe,  $arregloDatos[sql] ";
					$errores=$errores+1;
				}else{
					$unaValidacion->fetch();
					$arregloDatos[unidad_inventario_aux]=$arregloDatos[unidad_inventario];
					$arregloDatos[unidad_inventario]=$unaValidacion->codigo;
					//$arregloDatos[alerta].="Se cambia unidad $arregloDatos[unidad_inventario_aux]  a $arregloDatos[unidad_inventario]   $arregloDatos[sql]";
				}
				
				$errorReferencia=$unaValidacion->validarTipoReferencia($arregloDatos);
				if($errorReferencia==0){
					$arregloDatos[alerta].="Error en el Tipo $arregloDatos[tipo] NO existe, $arregloDatos[sqlX] ";
					$errores=$errores+1;
				}
			
				$errorReferencia=$unaValidacion->validarGrupoItem($arregloDatos);
				if($errorReferencia==0){
					$arregloDatos[alerta].="Error en el Grupo Items $arregloDatos[grupo_item] NO existe,  ";
					$errores=$errores+1;
				}
				
			
			
				
				
				
				$anio = substr($arregloDatos[vigencia], 0, 4);
				$mes = substr($arregloDatos[vigencia], 6, 2);
				$dia = substr($arregloDatos[vigencia], 8, 2);
			
				if($mes > 12){
					$arregloDatos[alerta].="Error en el Formato de la fecha  $arregloDatos[vigencia] debe ser AAAA/MM/DD,  ";
					$errores=$errores+1;
				}
				
				if($dia > 30){
					$arregloDatos[alerta].="Error en el Formato de la fecha  $arregloDatos[vigencia] debe ser AAAA/MM/DD,  ";
					$errores=$errores+1;
				}
			
				//$errorUnidad=$unaValidacion->validarUnidad($arregloDatos);
			
            	if(trim($arregloDatos[codigo_ref]) <>""){
					$this->mantenerDatos($arregloDatos, $unaPlantilla);
					$unaPlantilla->parseCurrentBlock();	
				}
				
		    }
			$n = $n + 1;
				
        }
		$unaPlantilla->setVariable('registros', $n);
		$unaPlantilla->setVariable('errores', $errores-4);
		$arregloDatos[alerta]="";
        if ($cuentaError == 1) {
            $arregloDatos[mostrarBotonCrear] = 'none';
            $arregloDatos[carg] = '0';
        } else {
            $arregloDatos[mostrarBotonCrear] = 'block';
            $arregloDatos[carg] = '1';
        }
		
		 //fclose($nombreCompleto);
		 unlink("./integrado/_files/$arregloDatos[nombre_archivo]");
		echo  $unaPlantilla->get();
     }

  
    function checkDateTime($data) {
        if (date('Y/m/d', strtotime($data)) == $data) {
            return true;
        } else {
            return false;
        }
    }

  }

?>