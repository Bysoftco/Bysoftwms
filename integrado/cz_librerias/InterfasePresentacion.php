<?php
require_once("HTML/Template/IT.php");
require_once("InterfaseDatos.php");
require_once("montoEscrito.php");
require_once("Archivo.php");

// Caracter & Eliminado - 06/03/2019
class InterfasePresentacion {
  var $datos;
  var $plantilla;

  function InterfasePresentacion(&$datos) {
    $this->datos = $datos;
    $this->plantilla = new HTML_Template_IT(PLANTILLAS);
  }

  //Función que coloca los datos que vienen de la BD
  function setDatos($arregloDatos,&$datos,&$plantilla) {
    foreach ($datos as $key => $value) {
      $plantilla->setVariable($key, $value);
    }
  }

  function mantenerDatos($arregloCampos,&$plantilla) {
    $plantilla = $plantilla;
    if(is_array($arregloCampos)) {
      foreach ($arregloCampos as $key => $value) {
        $plantilla->setVariable($key, $value);
      }
    }
  }

  //Función que Arma una lista
  function getLista($arregloDatos,$seleccion,&$plantilla) {
    $unaLista = new Interfase();
    $lista = $unaLista->lista($arregloDatos['tabla'],$arregloDatos['condicion'],$arregloDatos['campoCondicion']);
    $lista = $unaLista->armSelect($lista,'[seleccione]',$seleccion);
    $plantilla->setVariable($arregloDatos['labelLista'], $lista);
  }

  // Arma cada Formulario o función en pantalla
  function setFuncion($arregloDatos,$unDatos) {
    $unDatos = new Interfase();
    $metodo = $arregloDatos['thisFunction'];

    $r = $unDatos->$metodo($arregloDatos);

    if(!empty($arregloDatos['thisFunctionAux'])) {
      $metodoaux = $arregloDatos['thisFunctionAux'];
      $this->$metodoaux($arregloDatos);
    }

    $unaPlantilla = new HTML_Template_IT(PLANTILLAS);
    $unaPlantilla->loadTemplateFile($arregloDatos['plantilla'],true,true);
    $unaPlantilla->setVariable('comodin',' ');
    if(!empty($unDatos->mensaje_error)) {
      $unaPlantilla->setVariable('mensaje',$unDatos->mensaje_error);
      $unaPlantilla->setVariable('estilo',$unDatos->mensaje_error);
    }

    $this->mantenerDatos($arregloDatos, $unaPlantilla);
    $arregloDatos['n'] = 0;
    $rows = $unDatos->db->countRows();
    $unaPlantilla->setVariable('num_registros',$rows);
    while($obj=$unDatos->db->fetch()) {
      $odd = $arregloDatos['n'] % 2 ? 'odd' : '';
      $arregloDatos['n'] = $arregloDatos['n'] + 1;
      $unaPlantilla->setCurrentBlock('ROW');
      $this->setDatos($arregloDatos, $unDatos, $unaPlantilla);
      $metodo = $arregloDatos['thisFunction'];
      $this->$metodo($arregloDatos,$obj,$unaPlantilla);
      $unaPlantilla->setVariable('n', $arregloDatos['n']);
      $unaPlantilla->setVariable('odd', $odd);
      $unaPlantilla->parseCurrentBlock();
    }

    if($rows == 0) {
      $unaPlantilla->setVariable('mensaje','No hay registros para listar, ' . $unDatos->mensaje_error);
      $unaPlantilla->setVariable('estilo','ui-state-highlight');
      $unaPlantilla->setVariable('mostrarCuerpo','none');
    }

    if($arregloDatos['mostrar']) {
      $unaPlantilla->show();
    } else {
      $unaPlantilla->setVariable('cuenta', $this->cuenta);
      return $unaPlantilla->get();
    }
  }

  function cargaPlantilla($arregloDatos) {
    $unAplicaciones = new Levante();
    $formularioPlantilla = new HTML_Template_IT(PLANTILLAS);
    $formularioPlantilla->loadTemplateFile($arregloDatos['plantilla'],true,true);
    $formularioPlantilla->setVariable('comodin',' ');
    $this->mantenerDatos($arregloDatos,$formularioPlantilla);

    $metodo = $arregloDatos['thisFunction'];
    $this->$metodo($arregloDatos,$this->datos,$formularioPlantilla);
    if($arregloDatos['mostrar']) {
      $formularioPlantilla->show();
    } else {
      return $formularioPlantilla->get();
    }
  }

  // Función Principal para las consultas
  function paraGenerarInterfase($arregloDatos) {
    $this->plantilla->loadTemplateFile('interfaseMaestroConsulta.html',true,true);
    $this->mantenerDatos($arregloDatos,$this->plantilla);
    $this->plantilla->setVariable('comodin','');

    if(!empty($arregloDatos['filtro'])) {
      $arregloDatos['mostrar'] = 0;
      $arregloDatos['plantilla'] = 'interfaseListado.html';
      $arregloDatos['thisFunction'] = 'listarInterfases';
      $htmlListado = $this->setFuncion($arregloDatos,$unDatos);
      $this->plantilla->setVariable('htmlListado',$htmlListado);
    } else {                
      $arregloDatos['thisFunction'] = 'filtroConsulta';
      $arregloDatos['plantilla'] = 'interfaseFiltro.html';
      $arregloDatos['mostrar'] = 0;
      if($arregloDatos['accion'] == 'new') { $arregloDatos['requiredName'] = 'required'; }
      $htmlFiltro = $this->cargaPlantilla($arregloDatos);
      $this->plantilla->setVariable('filtroEntradaConsulta',$htmlFiltro);
    }
    $this->plantilla->show();
  }

