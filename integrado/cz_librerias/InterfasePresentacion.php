<?php
require_once("HTML/Template/IT.php");
require_once("Funciones.php");
require_once("InterfaseDatos.php");
require_once("montoEscrito.php");
require_once("Archivo.php");

class InterfasePresentacion {
  var $datos;
  var $plantilla;

  function InterfasePresentacion(&$datos) {
    $this->datos =& $datos;
    $this->plantilla = new HTML_Template_IT();
  }

  //Función que coloca los datos que vienen de la BD
  function setDatos($arregloDatos,&$datos,&$plantilla) {
    foreach ($datos as $key => $value) {
      $plantilla->setVariable($key, $value);
    }
  }

  function mantenerDatos($arregloCampos,&$plantilla) {
    $plantilla =& $plantilla;
    if(is_array($arregloCampos)) {
      foreach ($arregloCampos as $key => $value) {
        $plantilla->setVariable($key, $value);
      }
    }
  }

  //Función que Arma una lista
  function getLista($arregloDatos,$seleccion,&$plantilla) {
    $unaLista = new Interfase();
    $lista = $unaLista->lista($arregloDatos[tabla],$arregloDatos[condicion],$arregloDatos[campoCondicion]);
    $lista = armaSelect($lista,'[seleccione]',$seleccion);
    $plantilla->setVariable($arregloDatos[labelLista], $lista);
  }

