<?php
/**
 * Description of acondicionaVista
 *
 * @author  Fredy Salom <fsalom@bysoft.us>
 * @date    10-Octubre-2017
 */

require_once COMPONENTS_PATH . 'Entidades/Clientes.php';
require_once COMPONENTS_PATH . 'Entidades/Referencias.php';

class acondicionaVista {
  var $template;
  var $datos;
  
  function acondicionaVista() {
    $this->template = new HTML_Template_IT();
    $this->datos = new acondicionaDatos();
  }
  
  function filtroClientes() {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'acondicionamientos/views/tmpl/filtroClientes.php' );
    $this->template->setVariable('COMODIN', '' );
    $this->template->show();
  }
  
	function filtroEtiquetar($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'acondicionamientos/views/tmpl/filtroEtiquetar.php' );
    $this->template->setVariable('COMODIN', '');
    $this->template->show();
	}

  function listadoEtiquetar($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'acondicionamientos/views/tmpl/listadoEtiquetar.php' );
    $this->template->setVariable('COMODIN', '');
    $this->template->setVariable('paginacion', $arreglo['datos']['paginacion']);
    $this->template->setVariable('pagina', $arreglo['pagina']);
    $this->template->setVariable('verAlerta', 'none');
	
    $this->template->setVariable('orden', $arreglo['orden']);
    $this->template->setVariable('id_orden', $arreglo['id_orden']);
    $this->template->setVariable('campoBuscar', $arreglo['buscar']);
        	
    if(isset($arreglo['alerta_accion'])) {
      $this->template->setVariable('alerta_accion', $arreglo['alerta_accion']);
      $this->template->setVariable('verAlerta', 'block');
    }

    //Configura datos del filtro
    $this->template->setVariable('niteac', $arreglo['niteac']);
    $this->template->setVariable('fechadesdeeac', $arreglo['fechadesdeeac']);
    $this->template->setVariable('fechahastaeac', $arreglo['fechahastaeac']);
    $this->template->setVariable('nalista', $arreglo['nalista']);
    //+-----------------------------------------------------------------------------------------------------------+
    $numRegistro = count($arreglo['datos']['datos']);
    if($numRegistro == 0) {
      $this->template->setVariable(mensaje, "&nbsp;No hay Acondicionamientos para Etiquetar");
      $this->template->setVariable(estilo, "ui-state-error");
    } else {
      $codbagcolor = 1; $n = $arreglo['pagina'] == '1' ? 0 : (((int) $arreglo['pagina'] - 1) * 10);
      foreach($arreglo['datos']['datos'] as $value) {
        $this->template->setCurrentBlock("ROW");
        if($codbagcolor == 1) {
          $this->template->setVariable('id_tr_estilo','tr_blanco');
          $codbagcolor = 2;
        } else {
          $this->template->setVariable('id_tr_estilo','tr_gris_cla');	
          $codbagcolor = 1;
        }
        $n++;
        $this->template->setVariable('n', $n);
        $this->template->setVariable('acondicionamiento', $value['acondicionamiento']);
        $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
        $fecha = date_create($value['fecha']);
        $this->template->setVariable('fecha',date_format($fecha,'Y-m-d H:i'));
        $this->template->setVariable('pedido', $value['pedido']);
        $this->template->setVariable('fmm', $value['fmm']);
        $this->template->parseCurrentBlock("ROW");
      }
    }
    
    $this->template->show();
  }
  
  function mostrarListadoReferencias($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'acondicionamientos/views/tmpl/listadoReferencias.php' );
    $this->template->setVariable('COMODIN', '' );
    $this->template->setVariable('tipo_mercancia', $arreglo['tipo_mercancia']);
    if($arreglo['tipo_mercancia']==1) {
      $this->template->setVariable('nombre_tipo_mercancia', 'NACIONAL');
    } else if($arreglo['tipo_mercancia']==2) {
      $this->template->setVariable('nombre_tipo_mercancia', 'EXTRANJERA');
    } else if($arreglo['tipo_mercancia']==3) {
      $this->template->setVariable('nombre_tipo_mercancia', 'MIXTA');
    }
    
    $cliente = new Clientes();
    $datosCliente = $cliente->recover($arreglo['docCliente'],'numero_documento');
    $this->template->setVariable('verAlerta' , 'none');
    $this->template->setVariable('documento_cliente' , isset($datosCliente->numero_documento)?$datosCliente->numero_documento:'');
    $this->template->setVariable('nombre_cliente' , isset($datosCliente->razon_social)?$datosCliente->razon_social:'');
    
    $referencias = new Referencias();
    $refCliente = $referencias->get_listed(" ( cliente = ".$arreglo['docCliente']." OR cliente = 99 ) AND tipo<>35  ");
  
    foreach($refCliente as $valure) {
      $disponibles = $this->datos->disponiblesProducto($valure['codigo'],$arreglo['docCliente']);
      
      if(isset($disponibles->cantidad_naci)) {
        if($arreglo['tipo_mercancia']==1) {
          if($disponibles->cantidad_naci>0) {
            $this->template->setCurrentBlock("ROW");
            $this->template->setVariable('codigo_ref', $valure['codigo_ref']);
            $this->template->setVariable('nombre_ref', $valure['nombre']);
            $this->template->setVariable('codigo', $valure['codigo']);
            $this->template->parseCurrentBlock("ROW");
          }
        } else if($arreglo['tipo_mercancia']==2) {
          if($disponibles->cantidad_nonac>0) {
            $this->template->setCurrentBlock("ROW");
            $this->template->setVariable('codigo_ref', $valure['codigo_ref']);
            $this->template->setVariable('nombre_ref', $valure['nombre']);
            $this->template->setVariable('codigo', $valure['codigo']);
            $this->template->parseCurrentBlock("ROW");
          }
        } else if($arreglo['tipo_mercancia']==3) {
          if($disponibles->cantidad_mixto>0) {
            $this->template->setCurrentBlock("ROW");
            $this->template->setVariable('codigo_ref', $valure['codigo_ref']);
            $this->template->setVariable('nombre_ref', $valure['nombre']);
            $this->template->setVariable('codigo', $valure['codigo']);
            $this->template->parseCurrentBlock("ROW");
          }
        }
      }
    }
    
    $this->template->show();
  }
  
  function acondicionarReferencias($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'acondicionamientos/views/tmpl/formAcondicionamiento.php' );
    $this->template->setVariable('COMODIN', '' );
    
    $cliente = new Clientes();
    $datosCliente = $cliente->recover($arreglo['docCliente'],'numero_documento');
    $this->template->setVariable('documento_cliente' , isset($datosCliente->numero_documento)?$datosCliente->numero_documento:'');
    $this->template->setVariable('nombre_cliente' , isset($datosCliente->razon_social)?$datosCliente->razon_social:'');
    
    $this->template->setVariable('tipo_mercancia' , $arreglo['tipo_mercancia']);
    if($arreglo['tipo_mercancia']==1) {
      $this->template->setVariable('nombre_tipo_mercancia' , 'NACIONAL');
    } else if($arreglo['tipo_mercancia']==2) {
      $this->template->setVariable('nombre_tipo_mercancia' , 'EXTRANJERA');
    } else if($arreglo['tipo_mercancia']==3) {
      $this->template->setVariable('nombre_tipo_mercancia' , 'MIXTA');
    }

    //Captura automática de fecha y hora 
    $fecha = new DateTime();
    $fecha = $fecha->format('Y-m-d H:i');
    $this->template->setVariable('fecha',$fecha);
    
    $value = $arreglo['seleccion'][0]; //Capturo Primera Selección
    $referencias = new Referencias();
    $datosReferencia = $referencias->recover($value, 'codigo'); //Recuperación de un Registro
    // Información de la Primera Referencia Seleccionada
    $this->template->setVariable('cod_referencia', isset($datosReferencia->codigo)?$datosReferencia->codigo:'');
    $this->template->setVariable('codigo_referencia', isset($datosReferencia->codigo_ref)?$datosReferencia->codigo_ref:'');
    $this->template->setVariable('nombre_referencia', isset($datosReferencia->nombre)?$datosReferencia->nombre:'');
    $disponibles = $this->datos->disponiblesProducto($value,$arreglo['docCliente']);
    $this->template->setVariable('doc_tte', isset($disponibles->doc_tte)?$disponibles->doc_tte:'');
    if($arreglo['tipo_mercancia']==1) {
      $this->template->setVariable('disponible', isset($disponibles->cantidad_naci)?number_format($disponibles->cantidad_naci,2,".",""):0);
    } else if($arreglo['tipo_mercancia']==2) {
      $this->template->setVariable('disponible', isset($disponibles->cantidad_nonac)?number_format($disponibles->cantidad_nonac,2,".",""):0);
    } else if($arreglo['tipo_mercancia']==3) {
      $this->template->setVariable('disponible', isset($disponibles->cantidad_mixto)?number_format($disponibles->cantidad_mixto,2,".",""):0);
    }
    
    $this->template->show();
  }
  
  function mostrarAcondicionamiento($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'acondicionamientos/views/tmpl/detalleAcondicionamiento.php' );
    $this->template->setVariable('COMODIN', '' );
    
    $datosMaestro = $this->datos->retornarMaestroAcondicionamiento($arreglo['id_registro']);
    
    $this->template->setVariable('mostrar_botones', 'block');
    $this->template->setVariable('mostrar_mensaje', 'none');
    $this->template->setVariable('n',1);
    if($datosMaestro->cierre==1) {
      $this->template->setVariable('mostrar_botones', 'none');
      $this->template->setVariable('mostrar_mensaje', 'block');
      $this->template->setVariable('n',2);
    }

    $this->template->setVariable('tipo_mercancia', $arreglo['tipo_mercancia']);
    $this->template->setVariable('select_tiporechazo', $arreglo['select_tiporechazo']);
        
    $this->template->setVariable('codigo_operacion', $datosMaestro->codigo);
    $this->template->setVariable('numero_documento', $datosMaestro->numero_documento);    
    $this->template->setVariable('razon_social', $datosMaestro->razon_social);
    $fecha = date_create($datosMaestro->fecha);
    $this->template->setVariable('fecha', date_format($fecha,'Y-m-d H:i'));
    $this->template->setVariable('destinatario', $datosMaestro->destinatario);
    $this->template->setVariable('ciudad', $datosMaestro->ciudad);
    $this->template->setVariable('direccion', $datosMaestro->direccion);
    $this->template->setVariable('conductor_nombre', $datosMaestro->conductor_nombre);
    $this->template->setVariable('conductor_identificacion', $datosMaestro->conductor_identificacion);
    $this->template->setVariable('placa', $datosMaestro->placa);
    $this->template->setVariable('fmm', $datosMaestro->fmm);
    $this->template->setVariable('reginvima', $datosMaestro->parte_numero);
    $this->template->setVariable('producto', $datosMaestro->nombre_referencia);
    $this->template->setVariable('orden', $datosMaestro->orden);
    $this->template->setVariable('doc_tte', $datosMaestro->doc_tte);
    $this->template->setVariable('pedido', $datosMaestro->pedido);
    $this->template->setVariable('cantidad', number_format($datosMaestro->cantidad,2,".",","));
    $this->template->setVariable('cantidad_nac', number_format($datosMaestro->cantidad_nac,2,".",","));
    $this->template->setVariable('cantidad_ext', number_format($datosMaestro->cantidad_ext,2,".",","));
    $this->template->setVariable('observaciones', $datosMaestro->obs);
    
    $detalleAcondicionamiento = $this->datos->retornarDetalleAcondicionamiento($arreglo['id_registro']);

    foreach($detalleAcondicionamiento as $valueDetalle) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('orden_detalle', $valueDetalle['orden']);
      $this->template->setVariable('arribo', $valueDetalle['arribo']);
      $this->template->setVariable('inv_entrada', $valueDetalle['inventario_entrada']);
      $this->template->setVariable('codigo_referen', $valueDetalle['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $valueDetalle['nombre_referencia']);
      $this->template->setVariable('fecha_expira', $valueDetalle['fecha_expira']);
      $this->template->setVariable('fecha_detalle', $valueDetalle['fecha']);
      $this->template->setVariable('modelo', !empty($valueDetalle['modelo'])?$valueDetalle['modelo']:'POR ASIGNAR');
      $this->template->setVariable('nombre_ubicacion', isset($valueDetalle['nombre_ubicacion'])?$valueDetalle['nombre_ubicacion']:'POR ASIGNAR');
      switch($valueDetalle['nombre_mcia']) {
        case 'NORMAL': {
          $this->template->setVariable('nombre_mcia', 'ACONDICIONADAS');
          break;
        }
        case 'DEVUELTAS': {
          $this->template->setVariable('nombre_mcia', 'DEVUELTAS');
          break;
        }
        default: $this->template->setVariable('nombre_mcia', $valueDetalle['nombre_mcia']);
      }
      $this->template->setVariable('cantidad_nacional', number_format(abs($valueDetalle['cantidad_naci']),2,".",","));
      $this->template->setVariable('peso_nacional', number_format(abs($valueDetalle['peso_naci']),2,".",","));
      $this->template->setVariable('valor_cif', number_format(abs($valueDetalle['cif']),2,".",","));
      $this->template->setVariable('cantidad_extranjera', number_format(abs($valueDetalle['cantidad_nonac']),2,".",","));
      $this->template->setVariable('peso_extranjera', number_format(abs($valueDetalle['peso_nonac']),2,".",","));
      $this->template->setVariable('valor_fob', number_format(abs($valueDetalle['fob_nonac']),2,".",","));
      $this->template->parseCurrentBlock("ROW");
    }
    
    $this->template->show();
  }

  function mostrarPackingList($idRegistro) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'acondicionamientos/views/tmpl/detallePackingList.php' );
    $this->template->setVariable('COMODIN', '' );
    
    $datosMaestro = $this->datos->retornarMaestroAcondicionamiento($idRegistro);
    
    $this->template->setVariable('mostrar_botones', 'block');
    $this->template->setVariable('mostrar_mensaje', 'none');
    if($datosMaestro->cierre==1) {
      $this->template->setVariable('mostrar_botones', 'none');
      $this->template->setVariable('mostrar_mensaje', 'block');
    }

    $this->template->setVariable('tipo_mercancia', $arreglo['tipo_mercancia']);
    $this->template->setVariable('select_tiporechazo', $arreglo['select_tiporechazo']);

    $this->template->setVariable('codigo_operacion', $datosMaestro->codigo);
    $this->template->setVariable('razon_social', $datosMaestro->razon_social);
    $this->template->setVariable('numero_documento', $datosMaestro->numero_documento);
    $fecha = date_create($datosMaestro->fecha);
    $this->template->setVariable('fecha', date_format($fecha,'Y-m-d H:i'));
    $this->template->setVariable('destinatario', $datosMaestro->destinatario);
    $this->template->setVariable('ciudad', $datosMaestro->ciudad);
    $this->template->setVariable('direccion', $datosMaestro->direccion);
    $this->template->setVariable('conductor_nombre', $datosMaestro->conductor_nombre);
    $this->template->setVariable('conductor_identificacion', $datosMaestro->conductor_identificacion);
    $this->template->setVariable('placa', $datosMaestro->placa);
    $this->template->setVariable('fmm', $datosMaestro->fmm);
    $this->template->setVariable('reginvima', $datosMaestro->parte_numero);
    $this->template->setVariable('codigo_ref', $datosMaestro->codigo_ref);
    $this->template->setVariable('producto', $datosMaestro->nombre_referencia);
    $this->template->setVariable('peso', number_format(abs($datosMaestro->peso),2,".",","));    
    $this->template->setVariable('orden', $datosMaestro->orden);
    $this->template->setVariable('doc_tte', $datosMaestro->doc_tte);
    $this->template->setVariable('pedido', $datosMaestro->pedido);
    $this->template->setVariable('cantidad', number_format($datosMaestro->cantidad,2,".",","));
    $this->template->setVariable('cantidad_nac', number_format($datosMaestro->cantidad_nac,2,".",","));
    $this->template->setVariable('cantidad_ext', number_format($datosMaestro->cantidad_ext,2,".",","));
    $this->template->setVariable('observaciones', $datosMaestro->obs);
    
    $detalleAlistamiento = $this->datos->retornarDetalleAcondicionamiento($idRegistro);
    
    //Inicializa las variables que registran los totales
    $tot_piezas_naci = $tot_peso_naci = $tot_valor_cif = 0;
    $tot_piezas_ext = $tot_peso_ext = $tot_valor_fob = 0;

    foreach($detalleAlistamiento as $valueDetalle) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('orden_detalle', $valueDetalle['orden']);
      $this->template->setVariable('arribo', $valueDetalle['arribo']);
      $this->template->setVariable('codigo_referen', $valueDetalle['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $valueDetalle['nombre_referencia']);
      $this->template->setVariable('fecha_expira', $valueDetalle['fecha_expira']);
      $this->template->setVariable('fecha_detalle', $valueDetalle['fecha']);    
      $this->template->setVariable('modelo', !empty($valueDetalle['modelo'])?$valueDetalle['modelo']:'POR ASIGNAR');
      $this->template->setVariable('nombre_ubicacion', isset($valueDetalle['nombre_ubicacion'])?$valueDetalle['nombre_ubicacion']:'POR ASIGNAR');
      switch($valueDetalle['nombre_mcia']) {
        case 'NORMAL': {
          $this->template->setVariable('nombre_mcia', 'ACONDICIONADAS');
          break;
        }
        case 'DEVUELTAS': {
          $this->template->setVariable('nombre_mcia', 'DEVUELTAS');
          break;
        }
        default: $this->template->setVariable('nombre_mcia', 'RECHAZADAS');
      }
      $this->template->setVariable('cantidad_nacional', number_format(abs($valueDetalle['cantidad_naci']),2,".",","));
      $this->template->setVariable('negrita_nac', abs($valueDetalle['cantidad_naci'])!=0?'bold':'normal');
      $this->template->setVariable('peso_nacional', number_format(abs($valueDetalle['peso_naci']),2,".",","));
      $this->template->setVariable('valor_cif', number_format(abs($valueDetalle['cif']),2,".",","));
      $this->template->setVariable('cantidad_extranjera', number_format(abs($valueDetalle['cantidad_nonac']),2,".",","));
      $this->template->setVariable('negrita_ext', abs($valueDetalle['cantidad_nonac'])!=0?'bold':'normal');
      $this->template->setVariable('peso_extranjera', number_format(abs($valueDetalle['peso_nonac']),2,".",","));
      $this->template->setVariable('valor_fob', number_format(abs($valueDetalle['fob_nonac']),2,".",","));
      /*************************************************************************************************/
      // Registro de Totales
      $tot_piezas_naci += abs($valueDetalle['cantidad_naci']); $tot_piezas_ext += abs($valueDetalle['cantidad_nonac']);
      $tot_peso_naci += abs($valueDetalle['peso_naci']); $tot_peso_ext += abs($valueDetalle['peso_nonac']);
      $tot_valor_cif += abs($valueDetalle['cif']); $tot_valor_fob += abs($valueDetalle['fob_nonac']);
      /*************************************************************************************************/
      $this->template->parseCurrentBlock("ROW");
    }
    // Captura los totales registrados
    $this->template->setVariable('tot_piezas_naci', number_format($tot_piezas_naci,2,".",","));
    $this->template->setVariable('tot_peso_naci', number_format($tot_peso_naci,2,".",","));
    $this->template->setVariable('tot_valor_cif', number_format($tot_valor_cif,2,".",","));
    $this->template->setVariable('tot_piezas_ext', number_format($tot_piezas_ext,2,".",","));
    $this->template->setVariable('tot_peso_ext', number_format($tot_peso_ext,2,".",","));
    $this->template->setVariable('tot_valor_fob', number_format($tot_valor_fob,2,".",","));
    
    $this->template->show();
  }
  
  function mostrarDetalleAcondicionamiento($idRegistro) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'acondicionamientos/views/tmpl/mostrarDetalleAcondicionamiento.php' );
    $this->template->setVariable('COMODIN', '' );
    
    $datosMaestro = $this->datos->retornarMaestroAlistamiento($idRegistro);
    $detalleAlistamiento = $this->datos->retornarDetalleAlistamiento($idRegistro);
    $this->template->setVariable('codigo_operacion', $datosMaestro->codigo);
    $this->template->setVariable('pedido', $datosMaestro->pedido);
    
    foreach($detalleAlistamiento as $valueDetalle) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('orden_detalle', $valueDetalle['orden']);
      $this->template->setVariable('arribo', $valueDetalle['arribo']);
      $this->template->setVariable('nombre_referencia', $valueDetalle['nombre_referencia']);
      $this->template->setVariable('nombre_ubicacion', $valueDetalle['nombre_ubicacion']);      
      $this->template->setVariable('fecha_detalle', $valueDetalle['fecha']);
      $this->template->setVariable('cantidad_nacional', number_format(abs($valueDetalle['cantidad_naci']),2,".",","));
      $this->template->setVariable('peso_nacional', number_format(abs($valueDetalle['peso_naci']),2,".",","));
      $this->template->setVariable('valor_cif', number_format(abs($valueDetalle['cif']),2,".",","));
      $this->template->setVariable('cantidad_extranjera', number_format(abs($valueDetalle['cantidad_nonac']),2,".",","));
      $this->template->setVariable('peso_extranjera', number_format(abs($valueDetalle['peso_nonac']),2,".",","));
      $this->template->setVariable('valor_fob', number_format(abs($valueDetalle['fob_nonac']),2,".",","));
      $this->template->parseCurrentBlock("ROW");
    }
    
    $this->template->show();
  }
  
  function mostrarEtiquetarAcondicionamiento($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'acondicionamientos/views/tmpl/detalleEtiquetarAcondicionamiento.php' );
    $this->template->setVariable('COMODIN', '' );

    //Captura fecha de etiqueta y sede
    $this->template->setVariable('fecha', date('Y-m-d H:i'));
    $this->template->setVariable('nombre_sede', $_SESSION['nombre_sede']);    
    $datosMaestro = $this->datos->retornarMaestroAcondicionamiento($arreglo['id_registro']);
    
    $this->template->setVariable('mostrar_botones', 'block');
    $this->template->setVariable('mostrar_mensaje', 'none');
    if($datosMaestro->cierre==1) {
      $this->template->setVariable('mostrar_botones', 'none');
      $this->template->setVariable('mostrar_mensaje', 'block');
    }

    $this->template->setVariable('tipo_mercancia', $arreglo['tipo_mercancia']);
        
    $this->template->setVariable('razon_social', $datosMaestro->razon_social);
    $this->template->setVariable('numero_documento', $datosMaestro->numero_documento);
    $this->template->setVariable('codigo_operacion', $datosMaestro->codigo);
    $this->template->setVariable('destinatario', $datosMaestro->destinatario);
    $this->template->setVariable('ciudad', $datosMaestro->ciudad);
    $this->template->setVariable('direccion', $datosMaestro->direccion);
    $this->template->setVariable('conductor_nombre', $datosMaestro->conductor_nombre);
    $this->template->setVariable('conductor_identificacion', $datosMaestro->conductor_identificacion);
    $this->template->setVariable('placa', $datosMaestro->placa);
    $this->template->setVariable('empresa', $datosMaestro->empresa);
    $this->template->setVariable('cantidad', number_format($datosMaestro->cantidad,2,".",","));
    $this->template->setVariable('cantidad_nac', number_format($datosMaestro->cantidad_nac,2,".",","));
    $this->template->setVariable('cantidad_ext', number_format($datosMaestro->cantidad_ext,2,".",","));
    
    $detalleAlistamiento = $this->datos->retornarDetalleAcondicionamiento($arreglo['id_registro']);
    foreach($detalleAlistamiento as $valueDetalle) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('codigo_referen', $valueDetalle['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $valueDetalle['nombre_referencia']);
      $this->template->setVariable('nombre_ubicacion', isset($valueDetalle['nombre_ubicacion'])?$valueDetalle['nombre_ubicacion']:'POR ASIGNAR');
      $this->template->setVariable('modelo', !empty($valueDetalle['modelo'])?$valueDetalle['modelo']:'POR ASIGNAR');
      $this->template->setVariable('cantidad_nacional', number_format(abs($valueDetalle['cantidad_naci']),2,".",","));
      $this->template->setVariable('cantidad_extranjera', number_format(abs($valueDetalle['cantidad_nonac']),2,".",","));
      $this->template->parseCurrentBlock("ROW");
    }
    
    $this->template->show();
  }
  
  function mostrarEtiqueta($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'acondicionamientos/views/tmpl/detalleEtiqueta.php' );
    $this->template->setVariable('COMODIN', '' );

    //Captura fecha de etiqueta y sede
    $this->template->setVariable('fecha', date('Y-m-d H:i'));
    $this->template->setVariable('nombre_sede', $_SESSION['nombre_sede']);  
    $datosMaestro = $this->datos->retornarMaestroAlistamiento($arreglo['id_registro']);
    
    $this->template->setVariable('mostrar_botones', 'block');
    $this->template->setVariable('mostrar_mensaje', 'none');
    if($datosMaestro->cierre==1) {
      $this->template->setVariable('mostrar_botones', 'none');
      $this->template->setVariable('mostrar_mensaje', 'block');
    }

    $this->template->setVariable('tipo_mercancia', $arreglo['tipo_mercancia']);
        
    $this->template->setVariable('razon_social', $datosMaestro->razon_social);
    $this->template->setVariable('numero_documento', $datosMaestro->numero_documento);
    $this->template->setVariable('codigo_operacion', $datosMaestro->codigo);
    $this->template->setVariable('destinatario', $datosMaestro->destinatario);
    $this->template->setVariable('ciudad', $datosMaestro->ciudad);
    $this->template->setVariable('direccion', $datosMaestro->direccion);
    $this->template->setVariable('conductor_nombre', $datosMaestro->conductor_nombre);
    $this->template->setVariable('conductor_identificacion', $datosMaestro->conductor_identificacion);
    $this->template->setVariable('placa', $datosMaestro->placa);
    $this->template->setVariable('empresa', $datosMaestro->empresa);
    $this->template->setVariable('cantidad', number_format($datosMaestro->cantidad,2,".",","));
    $this->template->setVariable('cantidad_nac', number_format($datosMaestro->cantidad_nac,2,".",","));
    $this->template->setVariable('cantidad_ext', number_format($datosMaestro->cantidad_ext,2,".",","));
    
    $detalleAlistamiento = $this->datos->retornarDetalleAlistamiento($arreglo['id_registro']);
    $n = 0;
    foreach($detalleAlistamiento as $valueDetalle) {
      $this->template->setCurrentBlock("ROW");
      $n++;
      $buscar = (string) $n;
      $resultado = strpos($arreglo['codigos'],$buscar);
      if($resultado !== FALSE) {
        $this->template->setVariable('codigo_referen', $valueDetalle['codigo_ref']);
        $this->template->setVariable('nombre_referencia', $valueDetalle['nombre_referencia']);
        $this->template->setVariable('nombre_ubicacion', isset($valueDetalle['nombre_ubicacion'])?$valueDetalle['nombre_ubicacion']:'POR ASIGNAR');
        $this->template->setVariable('modelo', !empty($valueDetalle['modelo'])?$valueDetalle['modelo']:'POR ASIGNAR');
        $this->template->setVariable('cantidad_nacional', number_format(abs($valueDetalle['cantidad_naci']),2,".",","));
        $this->template->setVariable('cantidad_extranjera', number_format(abs($valueDetalle['cantidad_nonac']),2,".",","));
        /*************************************************************************************************/
        // Registro de Totales
        $tot_piezas_naci += abs($valueDetalle['cantidad_naci']); $tot_piezas_ext += abs($valueDetalle['cantidad_nonac']);
        /*************************************************************************************************/
        $this->template->parseCurrentBlock("ROW");
      }
    }
    // Captura los totales registrados
    $this->template->setVariable('tot_piezas_naci', number_format($tot_piezas_naci,2,".",","));
    $this->template->setVariable('tot_piezas_ext', number_format($tot_piezas_ext,2,".",","));
    
    $this->template->show();
  }
}
?>