  function listarInterfases($arregloDatos,&$datos,&$plantilla) {
    $arregloDatos['valor'] = number_format(round($datos->valor), 0, ',', '.');
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  function filtroConsulta($arregloDatos,&$datos,&$plantilla) {
  }

  function filtro($arregloDatos) {
  }

  function valoresFormateados($arregloDatos,$unDatos) {
    $arregloDatos['valorf'] = number_format(round($unDatos->valor), 0, ',', '.');
    $arregloDatos['ivaf'] = number_format(round($unDatos->iva), 0, ',', '.');
    $arregloDatos['subtotalf'] = number_format(round($unDatos->subtotal), 0, ',', '.');
    $arregloDatos['rte_fuentef'] = number_format(round($unDatos->rte_fuente), 0, ',', '.');
    $arregloDatos['rte_ivaf'] = number_format(round($unDatos->rte_iva), 0, ',', '.');
    $arregloDatos['rte_icaf'] = number_format(round($unDatos->rte_ica), 0, ',', '.');
    $arregloDatos['totalf'] = number_format(round($unDatos->total), 0, ',', '.');
    $arregloDatos['anticipof'] = number_format(round($unDatos->valor_anticipo), 0, ',', '.');
  }

  function getInterfase($arregloDatos,$unDatos) {  //Módulo Solo 
  }

  function generaInterfase($arregloDatos) {  //Módulo Solo Factura
    $unArchivo = new Archivo();
    $unArchivo->crear('integrado/_files/interfase.txt');

    $this->plantilla->loadTemplateFile('interfaseListadoDetalle.html',true,true);
    if(!empty($arregloDatos['interfase'])) {
      $this->plantilla->setVariable('interfase',$arregloDatos['interfase']);
    } else {
      $this->plantilla->setVariable('mostrarTabla','none');
    }
    
    $this->datos->getInterfase($arregloDatos);
    $rows = $this->datos->db->countRows();
    
    $n = $total_valor = 0;

    if($rows == 0) {
      if(!empty($arregloDatos['accion'])) {
        $mensaje = 'No hay Registros a que generar Interfase';
        $this->plantilla->setVariable('mostrarInterfase','none');
        $this->plantilla->setVariable('mostrarTabla','none');
        $estilo = 'error';
      } else {
        $mensaje = 'No existe ninguna Interfase con el nombre Suministrado';
        $this->plantilla->setVariable('mostrarInterfase','none');
        $this->plantilla->setVariable('mostrarTabla','none');
        $estilo = 'error';
      }
    }

    switch($arregloDatos['tipo_interfase']) {
      case 1:
        // Interfase Helisa
        $this->getInterfaseElisa($arregloDatos,$unArchivo);
        break;
      case 2:
        // Interface SIIGO
        $this->getInterfaseSigo($arregloDatos,$unArchivo);
        break;
      case 3:
        // Interface FoxconPro
        $this->getInterfaseFoxconPro($arregloDatos,$unArchivo);
        break;
      case 4:
        // Interface WorldOffice
        $this->getInterfaseWorldOffice($arregloDatos,$unArchivo);
        break;
    }
  }

  // OJO aqui genera interfase Helisa
  function getInterfaseSigo($arregloDatos,$unArchivo) {
    $n = 0;
    $facturas = 1;
    $rows = $this->datos->db->countRows();
    while($res = $this->datos->db->fetch()) {
      $nuevo = 0;
      if($res->factura <> $anterior) {
        if($n > 0) {
          $nuevo = 1;
          $facturas = $facturas + 1;
        }
      }
      if($nuevo) { // Se calculan los conceptos Anteriores con variables anteriores 
        $debitos = 0;
        $arregloDatos['rte_fuentem'] = $arregloDatos['rte_fuentem_ant'];
        $arregloDatos['rte_icam'] = $arregloDatos['rte_icam_ant'];
        $arregloDatos['rte_ivam'] = $arregloDatos['rte_ivam_ant'];
        $arregloDatos['fecha_factura'] = $arregloDatos['fecha_factura_ant'];
        $arregloDatos['numero_oficial'] = $arregloDatos['numero_oficial_ant'];
        $arregloDatos['nit_ant'] = $arregloDatos['nit'];
        $this->debitos = 0;
        $this->conceptosCalculados($arregloDatos,$unArchivo);
      }
      $arregloDatos['factura'] = $res->factura;
      $this->plantilla->setCurrentBlock('ROW');
      $this->plantilla->setVariable('n',$n + 1);
      $this->plantilla->setVariable('cuenta',$res->cuenta);
      $this->plantilla->setVariable('valor',round($res->valor));
      $this->plantilla->setVariable('factura',$res->factura);
      $this->plantilla->setVariable('fecha_factura',$res->fecha_factura);
      $this->plantilla->setVariable('nombreservicio',$res->nombreservicio);
      $this->plantilla->setVariable('numero_oficial',$res->numero_oficial);
      $total_valor = $total_valor + $res->valor;

      $arregloDatos['total'] = round($res->subtotal) + round($res->ivam) - ($res->valor_anticipo + $arregloDatos['total_anticipos']);

      $n = $n + 1; // variables de getInterfase

      $arregloDatos['rte_fuentem'] = $res->rte_fuentem;
      $arregloDatos['rte_icam'] = $res->rte_icam;
      $arregloDatos['rte_ivam'] = $res->rte_ivam;
      $arregloDatos['fecha_factura'] = $res->fecha_factura;
      $arregloDatos['numero_oficial'] = $res->numero_oficial;
      $arregloDatos['nit'] = $res->nit;

      $arregloDatos['concepto'] = $res->concepto;
      $arregloDatos['nombreservicio'] = $res->nombreservicio;
      $arregloDatos['nombre_cliente'] = $res->nombre_cliente;
      $arregloDatos['centro_costo'] = $res->centro_costo;
      $arregloDatos['cuenta'] = $res->cuenta;
      $arregloDatos['cuenta_aux1'] = $res->cuenta;
      $arregloDatos['naturaleza'] = $res->naturaleza;
      $arregloDatos['valor'] = round($res->valor);

      if(trim($res->naturaleza) == "D") {
        $this->debitos = $this->debitos + $arregloDatos['valor'];
      } else {
        $this->creditos = $this->creditos + $arregloDatos['valor'];
      }

      $this->archivoLineaSigo($arregloDatos,$unArchivo);
      $anterior = $arregloDatos['factura'];

      if($factura_aux <> $arregloDatos['factura']) { }
      if($rows == $n) {// Se llama  conceptos calculados para la ultima factura
      }

      $factura_ant = $res->factura;
      $anterior = $res->factura;
      $factura_aux = $res->factura;

      $arregloDatos['rte_fuentem_ant'] = $res->rte_fuentem;
      $arregloDatos['rte_icam_ant'] = $res->rte_icam;
      $arregloDatos['rte_ivam_ant'] = $res->rte_ivam;
      $arregloDatos['fecha_factura_ant'] = $res->fecha_factura;
      $arregloDatos['numero_oficial_ant'] = $res->numero_oficial;
      $arregloDatos['nit_ant'] = $res->nit;

      $this->plantilla->parseCurrentBlock();
    }

    $this->conceptosCalculados($arregloDatos,$unArchivo); // Aplica para la última Factura o cuando hay solo una
    if($res->interfase == 'SinInterfase') {
      $this->plantilla->setVariable('mostrarTabla','none');
    }

    $this->plantilla->setVariable('total_valor',$total_valor);
    $this->plantilla->setVariable('mensaje',$mensaje);
    $this->plantilla->setVariable('estilo',$estilo);
    $this->plantilla->setvariable('tipo_interfase',$arregloDatos['tipo_interfase']);

    $this->plantilla->show();
  }

  // OJO esta genera interface SIIGO
  function getInterfaseElisa($arregloDatos,$unArchivo) {
    $secuencia = $factura_actual = 0;
    $arregloDatos['secuencia_cruce'] = '001';
    $arregloDatos['tipo_comprobante'] = 'F';
    $arregloDatos['base_retencion'] = '00';
    $arregloDatos['codigo_comprobante'] = '001';
    switch($_SESSION['sede']) {
      case '25':
      case '11':
        $arregloDatos['codigo_comprobante'] = '001'; // Dep Alcomex y ServialComex  digito Ver 7
        break;
      case '22':
        $arregloDatos['codigo_comprobante'] = '002'; // Alcomex ZF
        break;
      default:
        $arregloDatos['codigo_comprobante'] = '001'; // Servialcomex
    }
    $arregloDatos['comprobante_cruce'] = '001';
    while($res = $this->datos->db->fetch()) {
      $arregloDatos['total_interfase'] = $arregloDatos['total_interfase'] + $res->valor;
      $arregloDatos['total_interfase_f'] = number_format($arregloDatos['total_interfase'],0,',','.');
      if($res->anticipo > 0 OR !empty($res->recibo)) {      //Hay Anticipo entonces R
        $arregloDatos['tipo_doc'] = 'F';
        $arregloDatos['recibo'] = $res->numero_oficial;
        $arregloDatos['reciboAux'] = $res->recibo;
      } else {
        $arregloDatos['tipo_doc'] = 'F';
        $arregloDatos['recibo'] = $res->numero_oficial;
      }

      if($factura_actual <> $res->factura and $factura_actual <> 0) {
        $this->registrosAdicionales($arregloDatos,$unArchivo);
      }

      $arregloDatos['tipo_cliente'] = $res->tipo;
      $arregloDatos['cuenta_filial'] = $res->cuenta_filial;
      $arregloDatos['razon_social'] = $res->razon_social;

      $arregloDatos['factura'] = $res->factura;
      $arregloDatos['recibo_anticipo'] = $res->recibo;
      $arregloDatos['Auxfactura'] = $res->factura;
      $arregloDatos['numero_oficial'] = $res->numero_oficial;
      $arregloDatos['iva'] = $res->ivam;
      $arregloDatos['rte_fuente'] = $res->rte_fuentem;
      $arregloDatos['rte_fuented'] = round($res->rte_fuented);
      $arregloDatos['rte_iva'] = $res->rte_ivam;
      $arregloDatos['rte_iva_fac'] = $res->rte_ivam;
      $arregloDatos['rte_ica'] = $res->rte_icam;
      $arregloDatos['intermediario'] = $res->intermediario;
      $arregloDatos['anticipo'] = $res->anticipo;

      $arregloDatos['valor_anticipo'] = $res->valor_anticipo;
      $arregloDatos['efectivo'] = $res->efectivo;
      $arregloDatos['cheque'] = $res->cheque;
      $arregloDatos['banco'] = $res->banco;
      $arregloDatos['cuenta'] = $res->cuenta;

      $arregloDatos['facturado_a'] = $res->nit;
      $arregloDatos['por_cuenta_de'] = $res->intermediario;

      $arregloDatos['nit'] = $arregloDatos['por_cuenta_de'];

      $arregloDatos['fecha_factura'] = formatoFecha($res->fecha_factura);
      $arregloDatos['fecha_pago'] = formatoFecha($res->fecha_salida);
      $arregloDatos['forma_pago'] = $res->banco.$res->cheque.$res->efectivo;
      $arregloDatos['nombre_servicio'] = $res->nombreservicio;

      $arregloDatos['naturaleza'] = $res->naturaleza;
      $arregloDatos['cuenta'] = $res->cuenta;
      $arregloDatos['valor'] = round($res->valor);
      //Se trae el total de Anticipos
      $unAnticipo = new Interfase();
      $arregloDatos['total_anticipos'] = $unAnticipo->totalAnticipos($arregloDatos);

      //Se Recalcula Total por el tema  del redondeo
      $arregloDatos['total'] = round($res->subtotal) + round($res->ivam) - round($res->rte_fuentem) - round($res->rte_ivam) - round($res->rte_icam) - ($res->valor_anticipo + $arregloDatos['total_anticipos']);
      $arregloDatos['vendedor'] = $res->codigo_vendedor;
      $arregloDatos['centro_costo'] = $res->centro_costo;

      $arregloDatos['subcentro_costo'] = $res->subcentro_costo;
      $arregloDatos['credito'] = $res->credito;

      if($factura_actual == 0) {
        $factura_actual = $res->factura;
      }
      if($factura_actual <> $res->factura) {
        $secuencia = 0;
      }
      $secuencia = $secuencia + 1;
      $arregloDatos['secuencia'] = $secuencia;
      $this->plantilla->setCurrentBlock('ROW');
      $arregloDatos['valor_f'] = number_format($arregloDatos['valor'],0,',','.');
      $this->mantenerDatos($arregloDatos,$this->plantilla);
      $this->plantilla->setVariable('n', $n + 1);
      $this->plantilla->setVariable('nombreservicio',$res->nombreservicio);
      $total_valor = $total_valor + $res->valor;
      $n = $n + 1;

      $arregloDatos['aux_tipo_doc'] = $arregloDatos['tipo_doc'];
      $arregloDatos['aux_comprobante_cruce'] = $arregloDatos['comprobante_cruce'];
      $arregloDatos['aux_recibo'] = $arregloDatos['recibo'];

      if($res->idservicio == '8') {//Cuenta de Terceros no se tiene en cuenta en el consolidado 17-06-2008
        $secuencia = $secuencia - 1;
        $arregloDatos['secuencia'] = $secuencia;
      } else {
        if($res->tipo_tercero == 8) {
          $arregloDatos['tipo_doc'] = 'P';
          if($res->tipo == 2) { //2011
            $arregloDatos['comprobante_cruce'] = '003'; //$comprobante_cruce;
          } else {
            $arregloDatos['comprobante_cruce'] = '001'; // Otros
          }

          if($res->nit == '8001972682') { //Julio de 2010 se el el tercero DIAN  se utiliza otro documento cruce
            $arregloDatos['comprobante_cruce'] = '008';
          }
          $arregloDatos['recibo'] = $res->orden;
        }
        $this->archivoLinea($arregloDatos,$unArchivo);
      }

      $arregloDatos['tipo_doc'] = $arregloDatos['aux_tipo_doc'];
      $arregloDatos['comprobante_cruce'] = $arregloDatos['aux_comprobante_cruce'];
      $arregloDatos['recibo'] = $arregloDatos['aux_recibo'];

      if($factura_actual <> $res->factura) {
        $arregloDatos['factura'] = $factura_actual;
        $factura_actual = $res->factura;
      }

      if($rows == $n) {
        $arregloDatos['factura'] = $res->factura;
        $this->registrosAdicionales($arregloDatos,$unArchivo);
      }
      $this->plantilla->parseCurrentBlock();
    }

    $this->plantilla->setVariable('total_valor',$total_valor);
    $this->plantilla->setVariable('mensaje',$mensaje);
    $this->plantilla->setVariable('estilo',$estilo);
    $this->plantilla->setVariable('nombre_interfase',"Interface ".$arregloDatos['nombre_interfase']);
    $this->plantilla->setvariable('tipo_interfase',$arregloDatos['tipo_interfase']);
    $this->plantilla->show();
  }

  // Genera Interface FoxconPro - Fredy Salom - Sábado 01/01/2020
  function getInterfaseFoxconPro($arregloDatos,$unArchivo) {
    $n = 0;
    $facturas = 1;
    $rows = $this->datos->db->countRows();
    while($res=$this->datos->db->fetch()) {
      $nuevo = 0;

      if($res->factura <> $anterior) {
        if($n > 0) {
          $nuevo = 1;
          $facturas++;
        }
      }

      if($nuevo) { // Se calculan los conceptos Anteriores con variables anteriores 
        $debitos = 0;
        $arregloDatos['rte_fuentem'] = $arregloDatos['rte_fuentem_ant'];
        $arregloDatos['rte_icam'] = $arregloDatos['rte_icam_ant'];
        $arregloDatos['rte_ivam'] = $arregloDatos['rte_ivam_ant'];
        $arregloDatos['fecha_factura'] = $arregloDatos['fecha_factura_ant'];
        $arregloDatos['numero_oficial'] = $arregloDatos['numero_oficial_ant'];
        $arregloDatos['nit_ant'] = $arregloDatos['nit'];
        $this->debitos = 0;
        $this->calculosAdicionales($arregloDatos,$unArchivo);
      }
      $arregloDatos['factura'] = $res->factura;
      $this->plantilla->setCurrentBlock('ROW');
      $this->plantilla->setVariable('n',$n + 1);
      $this->plantilla->setVariable('cuenta',$res->cuenta);
      $this->plantilla->setVariable('valor',number_format(round($res->valor),0,',','.'));
      $this->plantilla->setVariable('factura',$res->factura);
      $this->plantilla->setVariable('fecha_factura',$res->fecha_factura);
      $this->plantilla->setVariable('nombreservicio',$res->nombreservicio);
      $this->plantilla->setVariable('numero_oficial',$res->numero_oficial);
      $total_valor = $total_valor + $res->valor;

      $arregloDatos['total'] = round($res->subtotal) + round($res->ivam) - ($res->valor_anticipo + $arregloDatos['total_anticipos']);

      $n++; // variables de getInterfase

      $arregloDatos['rte_fuentem'] = $res->rte_fuentem;
      $arregloDatos['rte_icam'] = $res->rte_icam;
      $arregloDatos['rte_ivam'] = $res->rte_ivam;
      $arregloDatos['fecha_factura'] = $res->fecha_factura;
			$arregloDatos['fecha_entrada'] = $res->fecha_entrada;
			$arregloDatos['fecha_salida'] = $res->fecha_salida;
      $arregloDatos['numero_oficial'] = $res->numero_oficial;
      $arregloDatos['nit'] = $res->nit;

      $arregloDatos['concepto'] = $res->concepto;
      $arregloDatos['nombreservicio'] = $res->nombreservicio;
			$arregloDatos['digito_verificacion'] = $res->digito_verificacion;
      $arregloDatos['nombre_cliente'] = $res->nombre_cliente;
			$arregloDatos['direccion'] = $res->direccion;
			$arregloDatos['telefonos_fijos'] = $res->telefonos_fijos;
			$arregloDatos['departamento'] = substr(trim($res->ciudad),0,2);
			$arregloDatos['ciudad'] = substr(trim($res->ciudad),-3);
      $arregloDatos['centro_costo'] = $res->centro_costo;
      $arregloDatos['cuenta'] = $res->cuenta;
      $arregloDatos['cuenta_aux1'] = $res->cuenta;
			$arregloDatos['observaciones'] = $res->observaciones;
      $arregloDatos['naturaleza'] = $res->naturaleza;
      // BASE para los Conceptos (Detalle)
			$arregloDatos['base'] = $res->valor;
      $arregloDatos['baseaux'] = $res->subtotal;
			$arregloDatos['porcentaje'] = $res->porcentaje;
			$arregloDatos['anulada'] = $res->anulada;
      $arregloDatos['valor'] = round($res->valor);

      if(trim($res->naturaleza) == "D") {
        $this->debitos = $this->debitos + $arregloDatos['valor'];
				$arregloDatos['debito'] = round($res->valor);
        $arregloDatos['credito'] = 0;
      } else {
        $this->creditos = $this->creditos + $arregloDatos['valor'];
        $arregloDatos['debito'] = 0;
				$arregloDatos['credito'] = round($res->valor);
      }

      $this->archivoLineaFoxconPro($arregloDatos,$unArchivo);
      $anterior = $arregloDatos['factura'];

      if($factura_aux <> $arregloDatos['factura']) {
      }
      if($rows == $n) {// Se llama  conceptos calculados para la ultima factura
      }

      $factura_ant = $res->factura;
      $anterior = $res->factura;
      $factura_aux = $res->factura;

      $arregloDatos['rte_fuentem_ant'] = $res->rte_fuentem;
      $arregloDatos['rte_icam_ant'] = $res->rte_icam;
      $arregloDatos['rte_ivam_ant'] = $res->rte_ivam;
      $arregloDatos['fecha_factura_ant'] = $res->fecha_factura;
      $arregloDatos['numero_oficial_ant'] = $res->numero_oficial;
      $arregloDatos['nit_ant'] = $res->nit;

      $this->plantilla->parseCurrentBlock();
    }

    $this->calculosAdicionales($arregloDatos,$unArchivo); // Aplica para la última Factura o cuando hay solo una
    if($res->interfase == 'SinInterfase') {
      $this->plantilla->setVariable('mostrarTabla','none');
    }

    $this->plantilla->setVariable('total_valor',$total_valor);
    $this->plantilla->setVariable('total_interfase_f',number_format($total_valor,0,',','.'));
    $this->plantilla->setVariable('mensaje',$mensaje);
    $this->plantilla->setVariable('estilo',$estilo);
    $this->plantilla->setvariable('tipo_interfase',$arregloDatos['tipo_interfase']);

    $this->plantilla->show();
  }

  // Cálculos Adicionales FoxconPro
  function calculosAdicionales($arregloDatos,$unArchivo) {
    // SE TOMAN LOS VALORES DE TODAS LAS VARIABLES DE LA ULTIMA FACTURA
    $unTotal = new Interfase();  // Hace la sumatoria de cada Concepto

    $unTipo = new Interfase();
    $unTipo->conceptosAdicionales($arregloDatos);
    $unConcepto = new Interfase();  // Recorre los conceptos de cada tipo

    while($obj1=$unTipo->db->fetch()) {
      $arregloDatos['tipo_servicio'] = $obj1->tipo;
      $unConcepto->otrosConceptos($arregloDatos);

      while($obj2=$unConcepto->db->fetch()) {
        $arregloDatos['prorcentaje_rte'] = 0;
        $arregloDatos['prorcentaje_ica'] = 0;
        $arregloDatos['base_retencion'] = 0;

        switch ($obj2->tipo) {
          case '7':// Cuentas Por Cobrar
            $total_factura = $arregloDatos['valor_anticipo'] + $arregloDatos['total_anticipos'] + $arregloDatos['total'];
            $arregloDatos['nombreservicio'] = $obj2->nombre;
            $arregloDatos['naturaleza'] = $obj2->naturaleza;
            $arregloDatos['cuenta'] = $obj2->cuenta;
            $arregloDatos['base'] = $arregloDatos['baseaux'];
            $longitud_nit = strlen($arregloDatos['intermediario']); // antes estaba el [nit] 28/11/2008 
            $nitCliente = substr($arregloDatos['intermediario'],0, $longitud_nit - 1);
            if($arregloDatos['tipo_cliente'] == 2) { //ES UNA FILIAL
              $arregloDatos['cuenta'] = $arregloDatos['cuenta_filial'];     // cuando crean una nueva filial se crea una cuenta con el consecutivo
              $arregloDatos['nombreservicio'] = $arregloDatos['razon_social'];
            } else {
              $arregloDatos['cuenta'] = '13050501';
            }
            if($arregloDatos['tipo_cliente'] == 9) { // Exterior
              $arregloDatos['cuenta'] = '13051001';
            }

            if($arregloDatos['valor_anticipo'] + $arregloDatos['total_anticipos'] <= $total_factura) {
              $arregloDatos['valor'] = $arregloDatos['total'];
              //quitando deducciones
              //$deducciones=$arregloDatos[rte_ivam]+$arregloDatos[rte_fuentem]+$arregloDatos[rte_icam];
              $deducciones = round($arregloDatos['rte_ivam']) + round($arregloDatos['rte_fuentem']) + round($arregloDatos['rte_icam']); //26/01/2013
              $arregloDatos['valor'] = $arregloDatos['valor'] - $deducciones;
              if($arregloDatos['valor'] > 0) {
                $arregloDatos['valor'] = round($arregloDatos['valor']);
                if(trim($obj2->naturaleza) == "D") {
                  $this->debitos = $this->debitos + $arregloDatos['valor'];
                  $arregloDatos['debito'] = $arregloDatos['valor'];
                  $arregloDatos['credito'] = 0; 
                } else {
                  $this->creditos = $this->creditos + $arregloDatos['valor'];
                  $arregloDatos['debito'] = 0;
                  $arregloDatos['credito'] = $arregloDatos['valor'];
                }
                // Aqui se Ajusta  la diferencia en el peso
                $diferencia = $this->creditos - $this->debitos;

                if($diferencia == -1) {
                  echo "$arregloDatos[numero_oficial] Creditos $this->creditos  Debitos $this->debitos Ajustando Diferencia $diferencia <br>";
                  $arregloDatos['valor'] = $arregloDatos['valor'] - 1;
                }
                if($diferencia == 1) {
                  $arregloDatos['valor'] = $arregloDatos['valor'] + 1;
                }
                $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
                $this->archivoLineaFoxconPro($arregloDatos,$unArchivo);
              }
            }
            break;
          case '2'; //IVA
            $arregloDatos['nombreservicio'] = $obj2->nombre;
            $arregloDatos['naturaleza'] = $obj2->naturaleza;
            $arregloDatos['cuenta'] = $obj2->cuenta;
            $arregloDatos['prorcentaje_iva'] = $obj2->iva;

            $arregloDatos['base_retencion'] = $unTotal->traerDetalle($arregloDatos);   // Se guarda la Base de Calculo de Iva.
            $arregloDatos['valor'] = round($arregloDatos['base_retencion'] * $obj2->iva / 100);
            
            // Cálculo de la BASE
            $arregloDatos['base'] = $arregloDatos['base_retencion'];

            if(trim($obj2->naturaleza) == "D") {
              $this->debitos = $this->debitos + $arregloDatos['valor'];
              $arregloDatos['debito'] = $arregloDatos['valor'];
              $arregloDatos['credito'] = 0;
            } else {
              $this->creditos = $this->creditos + $arregloDatos['valor'];
              $arregloDatos['debito'] = 0;
              $arregloDatos['credito'] = $arregloDatos['valor'];
            }

            if($arregloDatos['valor'] > 0) {
              $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
              $this->archivoLineaFoxconPro($arregloDatos,$unArchivo);
            }
            break;
          case '801'; //Cuentas Por Cobrar
            $total_factura = $arregloDatos['valor_anticipo'] + $arregloDatos['total_anticipos'] + $arregloDatos['total'];
            $arregloDatos['nombreservicio'] = $obj2->nombre;
            $arregloDatos['naturaleza'] = $obj2->naturaleza;
            $arregloDatos['cuenta'] = $obj2->cuenta;
            // Cálculo de la BASE
            $arregloDatos['base'] = $obj2->subtotal;
            if($arregloDatos['valor_anticipo'] + $arregloDatos['total_anticipos'] <= $total_factura) {
              $arregloDatos['valor'] = $arregloDatos['total'];
              // Agregado el 22-Feb-2020 - Sábado
              if(trim($obj2->naturaleza) == "D") {
                $arregloDatos['debito'] = $arregloDatos['valor'];
                $arregloDatos['credito'] = 0;
              } else {
                $arregloDatos['debito'] = 0;
                $arregloDatos['credito'] = $arregloDatos['valor'];
              } // Final 22-Feb-2020 - Sábado
              if($arregloDatos['valor'] > 0) {
                $this->archivoLineaFoxconPro($arregloDatos,$unArchivo);
              }
            }
            break;
          case 3:// Conceptos que son rete Fuente
            $arregloDatos['nombreservicio'] = $obj2->nombre;
            $arregloDatos['naturaleza'] = $obj2->naturaleza;
            $arregloDatos['cuenta'] = $obj2->cuenta;
            $arregloDatos['prorcentaje_rte'] = $obj2->rte_fuente;
            $arregloDatos['base_retencion'] = $unTotal->traerDetalle($arregloDatos);   // Se guarda la Base de Calculo de Iva.
            $arregloDatos['valor'] = round($arregloDatos['base_retencion'] * $obj2->rte_fuente / 100);
            // Cálculo de la BASE
            $arregloDatos['base'] = $arregloDatos['base_retencion'];
            if($arregloDatos['valor'] > 0 and $arregloDatos['rte_fuentem'] > 0) {
              if(trim($obj2->naturaleza) == "D") {
                $this->debitos = $this->debitos + $arregloDatos['valor'];
                $arregloDatos['debito'] = $arregloDatos['valor'];
                $arregloDatos['credito'] = 0;
              } else {
                $this->creditos = $this->creditos + $arregloDatos['valor'];
                $arregloDatos['debito'] = 0;
                $arregloDatos['credito'] = $arregloDatos['valor'];
              }
              $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
              $this->archivoLineaFoxconPro($arregloDatos,$unArchivo);
            }
            break;
          case 4: // Conceptos que son RETE ICA
            $arregloDatos['nombreservicio'] = $obj2->nombre;
            $arregloDatos['naturaleza'] = $obj2->naturaleza;
            $arregloDatos['cuenta'] = $obj2->cuenta;
            $arregloDatos['porcentaje_rete_ica'] = $obj2->rte_ica;
            $arregloDatos['prorcentaje_ica'] = $obj2->rte_ica;

            $arregloDatos['base_retencion'] = $unTotal->traerDetalle($arregloDatos);   // Se guarda la Base de Calculo de Iva.
            $arregloDatos['valor'] = round($arregloDatos['base_retencion'] * $obj2->rte_ica / 100);
            if($arregloDatos['valor'] > 0 and $arregloDatos['rte_icam'] > 0) { // es necesario condicionar el rte_ica del maestro para saber si aplicaba o no ICA
              // Cálculo de la BASE
              $arregloDatos['base'] = $arregloDatos['base_retencion'];
              if(trim($obj2->naturaleza) == "D") {
                $this->debitos = $this->debitos + $arregloDatos['valor'];
                $arregloDatos['debito'] = $arregloDatos['valor'];
                $arregloDatos['credito'] = 0;
              } else {
                $this->creditos = $this->creditos + $arregloDatos['valor'];
                $arregloDatos['debito'] = 0;
                $arregloDatos['credito'] = $arregloDatos['valor'];
              }
              $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
              $this->archivoLineaFoxconPro($arregloDatos,$unArchivo);
            }
            break;
          case 5: //Conceptos que son Rete Ivas
            $unReteIva = new Interfase();
            $arregloDatos['nombreservicio'] = $obj2->nombre;
            $arregloDatos['naturaleza'] = $obj2->naturaleza;
            $arregloDatos['cuenta'] = $obj2->cuenta;
            $arregloDatos['valor'] = round($arregloDatos['rte_iva']);
            $base_rete_iva = 0; $arregloDatos['valor'] = 0;
            $unReteIva->getBase($arregloDatos);
            $unReteIva = $unReteIva->db->fetch();
            $base_rete_iva = $unReteIva->iva;
            $arregloDatos['valor'] = round($unReteIva->rte_iva);
            if($base_rete_iva > 0) {
              $arregloDatos['base_retencion'] = round($base_rete_iva);
              // Cálculo de la BASE
              $arregloDatos['base'] = $arregloDatos['base_retencion'];
              if(trim($obj2->naturaleza) == "D") {
                $this->debitos = $this->debitos + $arregloDatos['valor'];
                $arregloDatos['debito'] = $arregloDatos['valor'];
                $arregloDatos['credito'] = 0;
              } else {
                $this->creditos = $this->creditos + $arregloDatos['valor'];
                $arregloDatos['debito'] = 0;
                $arregloDatos['credito'] = $arregloDatos['valor'];
              }
              $this->archivoLineaFoxconPro($arregloDatos,$unArchivo);
            }
            break;
        } //Fin Switch
      }
    }
    $this->creditos = 0;
  }

  function archivoLineaFoxconPro($arregloDatos,$unArchivo) {
    $fecha = fechaddmmaaaa($arregloDatos['fecha_factura']);

    $linea .= $fecha . "\t";
		$linea .= $arregloDatos['cuenta'] . "\t";
		$linea .= $arregloDatos['observaciones'] . "\t";
    $linea .= "FVE" . "\t";
    $linea .= $arregloDatos['numero_oficial'] . "\t";
		$linea .= $arregloDatos['debito'] . "\t";
		$linea .= $arregloDatos['credito'] . "\t";
		$linea .= $arregloDatos['nit'] . "\t";
		$linea .= $arregloDatos['digito_verificacion'] . "\t";
		$linea .= " " . "\t"; // NOMBRE1
		$linea .= " " . "\t"; // NOMBRE2
		$linea .= " " . "\t"; // APELLIDO1
		$linea .= " " . "\t"; // APELLIDO2
		$linea .= $arregloDatos['nombre_cliente'] . "\t";
		$linea .= $arregloDatos['direccion'] . "\t";
		$linea .= $arregloDatos['telefonos_fijos'] . "\t";
		$linea .= $arregloDatos['departamento'] . "\t";
		$linea .= $arregloDatos['ciudad'] . "\t";
		$linea .= fechaddmmaaaa($arregloDatos['fecha_entrada']) . "\t";
    $linea .= fechaddmmaaaa($arregloDatos['fecha_salida']) . "\t";
		$linea .= " " . "\t"; //NUMFAC = No Aplica
    $linea .= $arregloDatos['base'] . "\t";
    $linea .= $arregloDatos['porcentaje'] . "\t";
    $linea .= $arregloDatos['anulada'];

    //Sucursal
    $unArchivo->escribirContenido($linea . "\n");
  }

  // Genera Interface WorldOffice - Fredy Salom - Domingo 30/08/2020
  function getInterfaseWorldOffice($arregloDatos,$unArchivo) {
    $unPrefijo = new Interfase();
    $filas = $this->datos->db->countRows();
    while($obj=$this->datos->db->fetch()) {
      $arregloDatos['total_interfase'] = $arregloDatos['total_interfase'] + $obj->valor;
      $arregloDatos['total_interfase_f'] = number_format($arregloDatos['total_interfase'],0,',','.');

      $arregloDatos['sede'] = $obj->sede;
      $arregloDatos['nombre_cliente'] = $obj->nombre_cliente;
      $unPrefijo->obtenerPrefijo($arregloDatos);
      // Se obtiene el Prefijo de la resolución actualizada
      $unPrefijo = $unPrefijo->db->fetch();
      $arregloDatos['prefijo'] = $unPrefijo->prefijo;
      $arregloDatos['numero_oficial'] = $obj->numero_oficial;
      $arregloDatos['fecha_factura'] = fechaddmmaaaa($obj->fecha_factura);
      $arregloDatos['intermediario'] = $obj->intermediario;
      $arregloDatos['facturado_a'] = $obj->nit;
      $arregloDatos['nota'] = $obj->observaciones;
      $arregloDatos['forma_pago'] = $obj->efectivo==1 ? "Contado" : "Credito";
      $arregloDatos['verificada'] = $obj->verificada;
      $arregloDatos['anulada'] = $obj->anulada;
      $arregloDatos['cuenta'] = $obj->cuenta;
      $arregloDatos['porcentaje'] = $obj->porcentaje;
      $arregloDatos['porcentaje'] = $obj->tipo_detalle==1 ? $arregloDatos['porcentaje']/100 : $arregloDatos['porcentaje'];
      $arregloDatos['srviva'] = $obj->srviva;
      $arregloDatos['base'] = $obj->base;
      $arregloDatos['descuento'] = $obj->descuento;
      $arregloDatos['vencimiento'] = fechaddmmaaaa($obj->fecha_salida);
      $arregloDatos['valor'] = round($obj->valor);
      //Se Recalcula Total por el tema  del redondeo
      $arregloDatos['total'] = round($obj->subtotal) + round($obj->ivam) - round($obj->rte_fuentem) - round($obj->rte_ivam) - round($obj->rte_icam) - ($obj->valor_anticipo + $arregloDatos['total_anticipos']);
      /*$arregloDatos[vendedor] = $this->datos->codigo_vendedor;
      $arregloDatos[centro_costo] = $this->datos->centro_costo;

      $arregloDatos[subcentro_costo] = $this->datos->subcentro_costo;
      $arregloDatos[credito] = $this->datos->credito;*/

      if($factura_actual == 0) {
        $factura_actual = $obj->factura;
      }
      if($factura_actual <> $obj->factura) {
        $secuencia = 0;
      }
      $secuencia = $secuencia + 1;
      $arregloDatos['secuencia'] = $secuencia;
      $this->plantilla->setCurrentBlock('ROW');
      $arregloDatos['valor'] = number_format($arregloDatos['valor'],0,',','.');
      $this->mantenerDatos($arregloDatos,$this->plantilla);
      $this->plantilla->setVariable('n',$n + 1);
      $this->plantilla->setVariable('nombreservicio',$obj->nombreservicio);
      $total_valor = $total_valor + $obj->valor;
      $n = $n + 1;

      if($factura_actual <> $obj->factura) {
        $arregloDatos['factura'] = $factura_actual;
        $factura_actual = $obj->factura;
      }

      if($filas == $n) {
        $arregloDatos['factura'] = $obj->factura;
      }
      $this->plantilla->parseCurrentBlock();
    }

    $this->plantilla->setVariable('total_valor',$total_valor);
    $this->plantilla->setVariable('mensaje',$mensaje);
    $this->plantilla->setVariable('estilo',$estilo);
    $this->plantilla->setVariable('interfase',"Interface " . $arregloDatos[nombre_interfase]);
    $this->plantilla->setvariable('tipo_interfase',$arregloDatos['tipo_interfase']);
    $this->plantilla->show();
  }

  // Para interfase SIiGO
  function registrosAdicionales($arregloDatos,$unArchivo) {
    $centro_costo_aux = $arregloDatos['centro_costo'];
    $conceptoAux = new Interfase();
    $unTipo = new Interfase();  // Recorre Los Tipos de Conceptos
    $unConcepto = new Interfase();  // Recorre los conceptos de cada tipo
    $unTotal = new Interfase();  // Hace la sumatoria de cada Concepto

    $unTipo->conceptosTipos($arregloDatos); // Trae todos los tipos que hacen parte de la interfaz
    $auxNit = $arregloDatos['nit'];
    $arregloDatos['secuencia_cruce'] = '001';
    $arregloDatos['base_retencion'] = 0;

    while($obj1=$unTipo->db->fetch()) { // Se recorren todos los tipos de  servicios
      switch($_SESSION['sede']) {
        case 22:
          $arregloDatos['comprobante_cruce'] = '002';
          break;
        default:
          $arregloDatos['comprobante_cruce'] = '001';
      }
      $arregloDatos['nit_tercero'] = NULL;
      $arregloDatos['recibo'] = $arregloDatos['numero_oficial'];
      $arregloDatos['tipo_doc'] = 'F';
      $arregloDatos['nombre_servicio'] = "";
      $arregloDatos['naturaleza'] = "";
      $arregloDatos['cuenta'] = "";
      $arregloDatos['nit'] = $auxNit; //Para recuperar el Nit del tercero y solo la CXC quede con el nit del intermediario Responsable
      $arregloDatos['tipo_servicio'] = $obj1->tipo;
      $unConcepto->otrosConceptos($arregloDatos);
      while($obj2=$unConcepto->db->fetch()) {
        $arregloDatos['prorcentaje_iva'] = 0;
        $arregloDatos['prorcentaje_rte'] = 0;
        $arregloDatos['prorcentaje_ica'] = 0;
        $arregloDatos['base_retencion'] = 0;
        switch($obj2->tipo) {
          case 1: // Conceptos Internos que no son impuestos
            break;
          case 2://Iva
            $arregloDatos['nit'] = $arregloDatos['por_cuenta_de'];
            $arregloDatos['nombre_servicio'] = $obj2->nombre;
            $arregloDatos['naturaleza'] = $obj2->naturaleza;
            $arregloDatos['cuenta'] = $obj2->cuenta;
            $arregloDatos['prorcentaje_iva'] = $obj2->iva;

            $arregloDatos['base_retencion'] = $unTotal->traerDetalle($arregloDatos);   // Se guarda la Base de Calculo de Iva.
            $arregloDatos['valor'] = round($arregloDatos['base_retencion'] * $obj2->iva / 100);
            if($arregloDatos['valor'] > 0) {
              $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
              $this->archivoLinea($arregloDatos,$unArchivo);
            }
            break;
          case 9: //Cree
            $arregloDatos['nit'] = $arregloDatos['por_cuenta_de'];
            $arregloDatos['nombre_servicio'] = $obj2->nombre;
            $arregloDatos['naturaleza'] = $obj2->naturaleza;
            $arregloDatos['cuenta'] = $obj2->cuenta;
            $arregloDatos['prorcentaje_cree'] = $obj2->rte_cree;

            $arregloDatos['base_retencion'] = $unTotal->traerBaseCree($arregloDatos);   // Se guarda la Base de Calculo de Iva.
            $arregloDatos['valor'] = round($arregloDatos['base_retencion'] * $obj2->rte_cree / 100);

            if($arregloDatos['valor'] > 0) {
              $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
              $this->archivoLinea($arregloDatos,$unArchivo);
              if($arregloDatos['tipo_cliente'] == 9) {
                $arregloDatos['naturaleza'] = "C";
                $arregloDatos['cuenta'] = "1355190202";
                $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
                $this->archivoLinea($arregloDatos,$unArchivo);
              }
            }
            break;
          case 3:// Conceptos que son rete Fuente
            if($arregloDatos['intermediario'] <> '99') {
              $arregloDatos['nit'] = $arregloDatos['facturado_a']; //  correo 18-08-2009 archivo plano Sigo
            }

            $arregloDatos['nombre_servicio'] = $obj2->nombre;
            $arregloDatos['naturaleza'] = $obj2->naturaleza;
            $arregloDatos['cuenta'] = $obj2->cuenta;
            $arregloDatos['prorcentaje_rte'] = $obj2->rte_fuente;

            $arregloDatos['base_retencion'] = $unTotal->traerDetalle($arregloDatos);   // Se guarda la Base de Calculo de Iva.
            $arregloDatos['valor'] = round($arregloDatos['base_retencion'] * $obj2->rte_fuente / 100);
            if($arregloDatos['valor'] > 0) {
              $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
              $this->archivoLinea($arregloDatos,$unArchivo);
            }
            break;
          case 4: // conceptos que son RETE ICA
            $arregloDatos['nit'] = $arregloDatos['facturado_a']; //Correo 18/08/2009 asunto ARCHIVO PLANO SIIGO
            $arregloDatos['nombre_servicio'] = $obj2->nombre;
            $arregloDatos['naturaleza'] = $obj2->naturaleza;
            $arregloDatos['cuenta'] = $obj2->cuenta;
            $arregloDatos['porcentaje_rete_ica'] = $obj2->rte_ica;
            $arregloDatos['prorcentaje_ica'] = $obj2->rte_ica;

            $arregloDatos['base_retencion'] = $unTotal->traerDetalle($arregloDatos);   // Se guarda la Base de Calculo de Iva.
            $arregloDatos['valor'] = round($arregloDatos['base_retencion'] * $obj2->rte_ica / 100);
            if($arregloDatos['valor'] > 0 and $arregloDatos['rte_ica'] > 0) { // es necesario condicionar el rte_ica del maestro para saber si aplicaba o no ICA
              $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
              $this->archivoLinea($arregloDatos,$unArchivo);
            }
            break;
          case 5: //Conceptos que son Rete Ivas
            $unReteIva = new Interfase();
            if($arregloDatos['intermediario'] <> '99') {
              $arregloDatos['nit'] = $arregloDatos['facturado_a']; // correo 18-08-2009 archivo plano Sigo
            }
            $arregloDatos['nombre_servicio'] = $obj2->nombre;
            $arregloDatos['naturaleza'] = $obj2->naturaleza;
            $arregloDatos['cuenta'] = $obj2->cuenta;
            $arregloDatos['valor'] = round($arregloDatos['rte_iva']);
            $arregloDatos['prorcentaje_iva'] = NULL;
            $arregloDatos['cal_riva'] = 1;

            $unReteIva->traerDetalle($arregloDatos);
            $base_rete_iva = 0;
            while($obj3=$unReteIva->db->fetch()) {
              $base_rete_iva = $base_rete_iva + ($obj3->valor * $obj3->iva / 100); //La Base del Iva Retenido es el IVA
            }
            $arregloDatos['valor'] = round($arregloDatos['rte_iva_fac']); // sobreescribe calculo anterior no requiere RECALCULO
            $base_rete_iva = $arregloDatos['iva']; // Los toma directamente de la CABEZA DE FACTURA.
            if($arregloDatos['valor'] > 0) {
              $arregloDatos['base_retencion'] = round($base_rete_iva);
              $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
              $this->archivoLinea($arregloDatos,$unArchivo);
            }
            break;
          case 6: // Anticipos
            $total_factura = $arregloDatos['valor_anticipo'] + $arregloDatos['total_anticipos'] + $arregloDatos['total'];
            $arregloDatos['nombre_servicio'] = $obj2->nombre;
            $arregloDatos['naturaleza'] = $obj2->naturaleza;
            $arregloDatos['cuenta'] = $obj2->cuenta;
            $arregloDatos['tipo_doc'] = 'R';

            switch($_SESSION['sede']) {
              case '11': //Alcomex
              case '22': //Alcomex ZF
                $arregloDatos['comprobante_cruce'] = '003';
                $arregloDatos['recibo'] = $arregloDatos['recibo_anticipo'];
                break;
              case '15': // No está en Uso
                $arregloDatos['comprobante_cruce'] = '002';
                $arregloDatos['recibo'] = $arregloDatos['recibo_anticipo'];
                break;
              default:
                $arregloDatos['comprobante_cruce'] = '001';
                $arregloDatos['recibo'] = $arregloDatos['recibo_anticipo'];
            }

            if($arregloDatos['valor_anticipo'] + $arregloDatos['total_anticipos'] > 0) {
              $arregloDatos['valor'] = $arregloDatos['valor_anticipo'];
              $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
              $this->archivoLinea($arregloDatos,$unArchivo);
              // Aqui se crea una Línea mas por cada Anticipo Adicional FF
              $unAnticipo = new Interfase();
              $unAnticipo->retornaAnticipos($arregloDatos);
              while($res=$unAnticipo->db->fetch()) {
                $arregloDatos['valor'] = $res->valor_anticipo;
                $arregloDatos['recibo'] = $res->num_recibo;
                $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
                $this->archivoLinea($arregloDatos,$unArchivo);
              }
              // Aqui se crea la contrapartida ANTICIPO CREDITO   Cuando los anticipos son mayores al valor de la factura
              $arregloDatos['valor'] = $arregloDatos['total'] * -1;
              $arregloDatos['naturaleza'] = 'C';
              $arregloDatos['tipo_doc'] = 'F';
              // Se trae la cuenta que le asignaron de la BD
              $unCuentaTercero = new Interfase();
              $unCuentaTercero->saldo_a_favor($arregloDatos);
              $unCuentaTercero = $unCuentaTercero->db->fetch();
              $arregloDatos['cuenta'] = $unCuentaTercero->cuenta; //Julio de 2010 si es credito se cambia la cuenta
              $arregloDatos['nombre_servicio'] = $unCuentaTercero->nombre;
              switch($_SESSION['sede']) {
                case '11'://11 - Alcomex
                  $arregloDatos['comprobante_cruce'] = '001'; //antes habia un 003
                  $arregloDatos['recibo'] = $arregloDatos['recibo_anticipo'];
                  break;
                case '22'://Alcomex ZF
                  $arregloDatos['comprobante_cruce'] = '002';
                  $arregloDatos['recibo'] = $arregloDatos['recibo_anticipo'];
                  break;
                default:
                  $arregloDatos['comprobante_cruce'] = '001';
                  $arregloDatos['recibo'] = $arregloDatos['recibo_anticipo'];
              }
              $total_facturaAux = $arregloDatos['valor_anticipo'] + $arregloDatos['total_anticipos'] + $arregloDatos['total'];
              if($arregloDatos['total'] < 0) {
                $arregloDatos['recibo'] = $arregloDatos['numero_oficial']; // 24 de noviembre de 2011   si hay saldo a favor 
                $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
                $this->archivoLinea($arregloDatos,$unArchivo);
              }
            }
            break;
          case 7:// Cuentas Por Cobrar
            $arregloDatos['nit'] = $arregloDatos['facturado_a'];
            $total_factura = $arregloDatos['valor_anticipo'] + $arregloDatos['total_anticipos'] + $arregloDatos['total'];
            $arregloDatos['nombre_servicio'] = $obj2->nombre;
            $arregloDatos['naturaleza'] = $obj2->naturaleza;
            $arregloDatos['cuenta'] = $obj2->cuenta;
            $longitud_nit = strlen($arregloDatos['intermediario']); // antes estaba el [nit] 28/11/2008 
            $nitCliente = substr($arregloDatos['intermediario'], 0, $longitud_nit - 1);
            if($arregloDatos['tipo_cliente'] == 2) { //ES UNA FILIAL
              $arregloDatos['cuenta'] = $arregloDatos['cuenta_filial'];     // cuando crean una nueva filial se crea una cuenta con el consecutivo
              $arregloDatos['nombre_servicio'] = $arregloDatos['razon_social'];
            } else {
              $arregloDatos['cuenta'] = '1305050101';
            }
            if($arregloDatos['tipo_cliente'] == 9) { // Exterior
              $arregloDatos['cuenta'] = '1305100100';
            }
            if($arregloDatos['valor_anticipo'] + $arregloDatos['total_anticipos'] <= $total_factura) {
              $arregloDatos['valor'] = $arregloDatos['total'];
              if($arregloDatos['valor'] > 0) {
                $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
                $this->archivoLinea($arregloDatos,$unArchivo);
              }
            }
            break;
          case 8:// Terceros
            //NOV 17 2011  CUENTAS TERCEROS	
            // secuencia del DO
            $arregloDatos['nit'] = $arregloDatos['por_cuenta_de'];
            $arregloDatos['nombre_servicio'] = $obj2->nombre;
            $arregloDatos['naturaleza'] = $obj2->naturaleza;
            $arregloDatos['cuenta'] = $obj2->cuenta;
            $arregloDatos['prorcentaje_iva'] = $obj2->iva;
            $arregloDatos['centro_costo'] = '118';

            switch($_SESSION['sede']) {
              case '25':// Alcomex
                $arregloDatos['centro_costo'] = '060';
                break;
            }

            $unIvaT = new Interfase();
            $unIvaT->traerIvaTerceros($arregloDatos);
            $valor = 0;
            while($unIvaT=$unIvaT->db->fetch()) {
              $arregloDatos['tipo_doc'] = 'P';
              $arregloDatos['nit_tercero'] = $unIvaT->nit_tercero;
              $arregloDatos['es_del_grupo'] = $unIvaT->tipo; // se toma el campo para saber si es del grupo

              switch($_SESSION['sede']) {
                case '11'://Alcomex
                  $comprobante_cruce = '003';
                  $longitud_nit = strlen($arregloDatos['nit_tercero']);
                  $nit = substr($arregloDatos['nit_tercero'],0,$longitud_nit - 1);
                  break;
                case '25':
                  $comprobante_cruce = '002';
                  // Correo Entre compañías y terceros en la posición de secuencia del documento cruce del vencimiento (204-206), para el valor total 001 (1380250100) es, y para IVA tercero 002 (1380250200). 
                  break;
              }
              $longitud_nit = strlen($arregloDatos['nit_tercero']);
              $nit = substr($arregloDatos['nit_tercero'],0,$longitud_nit - 1);
              if($arregloDatos['es_del_grupo'] == 2) { //18 de Agosto de 2010 si es del grupo se coloca 003
                $arregloDatos['comprobante_cruce'] = '003'; //$comprobante_cruce;
              } else {
                $arregloDatos['comprobante_cruce'] = '001'; // Otros
              }

              $arregloDatos['recibo'] = $unIvaT->factura_detalle;

              if($arregloDatos['valor'] > 0) {
                if($arregloDatos['nit_tercero'] == '8001972682') { //Julio de 2010 se el el tercero DIAN  se utiliza otro documento cruce
                  $arregloDatos['comprobante_cruce'] = '008';
                }
                $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
                $arregloDatos['valor'] = $unIvaT->valor + $unIvaT->iva; // 27 de Agosto de 2009  se unifico IVA y Pago de Terceros
                echo 'test2s';
                $this->archivoLinea($arregloDatos,$unArchivo);
              }
            }
            break;
        }
        $arregloDatos['centro_costo'] = $centro_costo_aux;
      } //Por cada tipo de concepto se traen todas las cuentas que pertenezcan a éste
    }
  }

  function archivoLineaSigo($arregloDatos,$unArchivo) {
    $fecha = fechaddmmaaaa($arregloDatos['fecha_factura']);

    $linea.=$fecha . ',';
    $linea.='FV,';
    $linea.=$arregloDatos['numero_oficial'] . ',';
    $linea.=$arregloDatos['nombreservicio'] . ',';
    $linea.=$arregloDatos['nit'] . ',';
    $linea.=$arregloDatos['nombre_cliente'] . ',';
    $linea.=$arregloDatos['centro_costo'] . ',';
    $linea.=$arregloDatos['cuenta'] . ',';
    $linea.=$arregloDatos['naturaleza'] . ',';
    $linea.=$arregloDatos['valor'] . ',,';

    //Sucursal
    $unArchivo->escribirContenido($linea . "\n");
  }

  function archivoLinea($arregloDatos,$unArchivo) {
    $linea = $arregloDatos['tipo_comprobante']; //Tipo Comprobante
    $linea.=$arregloDatos['codigo_comprobante']; //Código Comprobante
    $linea.=str_pad(trim($arregloDatos['numero_oficial']),11,'0',STR_PAD_LEFT); //Número Documento
    $linea.=str_pad(trim($arregloDatos['secuencia']),5,'0',STR_PAD_LEFT); //Secuencia
    $longitud_nit = strlen($arregloDatos['nit']);
    $nit = substr($arregloDatos['nit'],0,$longitud_nit - 1);
    $linea.=str_pad(trim($nit),13,'0',STR_PAD_LEFT);              // Nit
    $linea.='000'; //Sucursal
    $linea.=str_pad(trim($arregloDatos['cuenta']),10,'0',STR_PAD_RIGHT); //Cuenta
    $linea.=str_pad('000',13,'0',STR_PAD_LEFT); //Producto
    $linea.=str_pad(trim($arregloDatos['fecha_factura']),8,'0',STR_PAD_LEFT); //Fecha Documento
    $linea.=str_pad(trim($arregloDatos['centro_costo']),4,'0',STR_PAD_LEFT); //Centro Costo

    $linea.=str_pad(trim($arregloDatos['subcentro_costo']),3,'0',STR_PAD_LEFT); // SubCentro Costo
    $linea.=str_pad(trim($arregloDatos['nombre_servicio']),50,' ',STR_PAD_RIGHT); //Descripción del Movimiento
    $linea.=str_pad(trim($arregloDatos['naturaleza']),1,'0',STR_PAD_LEFT); //Débito o Crédito
    $linea.=str_pad(trim($arregloDatos['valor']),13,'0',STR_PAD_LEFT) . '00'; //Valor del Movimiento
    $linea.=str_pad(trim($arregloDatos['base_retencion']),13,'0',STR_PAD_LEFT) . '00'; //Base de Retención

    if($arregloDatos['vendedor'] == '99') {
      $arregloDatos['vendedor'] = '01';
    }
    $linea.=str_pad(trim($arregloDatos['vendedor']),4,'0',STR_PAD_LEFT); //Código del Vendedor 
    $linea.='0000'; //Código de la Ciudad
    $linea.='000'; //Código de la Zona
    $linea.='0000'; //Código de la Bodega
    $linea.='000'; //Código de la Ubicación
    $linea.='000000000000100'; //Cantidad
    $linea.=$arregloDatos['tipo_doc']; //Tipo de Documento Cruce
    $linea.=$arregloDatos['comprobante_cruce']; //Código de Comprobante uuu

    $linea.=str_pad($arregloDatos['recibo'],11,'0',STR_PAD_LEFT); //Número de Documento Cruce
    $linea.=str_pad($arregloDatos['secuencia_cruce'],3,'0',STR_PAD_LEFT); //Secuencia del Documento Cruce												              
    $linea.=str_pad(trim($arregloDatos['fecha_pago']),8,'0',STR_PAD_LEFT); //Fecha del Vencimiento documento Cruce
    $linea.='0001'; //Forma de Pago
    $linea.='00'; //Código del Banco

    $unArchivo->escribirContenido($linea . "\n");
  }

  // Adicionales Helisa
  function conceptosCalculados($arregloDatos,$unArchivo) {
    // SE TOMAN LOS VALORES DE TODAS LAS VARIABLES DE LA ULTIMA FACTURA
    $unTotal = new Interfase();  // Hace la sumatoria de cada Concepto

    $unTipo = new Interfase();
    $unTipo->conceptosAdicionales($arregloDatos);
    $unConcepto = new Interfase();  // Recorre los conceptos de cada tipo

    while($unTipo=$unTipo->db->fetch()) {
      $arregloDatos['tipo_servicio'] = $unTipo->tipo;
      $unConcepto->otrosConceptos($arregloDatos);

      while($unConcepto=$unConcepto->db->fetch()) {
        $arregloDatos['prorcentaje_iva'] = 0;
        $arregloDatos['prorcentaje_rte'] = 0;
        $arregloDatos['prorcentaje_ica'] = 0;
        $arregloDatos['base_retencion'] = 0;

        switch ($unConcepto->tipo) {
          case '7':// Cuentas Por Cobrar
            $total_factura = $arregloDatos['valor_anticipo'] + $arregloDatos['total_anticipos'] + $arregloDatos['total'];
            $arregloDatos['nombreservicio'] = $unConcepto->nombre;
            $arregloDatos['naturaleza'] = $unConcepto->naturaleza;
            $arregloDatos['cuenta'] = $unConcepto->cuenta;
            $longitud_nit = strlen($arregloDatos['intermediario']); // antes estaba el [nit] 28/11/2008 
            $nitCliente = substr($arregloDatos['intermediario'], 0, $longitud_nit - 1);
            if($arregloDatos['tipo_cliente'] == 2) { //ES UNA FILIAL
              $arregloDatos['cuenta'] = $arregloDatos['cuenta_filial']; //cuando crean una nueva filial se crea una cuenta con el consecutivo
              $arregloDatos['nombreservicio'] = $arregloDatos['razon_social'];
            } else {
              $arregloDatos['cuenta'] = '13050501';
            }
            if($arregloDatos['tipo_cliente'] == 9) { // Exterior
              $arregloDatos['cuenta'] = '13051001';
            }

            if($arregloDatos['valor_anticipo'] + $arregloDatos['total_anticipos'] <= $total_factura) {
              $arregloDatos['valor'] = $arregloDatos['total'];
              //quitando deducciones
              //$deducciones=$arregloDatos[rte_ivam]+$arregloDatos[rte_fuentem]+$arregloDatos[rte_icam];
              $deducciones = round($arregloDatos['rte_ivam']) + round($arregloDatos['rte_fuentem']) + round($arregloDatos['rte_icam']); //26/01/2013
              $arregloDatos['valor'] = $arregloDatos['valor'] - $deducciones;
              if($arregloDatos['valor'] > 0) {
                $arregloDatos['valor'] = round($arregloDatos['valor']);
                if(trim($unConcepto->naturaleza) == "D") {
                  $this->debitos = $this->debitos + $arregloDatos['valor'];
                } else {
                  $this->creditos = $this->creditos + $arregloDatos['valor'];
                }
                // Aqui se Ajusta  la diferencia en el peso
                $diferencia = $this->creditos - $this->debitos;

                if($diferencia == -1) {
                  echo "$arregloDatos[numero_oficial] Cr&eacute;ditos $this->creditos  D&eacute;bitos $this->debitos Ajustando Diferencia $diferencia <br>";
                  $arregloDatos['valor'] = $arregloDatos['valor'] - 1;
                }
                if($diferencia == 1) {
                  $arregloDatos['valor'] = $arregloDatos['valor'] + 1;
                }
                $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
                $this->archivoLineaSigo($arregloDatos,$unArchivo);
              }
            }
            break;
          case '2'; //IVA
            $arregloDatos['nombreservicio'] = $unConcepto->nombre;
            $arregloDatos['naturaleza'] = $unConcepto->naturaleza;
            $arregloDatos['cuenta'] = $unConcepto->cuenta;
            $arregloDatos['prorcentaje_iva'] = $unConcepto->iva;

            $arregloDatos['base_retencion'] = $unTotal->traerDetalle($arregloDatos);   // Se guarda la Base de Calculo de Iva.
            $arregloDatos['valor'] = round($arregloDatos['base_retencion'] * $unConcepto->iva / 100);

            if(trim($unConcepto->naturaleza) == "D") {
              $this->debitos = $this->debitos + $arregloDatos['valor'];
            } else {
              $this->creditos = $this->creditos + $arregloDatos['valor'];
            }

            if($arregloDatos['valor'] > 0) {
              $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
              $this->archivoLineaSigo($arregloDatos,$unArchivo);
            }
            break;
          case '801'; //Cuentas Por Cobrar
            $total_factura = $arregloDatos['valor_anticipo'] + $arregloDatos['total_anticipos'] + $arregloDatos['total'];
            $arregloDatos['nombreservicio'] = $unConcepto->nombre;
            $arregloDatos['naturaleza'] = $unConcepto->naturaleza;
            $arregloDatos['cuenta'] = $unConcepto->cuenta;
            if($arregloDatos['valor_anticipo'] + $arregloDatos['total_anticipos'] <= $total_factura) {
              $arregloDatos['valor'] = $arregloDatos['total'];
              if($arregloDatos['valor'] > 0) {
                $this->archivoLineaSigo($arregloDatos,$unArchivo);
              }
            }
            break;
          case 3:// Conceptos que son rete Fuente
            $arregloDatos['nombreservicio'] = $unConcepto->nombre;
            $arregloDatos['naturaleza'] = $unConcepto->naturaleza;
            $arregloDatos['cuenta'] = $unConcepto->cuenta;
            $arregloDatos['prorcentaje_rte'] = $unConcepto->rte_fuente;
            $arregloDatos['base_retencion'] = $unTotal->traerDetalle($arregloDatos);   // Se guarda la Base de Calculo de Iva.
            $arregloDatos['valor'] = round($arregloDatos['base_retencion'] * $unConcepto->rte_fuente / 100);
            if($arregloDatos['valor'] > 0 and $arregloDatos['rte_fuentem'] > 0) {
              if(trim($unConcepto->naturaleza) == "D") {
                $this->debitos = $this->debitos + $arregloDatos['valor'];
              } else {
                $this->creditos = $this->creditos + $arregloDatos['valor'];
              }
              $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
              $this->archivoLineaSigo($arregloDatos,$unArchivo);
            }
            break;
          case 4: // Conceptos que son RETE ICA
            $arregloDatos['nombreservicio'] = $unConcepto->nombre;
            $arregloDatos['naturaleza'] = $unConcepto->naturaleza;
            $arregloDatos['cuenta'] = $unConcepto->cuenta;
            $arregloDatos['porcentaje_rete_ica'] = $unConcepto->rte_ica;
            $arregloDatos['prorcentaje_ica'] = $unConcepto->rte_ica;

            $arregloDatos['base_retencion'] = $unTotal->traerDetalle($arregloDatos);   // Se guarda la Base de Calculo de Iva.
            $arregloDatos['valor'] = round($arregloDatos['base_retencion'] * $unConcepto->rte_ica / 100);
            if($arregloDatos['valor'] > 0 and $arregloDatos['rte_icam'] > 0) { // es necesario condicionar el rte_ica del maestro para saber si aplicaba o no ICA
              if(trim($unConcepto->naturaleza) == "D") {
                $this->debitos = $this->debitos + $arregloDatos['valor'];
              } else {
                $this->creditos = $this->creditos + $arregloDatos['valor'];
              }
              $arregloDatos['secuencia'] = $arregloDatos['secuencia'] + 1;
              $this->archivoLineaSigo($arregloDatos,$unArchivo);
            }
            break;
          case 5: //Conceptos que son Rete Ivas
            $unReteIva = new Interfase();
            $arregloDatos['nombreservicio'] = $unConcepto->nombre;
            $arregloDatos['naturaleza'] = $unConcepto->naturaleza;
            $arregloDatos['cuenta'] = $unConcepto->cuenta;
            $arregloDatos['valor'] = round($arregloDatos['rte_iva']);
            $base_rete_iva = 0; $arregloDatos['valor'] = 0;
            $unReteIva->getBase($arregloDatos);
            $unReteIva = $unReteIva->db->fetch();
            $base_rete_iva = $unReteIva->iva;
            $arregloDatos['valor'] = round($unReteIva->rte_iva);
            if($base_rete_iva > 0) {
              $arregloDatos['base_retencion'] = round($base_rete_iva);
              if(trim($unConcepto->naturaleza) == "D") {
                $this->debitos = $this->debitos + $arregloDatos['valor'];
              } else {
                $this->creditos = $this->creditos + $arregloDatos['valor'];
              }
              $this->archivoLineaSigo($arregloDatos,$unArchivo);
            }
            break;
        } //Fin Switch
      }
    }
    $this->creditos = 0;
  }
}
?>