  // Arma cada Formulario o función en pantalla
  function setFuncion($arregloDatos,$unDatos) {
    $unDatos = new Interfase();
    if(!empty($arregloDatos[setCharset])) {
      header('Content-type: text/html; charset=iso-8859-1');
    }

    $r = $unDatos->$arregloDatos[thisFunction]($arregloDatos);

    if(!empty($arregloDatos[thisFunctionAux])) {
      $this->$arregloDatos[thisFunctionAux]($arregloDatos);
    }

    $unaPlantilla = new HTML_Template_IT();
    $unaPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla], true, true);
    $unaPlantilla->setVariable('comodin', ' ');
    if(!empty($unDatos->mensaje_error)) {
      $unaPlantilla->setVariable('mensaje', $unDatos->mensaje_error);
      $unaPlantilla->setVariable('estilo', $unDatos->mensaje_error);
    }

    $this->mantenerDatos($arregloDatos, $unaPlantilla);
    $arregloDatos[n] = 0;

    while($unDatos->fetch()) {
      if($arregloDatos[n] % 2) {
        $odd = 'odd';
      } else {
        $odd = '';
      }
      $arregloDatos[n] = $arregloDatos[n] + 1;
      $unaPlantilla->setCurrentBlock('ROW');
      $this->setDatos($arregloDatos, $unDatos, $unaPlantilla);
      $this->$arregloDatos[thisFunction]($arregloDatos,$unDatos,$unaPlantilla);
      $unaPlantilla->setVariable('n', $arregloDatos[n]);
      $unaPlantilla->setVariable('odd', $odd);
      $unaPlantilla->parseCurrentBlock();
    }

    if($unDatos->N == 0) {
      $unaPlantilla->setVariable('mensaje', 'No hay registros para listar, ' . $unDatos->mensaje_error);
      $unaPlantilla->setVariable('estilo', 'ui-state-highlight');
      $unaPlantilla->setVariable('mostrarCuerpo', 'none');
    }
    
    $unaPlantilla->setVariable('num_registros', $unDatos->N);

    if($arregloDatos[mostrar]) {
      $unaPlantilla->show();
    } else {
      $unaPlantilla->setVariable('cuenta', $this->cuenta);
      return $unaPlantilla->get();
    }
  }

  function cargaPlantilla($arregloDatos) {
    $unAplicaciones = new Levante();
    $formularioPlantilla = new HTML_Template_IT();
    $formularioPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla], true, true);
    $formularioPlantilla->setVariable('comodin', ' ');
    $this->mantenerDatos($arregloDatos, &$formularioPlantilla);

    $this->$arregloDatos[thisFunction]($arregloDatos, $this->datos, $formularioPlantilla);
    if($arregloDatos[mostrar]) {
      $formularioPlantilla->show();
    } else {
      return $formularioPlantilla->get();
    }
  }

  // Función Principal para las consultas
  function paraGenerarInterfase($arregloDatos) {
    $this->plantilla->loadTemplateFile(PLANTILLAS . 'interfaseMaestroConsulta.html', true, true);
    $this->mantenerDatos($arregloDatos, &$this->plantilla);
    $this->plantilla->setVariable('comodin', '');

    if(!empty($arregloDatos[filtro])) {
      $arregloDatos[mostrar] = 0;
      $arregloDatos[plantilla] = 'interfaseListado.html';
      $arregloDatos[thisFunction] = 'listarInterfases';
      $htmlListado = $this->setFuncion($arregloDatos,$unDatos);
      $this->plantilla->setVariable('htmlListado', $htmlListado);
    } else {                
      $arregloDatos[thisFunction] = 'filtroConsulta';
      $arregloDatos[plantilla] = 'interfaseFiltro.html';
      $arregloDatos[mostrar] = 0;
      if($arregloDatos[accion] == 'new'){ $arregloDatos[requiredName] = 'required'; }
      $htmlFiltro = $this->cargaPlantilla($arregloDatos);
      $this->plantilla->setVariable('filtroEntradaConsulta', $htmlFiltro);
    }
    $this->plantilla->show();
  }

  function listarInterfases($arregloDatos,&$datos,&$plantilla) {
    $arregloDatos[valor] = number_format(round($datos->valor), 0, ',', '.');
    $this->mantenerDatos($arregloDatos, $plantilla);
  }

  function filtroConsulta($arregloDatos,&$datos,&$plantilla) {
  }

  function filtro($arregloDatos) {
  }

  function valoresFormateados($arregloDatos,$unDatos) {
    $arregloDatos[valorf] = number_format(round($unDatos->valor), 0, ',', '.');
    $arregloDatos[ivaf] = number_format(round($unDatos->iva), 0, ',', '.');
    $arregloDatos[subtotalf] = number_format(round($unDatos->subtotal), 0, ',', '.');
    $arregloDatos[rte_fuentef] = number_format(round($unDatos->rte_fuente), 0, ',', '.');
    $arregloDatos[rte_ivaf] = number_format(round($unDatos->rte_iva), 0, ',', '.');
    $arregloDatos[rte_icaf] = number_format(round($unDatos->rte_ica), 0, ',', '.');
    $arregloDatos[totalf] = number_format(round($unDatos->total), 0, ',', '.');
    $arregloDatos[anticipof] = number_format(round($unDatos->valor_anticipo), 0, ',', '.');
  }

  function getInterfase($arregloDatos,$unDatos) {  //Módulo Solo 
  }

  function generaInterfase($arregloDatos) {  //Módulo Solo Factura
    $unArchivo = new Archivo();
    $unArchivo->crear('integrado/_files/interfase.txt');

    $this->plantilla->loadTemplateFile(PLANTILLAS . 'interfaseListadoDetalle.html', true, true);
    if(!empty($arregloDatos[interfase])) {
      $this->plantilla->setVariable('interfase', $arregloDatos[interfase]);
    } else {
      $this->plantilla->setVariable('mostrarTabla', 'none');
    }
    
    $this->datos->getInterfase($arregloDatos);
    
    $n = 0;
    $total_valor = 0;
    
    if($this->datos->N == 0) {
      if(!empty($arregloDatos[accion])) {
        $mensaje = 'No hay Registros a que generar Interfase';
        $this->plantilla->setVariable('mostrarInterfase', 'none');
        $this->plantilla->setVariable('mostrarTabla', 'none');
        $estilo = 'error';
      } else {
        $mensaje = 'No existe ninguna Interfase con el nombre Suministrado';
        $this->plantilla->setVariable('mostrarInterfase', 'none');
        $this->plantilla->setVariable('mostrarTabla', 'none');
        $estilo = 'error';
      }
    }

    switch($arregloDatos[tipo_interfase]) {
      case 1:
        // Interfase Helisa
        $this->getInterfaseElisa($arregloDatos, $unArchivo);
        break;
      case 2:
        $this->getInterfaseSigo($arregloDatos,$unArchivo);
        break;
    }
  }

  // OJO aqui genera interfase Helisa
  function getInterfaseSigo($arregloDatos,$unArchivo) {
    $n = 0;
    $facturas = 1;
    while($this->datos->fetch()) {
      $nuevo = 0;
      if($this->datos->factura <> $anterior) {
        if($n > 0) {
          $nuevo = 1;
          $facturas = $facturas + 1;
        }
      }
      if($nuevo) { // Se calculan los conceptos Anteriores con variables anteriores 
        $debitos = 0;
        $arregloDatos[rte_fuentem] = $arregloDatos[rte_fuentem_ant];
        $arregloDatos[rte_icam] = $arregloDatos[rte_icam_ant];
        $arregloDatos[rte_ivam] = $arregloDatos[rte_ivam_ant];
        $arregloDatos[fecha_factura] = $arregloDatos[fecha_factura_ant];
        $arregloDatos[numero_oficial] = $arregloDatos[numero_oficial_ant];
        $arregloDatos[nit_ant] = $arregloDatos[nit];
        $this->debitos = 0;
        $this->conceptosCalculados($arregloDatos, $unArchivo);
      }
      $arregloDatos[factura] = $this->datos->factura;
      $this->plantilla->setCurrentBlock('ROW');
      $this->plantilla->setVariable('n',$n + 1);
      $this->plantilla->setVariable('cuenta', $this->datos->cuenta);
      $this->plantilla->setVariable('valor', round($this->datos->valor));
      $this->plantilla->setVariable('factura', $this->datos->factura);
      $this->plantilla->setVariable('fecha_factura', $this->datos->fecha_factura);
      $this->plantilla->setVariable('nombreservicio', $this->datos->nombreservicio);
      $this->plantilla->setVariable('numero_oficial', $this->datos->numero_oficial);
      $total_valor = $total_valor + $this->datos->valor;

      $arregloDatos[total] = round($this->datos->subtotal) + round($this->datos->ivam) - ($this->datos->valor_anticipo + $arregloDatos[total_anticipos]);

      $n = $n + 1; // variables de getInterfase

      $arregloDatos[rte_fuentem] = $this->datos->rte_fuentem;
      $arregloDatos[rte_icam] = $this->datos->rte_icam;
      $arregloDatos[rte_ivam] = $this->datos->rte_ivam;
      $arregloDatos[fecha_factura] = $this->datos->fecha_factura;
      $arregloDatos[numero_oficial] = $this->datos->numero_oficial;
      $arregloDatos[nit] = $this->datos->nit;

      $arregloDatos[concepto] = $this->datos->concepto;
      $arregloDatos[nombreservicio] = $this->datos->nombreservicio;
      $arregloDatos[nombre_cliente] = $this->datos->nombre_cliente;
      $arregloDatos[centro_costo] = $this->datos->centro_costo;
      $arregloDatos[cuenta] = $this->datos->cuenta;
      $arregloDatos[cuenta_aux1] = $this->datos->cuenta;
      $arregloDatos[naturaleza] = $this->datos->naturaleza;
      $arregloDatos[valor] = round($this->datos->valor);

      if(trim($this->datos->naturaleza) == "D") {
        $this->debitos = $this->debitos + $arregloDatos[valor];
      } else {
        $this->creditos = $this->creditos + $arregloDatos[valor];
      }

      $this->archivoLineaSigo($arregloDatos, $unArchivo);
      $anterior = $arregloDatos[factura];
      $anterior = $arregloDatos[factura];

      if($factura_aux <> $arregloDatos[factura]) {
      }
      if($this->datos->N == $n) {// Se llama  conceptos calculados para la ultima factura
      }

      $factura_ant = $this->datos->factura;
      $anterior = $this->datos->factura;
      $factura_aux = $this->datos->factura;

      $arregloDatos[rte_fuentem_ant] = $this->datos->rte_fuentem;
      $arregloDatos[rte_icam_ant] = $this->datos->rte_icam;
      $arregloDatos[rte_ivam_ant] = $this->datos->rte_ivam;
      $arregloDatos[fecha_factura_ant] = $this->datos->fecha_factura;
      $arregloDatos[numero_oficial_ant] = $this->datos->numero_oficial;
      $arregloDatos[nit_ant] = $this->datos->nit;

      $this->plantilla->parseCurrentBlock();
    }

    $this->conceptosCalculados($arregloDatos,$unArchivo); // Aplica para la última Factura o cuando hay solo una
    if($this->datos->interfase == 'SinInterfase') {
      $this->plantilla->setVariable('mostrarTabla', 'none');
    }

    $this->plantilla->setVariable('total_valor',$total_valor);
    $this->plantilla->setVariable('mensaje', $mensaje);
    $this->plantilla->setVariable('estilo', $estilo);

    $this->plantilla->show();
  }

  // OJO esta genera interface SIIGO
  function getInterfaseElisa($arregloDatos,$unArchivo) {
    $secuencia = 0;
    $factura_actual = 0;
    $arregloDatos[secuencia_cruce] = '001';
    $arregloDatos[tipo_comprobante] = 'F';
    $arregloDatos[base_retencion] = '00';
    $arregloDatos[codigo_comprobante] = '001';
    switch($_SESSION['sede']) {
      case '25':
      case '11':
        $arregloDatos[codigo_comprobante] = '001'; // Dep Alcomex y ServialComex  digito Ver 7
        break;
      case '22':
        $arregloDatos[codigo_comprobante] = '002'; // Alcomex ZF
        break;
      default:
        $arregloDatos[codigo_comprobante] = '001'; // Servialcomex
    }
    $arregloDatos[comprobante_cruce] = '001';
    while($this->datos->fetch()) {
      $arregloDatos[total_interfase] = $arregloDatos[total_interfase] + $this->datos->valor;
      $arregloDatos[total_interfase_f] = number_format($arregloDatos[total_interfase],0,',','.');
      if($this->datos->anticipo > 0 OR !empty($this->datos->recibo)) {      //Hay Anticipo entonces R
        $arregloDatos[tipo_doc] = 'F';
        $arregloDatos[recibo] = $this->datos->numero_oficial;
        $arregloDatos[reciboAux] = $this->datos->recibo;
      } else {
        $arregloDatos[tipo_doc] = 'F';
        $arregloDatos[recibo] = $this->datos->numero_oficial;
      }

      if($factura_actual <> $this->datos->factura and $factura_actual <> 0) {
        $this->registrosAdicionales($arregloDatos, &$unArchivo);
      }

      $arregloDatos[tipo_cliente] = $this->datos->tipo;
      $arregloDatos[cuenta_filial] = $this->datos->cuenta_filial;
      $arregloDatos[razon_social] = $this->datos->razon_social;

      $arregloDatos[factura] = $this->datos->factura;
      $arregloDatos[recibo_anticipo] = $this->datos->recibo;
      $arregloDatos[Auxfactura] = $this->datos->factura;
      $arregloDatos[numero_oficial] = $this->datos->numero_oficial;
      $arregloDatos[iva] = $this->datos->ivam;
      $arregloDatos[rte_fuente] = $this->datos->rte_fuentem;
      $arregloDatos[rte_fuented] = round($this->datos->rte_fuented);
      $arregloDatos[rte_iva] = $this->datos->rte_ivam;
      $arregloDatos[rte_iva_fac] = $this->datos->rte_ivam;
      $arregloDatos[rte_ica] = $this->datos->rte_icam;
      $arregloDatos[intermediario] = $this->datos->intermediario;
      $arregloDatos[anticipo] = $this->datos->anticipo;

      $arregloDatos[valor_anticipo] = $this->datos->valor_anticipo;
      $arregloDatos[efectivo] = $this->datos->efectivo;
      $arregloDatos[cheque] = $this->datos->cheque;
      $arregloDatos[banco] = $this->datos->banco;
      $arregloDatos[cuenta] = $this->datos->cuenta;

      $arregloDatos[facturado_a] = $this->datos->nit;
      $arregloDatos[por_cuenta_de] = $this->datos->intermediario;

      $arregloDatos[nit] = $arregloDatos[por_cuenta_de];

      $arregloDatos[fecha_factura] = formatoFecha($this->datos->fecha_factura);
      $arregloDatos[fecha_pago] = formatoFecha($this->datos->fecha_salida);
      $arregloDatos[forma_pago] = $this->datos->banco . $this->datos->cheque . $this->datos->efectivo;
      $arregloDatos[nombre_servicio] = $this->datos->nombreservicio;

      $arregloDatos[naturaleza] = $this->datos->naturaleza;
      $arregloDatos[cuenta] = $this->datos->cuenta;
      $arregloDatos[valor] = round($this->datos->valor);
      //Se trae el total de Anticipos
      $unAnticipo = new Interfase();
      $arregloDatos[total_anticipos] = $unAnticipo->totalAnticipos($arregloDatos);

      //Se Recalcula Total por el tema  del redondeo
      $arregloDatos[total] = round($this->datos->subtotal) + round($this->datos->ivam) - round($this->datos->rte_fuentem) - round($this->datos->rte_ivam) - round($this->datos->rte_icam) - ($this->datos->valor_anticipo + $arregloDatos[total_anticipos]);
      $arregloDatos[vendedor] = $this->datos->codigo_vendedor;
      $arregloDatos[centro_costo] = $this->datos->centro_costo;

      $arregloDatos[subcentro_costo] = $this->datos->subcentro_costo;
      $arregloDatos[credito] = $this->datos->credito;

      if($factura_actual == 0) {
        $factura_actual = $this->datos->factura;
      }
      if($factura_actual <> $this->datos->factura) {
        $secuencia = 0;
      }
      $secuencia = $secuencia + 1;
      $arregloDatos[secuencia] = $secuencia;
      $this->plantilla->setCurrentBlock('ROW');
      $arregloDatos[valor_f] = number_format($arregloDatos[valor], 0, ',', '.');
      $this->mantenerDatos($arregloDatos, $this->plantilla);
      $this->plantilla->setVariable('n', $n + 1);
      $this->plantilla->setVariable('nombreservicio', $this->datos->nombreservicio);
      $total_valor = $total_valor + $this->datos->valor;
      $n = $n + 1;

      $arregloDatos[aux_tipo_doc] = $arregloDatos[tipo_doc];
      $arregloDatos[aux_comprobante_cruce] = $arregloDatos[comprobante_cruce];
      $arregloDatos[aux_recibo] = $arregloDatos[recibo];

      if($this->datos->idservicio == '8') {//Cuenta de Terceros no se tiene en cuenta en el consolidado 17-06-2008
        $secuencia = $secuencia - 1;
        $arregloDatos[secuencia] = $secuencia;
      } else {
        if($this->datos->tipo_tercero == 8) {
          $arregloDatos[tipo_doc] = 'P';
          if($this->datos->tipo == 2) { //2011
            $arregloDatos[comprobante_cruce] = '003'; //$comprobante_cruce;
          } else {
            $arregloDatos[comprobante_cruce] = '001'; // Otros
          }

          if($this->datos->nit == '8001972682') { //Julio de 2010 se el el tercero DIAN  se utiliza otro documento cruce
            $arregloDatos[comprobante_cruce] = '008';
          }
          $arregloDatos[recibo] = $this->datos->orden;
        }
        $this->archivoLinea($arregloDatos, $unArchivo);
      }

      $arregloDatos[tipo_doc] = $arregloDatos[aux_tipo_doc];
      $arregloDatos[comprobante_cruce] = $arregloDatos[aux_comprobante_cruce];
      $arregloDatos[recibo] = $arregloDatos[aux_recibo];

      if($factura_actual <> $this->datos->factura) {
        $arregloDatos[factura] = $factura_actual;
        $factura_actual = $this->datos->factura;
      }

      if($this->datos->N == $n) {
        $arregloDatos[factura] = $this->datos->factura;
        $this->registrosAdicionales($arregloDatos,$unArchivo);
      }
      $this->plantilla->parseCurrentBlock();
    }

    $this->plantilla->setVariable('total_valor', $total_valor);
    $this->plantilla->setVariable('mensaje', $mensaje);
    $this->plantilla->setVariable('estilo', $estilo);
    $this->plantilla->setVariable('nombre_interfase', "Interfase " . $arregloDatos[nombre_interfase]);
    $this->plantilla->show();
  }

  // Para interfase SIiGO
  function registrosAdicionales($arregloDatos,$unArchivo) {
    $centro_costo_aux = $arregloDatos[centro_costo];
    $conceptoAux = new Interfase();
    $unTipo = new Interfase();  // Recorre Los Tipos de Conceptos
    $unConcepto = new Interfase();  // Recorre los conceptos de cada tipo
    $unTotal = new Interfase();  // Hace la sumatoria de cada Concepto

    $unTipo->conceptosTipos($arregloDatos); // Trae todos los tipos que hacen parte de la interfaz
    $auxNit = $arregloDatos[nit];
    $arregloDatos[secuencia_cruce] = '001';
    $arregloDatos[base_retencion] = 0;

    while($unTipo->fetch()) { // Se recorren todos los tipos de  servicios
      switch($_SESSION['sede']) {
        case 22:
          $arregloDatos[comprobante_cruce] = '002';
          break;
        default:
          $arregloDatos[comprobante_cruce] = '001';
      }
      $arregloDatos[nit_tercero] = NULL;
      $arregloDatos[recibo] = $arregloDatos[numero_oficial];
      $arregloDatos[tipo_doc] = 'F';
      $arregloDatos[nombre_servicio] = "";
      $arregloDatos[naturaleza] = "";
      $arregloDatos[cuenta] = "";
      $arregloDatos[nit] = $auxNit; //Para recuperar el Nit del tercero y solo la CXC quede con el nit del intermediario Responsable
      $arregloDatos[tipo_servicio] = $unTipo->tipo;
      $unConcepto->otrosConceptos($arregloDatos);
      while($unConcepto->fetch()) {
        $arregloDatos[prorcentaje_iva] = 0;
        $arregloDatos[prorcentaje_rte] = 0;
        $arregloDatos[prorcentaje_ica] = 0;
        $arregloDatos[base_retencion] = 0;
        switch($unConcepto->tipo) {
          case 1: // Conceptos Internos que no son impuestos
            break;
          case 2://Iva
            $arregloDatos[nit] = $arregloDatos[por_cuenta_de];
            $arregloDatos[nombre_servicio] = $unConcepto->nombre;
            $arregloDatos[naturaleza] = $unConcepto->naturaleza;
            $arregloDatos[cuenta] = $unConcepto->cuenta;
            $arregloDatos[prorcentaje_iva] = $unConcepto->iva;

            $arregloDatos[base_retencion] = $unTotal->traerDetalle($arregloDatos);   // Se guarda la Base de Calculo de Iva.
            $arregloDatos[valor] = round($arregloDatos[base_retencion] * $unConcepto->iva / 100);
            if($arregloDatos[valor] > 0) {
              $arregloDatos[secuencia] = $arregloDatos[secuencia] + 1;
              $this->archivoLinea($arregloDatos, $unArchivo);
            }
            break;
          case 9: //Cree
            $arregloDatos[nit] = $arregloDatos[por_cuenta_de];
            $arregloDatos[nombre_servicio] = $unConcepto->nombre;
            $arregloDatos[naturaleza] = $unConcepto->naturaleza;
            $arregloDatos[cuenta] = $unConcepto->cuenta;
            $arregloDatos[prorcentaje_cree] = $unConcepto->rte_cree;

            $arregloDatos[base_retencion] = $unTotal->traerBaseCree($arregloDatos);   // Se guarda la Base de Calculo de Iva.
            $arregloDatos[valor] = round($arregloDatos[base_retencion] * $unConcepto->rte_cree / 100);

            if($arregloDatos[valor] > 0) {
              $arregloDatos[secuencia] = $arregloDatos[secuencia] + 1;
              $this->archivoLinea($arregloDatos, $unArchivo);
              if($arregloDatos[tipo_cliente] == 9) {
                $arregloDatos[naturaleza] = "C";
                $arregloDatos[cuenta] = "1355190202";
                $arregloDatos[secuencia] = $arregloDatos[secuencia] + 1;
                $this->archivoLinea($arregloDatos, $unArchivo);
              }
            }
            break;
          case 3:// Conceptos que son rete Fuente
            if($arregloDatos[intermediario] <> '99') {
              $arregloDatos[nit] = $arregloDatos[facturado_a]; //  correo 18-08-2009 archivo plano Sigo
            }

            $arregloDatos[nombre_servicio] = $unConcepto->nombre;
            $arregloDatos[naturaleza] = $unConcepto->naturaleza;
            $arregloDatos[cuenta] = $unConcepto->cuenta;
            $arregloDatos[prorcentaje_rte] = $unConcepto->rte_fuente;

            $arregloDatos[base_retencion] = $unTotal->traerDetalle($arregloDatos);   // Se guarda la Base de Calculo de Iva.
            $arregloDatos[valor] = round($arregloDatos[base_retencion] * $unConcepto->rte_fuente / 100);
            if($arregloDatos[valor] > 0) {
              $arregloDatos[secuencia] = $arregloDatos[secuencia] + 1;
              $this->archivoLinea($arregloDatos, $unArchivo);
            }
            break;
          case 4: // conceptos que son RETE ICA
            $arregloDatos[nit] = $arregloDatos[facturado_a]; //Correo 18/08/2009 asunto ARCHIVO PLANO SIIGO
            $arregloDatos[nombre_servicio] = $unConcepto->nombre;
            $arregloDatos[naturaleza] = $unConcepto->naturaleza;
            $arregloDatos[cuenta] = $unConcepto->cuenta;
            $arregloDatos[porcentaje_rete_ica] = $unConcepto->rte_ica;
            $arregloDatos[prorcentaje_ica] = $unConcepto->rte_ica;

            $arregloDatos[base_retencion] = $unTotal->traerDetalle($arregloDatos);   // Se guarda la Base de Calculo de Iva.
            $arregloDatos[valor] = round($arregloDatos[base_retencion] * $unConcepto->rte_ica / 100);
            if($arregloDatos[valor] > 0 and $arregloDatos[rte_ica] > 0) { // es necesario condicionar el rte_ica del maestro para saber si aplicaba o no ICA
              $arregloDatos[secuencia] = $arregloDatos[secuencia] + 1;
              $this->archivoLinea($arregloDatos, $unArchivo);
            }
            break;
          case 5: //Conceptos que son Rete Ivas
            $unReteIva = new Interfase();
            if($arregloDatos[intermediario] <> '99') {
              $arregloDatos[nit] = $arregloDatos[facturado_a]; // correo 18-08-2009 archivo plano Sigo
            }
            $arregloDatos[nombre_servicio] = $unConcepto->nombre;
            $arregloDatos[naturaleza] = $unConcepto->naturaleza;
            $arregloDatos[cuenta] = $unConcepto->cuenta;
            $arregloDatos[valor] = round($arregloDatos[rte_iva]);
            $arregloDatos[prorcentaje_iva] = NULL;
            $arregloDatos[cal_riva] = 1;

            $unReteIva->traerDetalle($arregloDatos);
            $base_rete_iva = 0;
            while($unReteIva->fetch()) {
              $base_rete_iva = $base_rete_iva + ($unReteIva->valor * $unReteIva->iva / 100); //La Base del Iva Retenido es el IVA
            }
            $arregloDatos[valor] = round($arregloDatos[rte_iva_fac]); // sobreescribe calculo anterior no requiere RECALCULO
            $base_rete_iva = $arregloDatos[iva]; // Los toma directamente de la CABEZA DE FACTURA.
            if($arregloDatos[valor] > 0) {
              $arregloDatos[base_retencion] = round($base_rete_iva);
              $arregloDatos[secuencia] = $arregloDatos[secuencia] + 1;
              $this->archivoLinea($arregloDatos, $unArchivo);
            }
            break;
          case 6: // Anticipos
            $total_factura = $arregloDatos[valor_anticipo] + $arregloDatos[total_anticipos] + $arregloDatos[total];
            $arregloDatos[nombre_servicio] = $unConcepto->nombre;
            $arregloDatos[naturaleza] = $unConcepto->naturaleza;
            $arregloDatos[cuenta] = $unConcepto->cuenta;
            $arregloDatos[tipo_doc] = 'R';

            switch($_SESSION['sede']) {
              case '11': //Alcomex
              case '22': //Alcomex ZF
                $arregloDatos[comprobante_cruce] = '003';
                $arregloDatos[recibo] = $arregloDatos[recibo_anticipo];
                break;
              case '15': // No está en Uso
                $arregloDatos[comprobante_cruce] = '002';
                $arregloDatos[recibo] = $arregloDatos[recibo_anticipo];
                break;
              default:
                $arregloDatos[comprobante_cruce] = '001';
                $arregloDatos[recibo] = $arregloDatos[recibo_anticipo];
            }

            if($arregloDatos[valor_anticipo] + $arregloDatos[total_anticipos] > 0) {
              $arregloDatos[valor] = $arregloDatos[valor_anticipo];
              $arregloDatos[secuencia] = $arregloDatos[secuencia] + 1;
              $this->archivoLinea($arregloDatos, $unArchivo);
              // Aqui se crea una Línea mas por cada Anticipo Adicional FF
              $unAnticipo = new Interfase();
              $unAnticipo->retornaAnticipos($arregloDatos);
              while($unAnticipo->fetch()) {
                $arregloDatos[valor] = $unAnticipo->valor_anticipo;
                $arregloDatos[recibo] = $unAnticipo->num_recibo;
                $arregloDatos[secuencia] = $arregloDatos[secuencia] + 1;
                $this->archivoLinea($arregloDatos, $unArchivo);
              }
              // Aqui se crea la contrapartida ANTICIPO CREDITO   Cuando los anticipos son mayores al valor de la factura
              $arregloDatos[valor] = $arregloDatos[total] * -1;
              $arregloDatos[naturaleza] = 'C';
              $arregloDatos[tipo_doc] = 'F';
              // Se trae la cuenta que le asignaron de la BD
              $unCuentaTercero = new Interfase();
              $unCuentaTercero->saldo_a_favor($arregloDatos);
              $unCuentaTercero->fetch();
              $arregloDatos[cuenta] = $unCuentaTercero->cuenta; //Julio de 2010 si es credito se cambia la cuenta
              $arregloDatos[nombre_servicio] = $unCuentaTercero->nombre;
              switch($_SESSION['sede']) {
                case '11'://11 - Alcomex
                  $arregloDatos[comprobante_cruce] = '001'; //antes habia un 003
                  $arregloDatos[recibo] = $arregloDatos[recibo_anticipo];
                  break;
                case '22'://Alcomex ZF
                  $arregloDatos[comprobante_cruce] = '002';
                  $arregloDatos[recibo] = $arregloDatos[recibo_anticipo];
                  break;
                default:
                  $arregloDatos[comprobante_cruce] = '001';
                  $arregloDatos[recibo] = $arregloDatos[recibo_anticipo];
              }
              $total_facturaAux = $arregloDatos[valor_anticipo] + $arregloDatos[total_anticipos] + $arregloDatos[total];
              if($arregloDatos[total] < 0) {
                $arregloDatos[recibo] = $arregloDatos[numero_oficial]; // 24 de noviembre de 2011   si hay saldo a favor 
                $arregloDatos[secuencia] = $arregloDatos[secuencia] + 1;
                $this->archivoLinea($arregloDatos, $unArchivo);
              }
            }
            break;
          case 7:// Cuentas Por Cobrar
            $arregloDatos[nit] = $arregloDatos[facturado_a];
            $total_factura = $arregloDatos[valor_anticipo] + $arregloDatos[total_anticipos] + $arregloDatos[total];
            $arregloDatos[nombre_servicio] = $unConcepto->nombre;
            $arregloDatos[naturaleza] = $unConcepto->naturaleza;
            $arregloDatos[cuenta] = $unConcepto->cuenta;
            $longitud_nit = strlen($arregloDatos[intermediario]); // antes estaba el [nit] 28/11/2008 
            $nitCliente = substr($arregloDatos[intermediario], 0, $longitud_nit - 1);
            if($arregloDatos[tipo_cliente] == 2) { //ES UNA FILIAL
              $arregloDatos[cuenta] = $arregloDatos[cuenta_filial];     // cuando crean una nueva filial se crea una cuenta con el consecutivo
              $arregloDatos[nombre_servicio] = $arregloDatos[razon_social];
            } else {
              $arregloDatos[cuenta] = '1305050101';
            }
            if($arregloDatos[tipo_cliente] == 9) { // Exterior
              $arregloDatos[cuenta] = '1305100100';
            }
            if($arregloDatos[valor_anticipo] + $arregloDatos[total_anticipos] <= $total_factura) {
              $arregloDatos[valor] = $arregloDatos[total];
              if($arregloDatos[valor] > 0) {
                $arregloDatos[secuencia] = $arregloDatos[secuencia] + 1;
                $this->archivoLinea($arregloDatos, $unArchivo);
              }
            }
            break;
          case 8:// Terceros
            //NOV 17 2011  CUENTAS TERCEROS	
            // secuencia del DO
            $arregloDatos[nit] = $arregloDatos[por_cuenta_de];
            $arregloDatos[nombre_servicio] = $unConcepto->nombre;
            $arregloDatos[naturaleza] = $unConcepto->naturaleza;
            $arregloDatos[cuenta] = $unConcepto->cuenta;
            $arregloDatos[prorcentaje_iva] = $unConcepto->iva;
            $arregloDatos[centro_costo] = '118';

            switch($_SESSION['sede']) {
              case '25':// Alcomex
                $arregloDatos[centro_costo] = '060';
                break;
            }

            $unIvaT = new Interfase();
            $unIvaT->traerIvaTerceros($arregloDatos);
            $valor = 0;
            while($unIvaT->fetch()) {
              $arregloDatos[tipo_doc] = 'P';
              $arregloDatos[nit_tercero] = $unIvaT->nit_tercero;
              $arregloDatos[es_del_grupo] = $unIvaT->tipo; // se toma el campo para saber si es del grupo

              switch($_SESSION['sede']) {
                case '11'://Alcomex
                  $comprobante_cruce = '003';
                  $longitud_nit = strlen($arregloDatos[nit_tercero]);
                  $nit = substr($arregloDatos[nit_tercero], 0, $longitud_nit - 1);
                  break;
                case '25':
                  $comprobante_cruce = '002';
                  // Correo Entre compañías y terceros en la posición de secuencia del documento cruce del vencimiento (204-206), para el valor total 001 (1380250100) es, y para IVA tercero 002 (1380250200). 
                  break;
              }
              $longitud_nit = strlen($arregloDatos[nit_tercero]);
              $nit = substr($arregloDatos[nit_tercero], 0, $longitud_nit - 1);
              if($arregloDatos[es_del_grupo] == 2) { //18 de Agosto de 2010 si es del grupo se coloca 003
                $arregloDatos[comprobante_cruce] = '003'; //$comprobante_cruce;
              } else {
                $arregloDatos[comprobante_cruce] = '001'; // Otros
              }

              $arregloDatos[recibo] = $unIvaT->factura_detalle;

              if($arregloDatos[valor] > 0) {
                if($arregloDatos[nit_tercero] == '8001972682') { //Julio de 2010 se el el tercero DIAN  se utiliza otro documento cruce
                  $arregloDatos[comprobante_cruce] = '008';
                }
                $arregloDatos[secuencia] = $arregloDatos[secuencia] + 1;
                $arregloDatos[valor] = $unIvaT->valor + $unIvaT->iva; // 27 de Agosto de 2009  se unifico IVA y Pago de Terceros
                echo 'test2s';
                $this->archivoLinea($arregloDatos, $unArchivo);
              }
            }
            break;
        }
        $arregloDatos[centro_costo] = $centro_costo_aux;
      } //Por cada tipo de concepto se traen todas las cuentas que pertenezcan a éste
    }
  }

  function archivoLineaSigo($arregloDatos, $unArchivo) {
    $fecha = fechaddmmaaaa($arregloDatos[fecha_factura]);

    $linea.=$fecha . ',';
    $linea.='FV,';
    $linea.=$arregloDatos[numero_oficial] . ',';
    $linea.=$arregloDatos[nombreservicio] . ',';
    $linea.=$arregloDatos[nit] . ',';
    $linea.=$arregloDatos[nombre_cliente] . ',';
    $linea.=$arregloDatos[centro_costo] . ',';
    $linea.=$arregloDatos[cuenta] . ',';
    $linea.=$arregloDatos[naturaleza] . ',';
    $linea.=$arregloDatos[valor] . ',,';

    //Sucursal
    $unArchivo->escribirContenido($linea . "\n");
  }

  function archivoLinea($arregloDatos,$unArchivo) {
    $linea = $arregloDatos[tipo_comprobante];                                           // Tipo Comprobante
    $linea.=$arregloDatos[codigo_comprobante];            // Codigo Comprobante
    $linea.=str_pad(trim($arregloDatos[numero_oficial]), 11, '0', STR_PAD_LEFT);    // Numero Documento
    $linea.=str_pad(trim($arregloDatos[secuencia]), 5, '0', STR_PAD_LEFT);         // Secuencia
    $longitud_nit = strlen($arregloDatos[nit]);
    $nit = substr($arregloDatos[nit], 0, $longitud_nit - 1);
    $linea.=str_pad(trim($nit), 13, '0', STR_PAD_LEFT);              // Nit
    $linea.='000';                             //Sucursal
    $linea.=str_pad(trim($arregloDatos[cuenta]), 10, '0', STR_PAD_RIGHT);       //Cuenta
    $linea.=str_pad('000', 13, '0', STR_PAD_LEFT);        //Producto
    $linea.=str_pad(trim($arregloDatos[fecha_factura]), 8, '0', STR_PAD_LEFT);        //Fecha Documento
    $linea.=str_pad(trim($arregloDatos[centro_costo]), 4, '0', STR_PAD_LEFT);        // Centro Costo

    $linea.=str_pad(trim($arregloDatos[subcentro_costo]), 3, '0', STR_PAD_LEFT);        // SubCentro Costo
    $linea.=str_pad(trim($arregloDatos[nombre_servicio]), 50, ' ', STR_PAD_RIGHT);      //Descripcion del Movimiento
    $linea.=str_pad(trim($arregloDatos[naturaleza]), 1, '0', STR_PAD_LEFT);        //Debito o Credito
    $linea.=str_pad(trim($arregloDatos[valor]), 13, '0', STR_PAD_LEFT) . '00';  //Valor del Movimiento
    $linea.=str_pad(trim($arregloDatos[base_retencion]), 13, '0', STR_PAD_LEFT) . '00';   //Base de Retencion

    if($arregloDatos[vendedor] == '99') {
      $arregloDatos[vendedor] = '01';
    }
    $linea.=str_pad(trim($arregloDatos[vendedor]), 4, '0', STR_PAD_LEFT); //Codigo del Vendedor 
    $linea.='0000'; //Código de la Ciudad
    $linea.='000'; //Código de la Zona
    $linea.='0000'; //Código de la Bodega
    $linea.='000'; //Código de la Ubicacion
    $linea.='000000000000100'; //Cantidad
    $linea.=$arregloDatos[tipo_doc]; //Tipo de Documento Cruce
    $linea.=$arregloDatos[comprobante_cruce]; //Código de Comprobante uuu

    $linea.=str_pad($arregloDatos[recibo], 11, '0', STR_PAD_LEFT); //Número de Documento Cruce
    $linea.=str_pad($arregloDatos[secuencia_cruce], 3, '0', STR_PAD_LEFT); //Secuencia del Documento Cruce												              
    $linea.=str_pad(trim($arregloDatos[fecha_pago]), 8, '0', STR_PAD_LEFT); // Fecha del Vencimiento documento Cruce
    $linea.='0001';                 // Forma de Pago
    $linea.='00';                             //Codigo del Banco

    $unArchivo->escribirContenido($linea . "\n");
  }

  // Adicionales Helisa
  function conceptosCalculados($arregloDatos, $unArchivo) {
    // SE TOMAN LOS VALORES DE TODAS LAS VARIABLES DE LA ULTIMA FACTURA
    $unTotal = new Interfase();  // Hace la sumatoria de cada Concepto

    $unTipo = new Interfase();
    $unTipo->conceptosAdicionales($arregloDatos);
    $unConcepto = new Interfase();  // Recorre los conceptos de cada tipo

    while($unTipo->fetch()) {
      $arregloDatos[tipo_servicio] = $unTipo->tipo;
      $unConcepto->otrosConceptos($arregloDatos);

      while ($unConcepto->fetch()) {
        $arregloDatos[prorcentaje_iva] = 0;
        $arregloDatos[prorcentaje_rte] = 0;
        $arregloDatos[prorcentaje_ica] = 0;
        $arregloDatos[base_retencion] = 0;

        switch ($unConcepto->tipo) {
          case '7':// Cuentas Por Cobrar
            $total_factura = $arregloDatos[valor_anticipo] + $arregloDatos[total_anticipos] + $arregloDatos[total];
            $arregloDatos[nombreservicio] = $unConcepto->nombre;
            $arregloDatos[naturaleza] = $unConcepto->naturaleza;
            $arregloDatos[cuenta] = $unConcepto->cuenta;
            $longitud_nit = strlen($arregloDatos[intermediario]); // antes estaba el [nit] 28/11/2008 
            $nitCliente = substr($arregloDatos[intermediario], 0, $longitud_nit - 1);
            if($arregloDatos[tipo_cliente] == 2) { //ES UNA FILIAL
              $arregloDatos[cuenta] = $arregloDatos[cuenta_filial];     // cuando crean una nueva filial se crea una cuenta con el consecutivo
              $arregloDatos[nombreservicio] = $arregloDatos[razon_social];
            } else {
              $arregloDatos[cuenta] = '13050501';
            }
            if($arregloDatos[tipo_cliente] == 9) { // Exterior
              $arregloDatos[cuenta] = '13051001';
            }

            if($arregloDatos[valor_anticipo] + $arregloDatos[total_anticipos] <= $total_factura) {
              $arregloDatos[valor] = $arregloDatos[total];
              //quitando deducciones
              //$deducciones=$arregloDatos[rte_ivam]+$arregloDatos[rte_fuentem]+$arregloDatos[rte_icam];
              $deducciones = round($arregloDatos[rte_ivam]) + round($arregloDatos[rte_fuentem]) + round($arregloDatos[rte_icam]); //26/01/2013
              $arregloDatos[valor] = $arregloDatos[valor] - $deducciones;
              if($arregloDatos[valor] > 0) {
                $arregloDatos[valor] = round($arregloDatos[valor]);
                if(trim($unConcepto->naturaleza) == "D") {
                  $this->debitos = $this->debitos + $arregloDatos[valor];
                } else {
                  $this->creditos = $this->creditos + $arregloDatos[valor];
                }
                // Aqui se Ajusta  la diferencia en el peso
                $diferencia = $this->creditos - $this->debitos;

                if($diferencia == -1) {
                  echo "$arregloDatos[numero_oficial] Creditos $this->creditos  debitos $this->debitos Ajustando Diferencia $diferencia <br>";
                  $arregloDatos[valor] = $arregloDatos[valor] - 1;
                }
                if($diferencia == 1) {
                  $arregloDatos[valor] = $arregloDatos[valor] + 1;
                }
                $arregloDatos[secuencia] = $arregloDatos[secuencia] + 1;
                $this->archivoLineaSigo($arregloDatos,$unArchivo);
              }
            }
            break;
          case '2'; //IVA
            $arregloDatos[nombreservicio] = $unConcepto->nombre;
            $arregloDatos[naturaleza] = $unConcepto->naturaleza;
            $arregloDatos[cuenta] = $unConcepto->cuenta;
            $arregloDatos[prorcentaje_iva] = $unConcepto->iva;

            $arregloDatos[base_retencion] = $unTotal->traerDetalle($arregloDatos);   // Se guarda la Base de Calculo de Iva.
            $arregloDatos[valor] = round($arregloDatos[base_retencion] * $unConcepto->iva / 100);

            if(trim($unConcepto->naturaleza) == "D") {
              $this->debitos = $this->debitos + $arregloDatos[valor];
            } else {
              $this->creditos = $this->creditos + $arregloDatos[valor];
            }

            if($arregloDatos[valor] > 0) {
              $arregloDatos[secuencia] = $arregloDatos[secuencia] + 1;
              $this->archivoLineaSigo($arregloDatos, $unArchivo);
            }
            break;
          case '801'; //Cuentas Por Cobrar
            $total_factura = $arregloDatos[valor_anticipo] + $arregloDatos[total_anticipos] + $arregloDatos[total];
            $arregloDatos[nombreservicio] = $unConcepto->nombre;
            $arregloDatos[naturaleza] = $unConcepto->naturaleza;
            $arregloDatos[cuenta] = $unConcepto->cuenta;
            if($arregloDatos[valor_anticipo] + $arregloDatos[total_anticipos] <= $total_factura) {
              $arregloDatos[valor] = $arregloDatos[total];
              if($arregloDatos[valor] > 0) {
                $this->archivoLineaSigo($arregloDatos, $unArchivo);
              }
            }
            break;
          case 3:// Conceptos que son rete Fuente
            $arregloDatos[nombreservicio] = $unConcepto->nombre;
            $arregloDatos[naturaleza] = $unConcepto->naturaleza;
            $arregloDatos[cuenta] = $unConcepto->cuenta;
            $arregloDatos[prorcentaje_rte] = $unConcepto->rte_fuente;
            $arregloDatos[base_retencion] = $unTotal->traerDetalle($arregloDatos);   // Se guarda la Base de Calculo de Iva.
            $arregloDatos[valor] = round($arregloDatos[base_retencion] * $unConcepto->rte_fuente / 100);
            if($arregloDatos[valor] > 0 and $arregloDatos[rte_fuentem] > 0) {
              if(trim($unConcepto->naturaleza) == "D") {
                $this->debitos = $this->debitos + $arregloDatos[valor];
              } else {
                $this->creditos = $this->creditos + $arregloDatos[valor];
              }
              $arregloDatos[secuencia] = $arregloDatos[secuencia] + 1;
              $this->archivoLineaSigo($arregloDatos, $unArchivo);
            }
            break;
          case 4: // Conceptos que son RETE ICA
            $arregloDatos[nombreservicio] = $unConcepto->nombre;
            $arregloDatos[naturaleza] = $unConcepto->naturaleza;
            $arregloDatos[cuenta] = $unConcepto->cuenta;
            $arregloDatos[porcentaje_rete_ica] = $unConcepto->rte_ica;
            $arregloDatos[prorcentaje_ica] = $unConcepto->rte_ica;

            $arregloDatos[base_retencion] = $unTotal->traerDetalle($arregloDatos);   // Se guarda la Base de Calculo de Iva.
            $arregloDatos[valor] = round($arregloDatos[base_retencion] * $unConcepto->rte_ica / 100);
            if($arregloDatos[valor] > 0 and $arregloDatos[rte_icam] > 0) { // es necesario condicionar el rte_ica del maestro para saber si aplicaba o no ICA
              if(trim($unConcepto->naturaleza) == "D") {
                $this->debitos = $this->debitos + $arregloDatos[valor];
              } else {
                $this->creditos = $this->creditos + $arregloDatos[valor];
              }
              $arregloDatos[secuencia] = $arregloDatos[secuencia] + 1;
              $this->archivoLineaSigo($arregloDatos, $unArchivo);
            }
            break;
          case 5: //Conceptos que son Rete Ivas
            $unReteIva = new Interfase();
            $arregloDatos[nombreservicio] = $unConcepto->nombre;
            $arregloDatos[naturaleza] = $unConcepto->naturaleza;
            $arregloDatos[cuenta] = $unConcepto->cuenta;
            $arregloDatos[valor] = round($arregloDatos[rte_iva]);
            $base_rete_iva = 0; $arregloDatos[valor] = 0;
            $unReteIva->getBase($arregloDatos);
            $unReteIva->fetch();
            $base_rete_iva = $unReteIva->iva;
            $arregloDatos[valor] = round($unReteIva->rte_iva);
            if($base_rete_iva > 0) {
              $arregloDatos[base_retencion] = round($base_rete_iva);
              if(trim($unConcepto->naturaleza) == "D") {
                $this->debitos = $this->debitos + $arregloDatos[valor];
              } else {
                $this->creditos = $this->creditos + $arregloDatos[valor];
              }
              $this->archivoLineaSigo($arregloDatos, $unArchivo);
            }
            break;
        } //Fin Switch
      }
    }
    $this->creditos = 0;
  }
}
?>