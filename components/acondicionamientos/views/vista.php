<?php
/**
 * Description of acondicionaVista
 *
 * @author  Fredy Salom <fsalom@bysoft.us>
 * @date    24-Febrero-2018
 */
require_once COMPONENTS_PATH.'Entidades/Clientes.php';
require_once COMPONENTS_PATH.'Entidades/Camiones.php';
require_once COMPONENTS_PATH.'Entidades/Referencias.php';

require_once(COMPONENTS_PATH.'acondicionamientos/model/acondicionamientos.php');

class acondicionaVista {
  var $template;
  var $datos;
  
  function acondicionaVista() {
    $this->template = new HTML_Template_IT(COMPONENTS_PATH);
    $this->datos = new acondicionaDatos();
  }
  
  function filtroCliente() {
    $this->template->loadTemplateFile('acondicionamientos/views/tmpl/filtroCliente.php');
    $this->template->setVariable('COMODIN', '' );
    $this->template->show();
  }
  
	function filtroRechazadas($arreglo) {
    $this->template->loadTemplateFile('acondicionamientos/views/tmpl/filtroRechazadas.php');
    $this->template->setVariable('COMODIN', '');
    $this->template->setVariable('select_tiporechazo', $arreglo['select_tiporechazo']);
    $this->template->show();
	}

  function listadoRechazadas($arreglo) {
    $this->template->loadTemplateFile('acondicionamientos/views/tmpl/listadoRechazadas.php' );
    $this->template->setVariable('COMODIN', '');
    
    //Datos de Filtro para Impresión
    $this->template->setVariable('buscarClientefr', $arreglo['buscarClientefr']);
    $this->template->setVariable('nitfr', $arreglo['nitfr']);
    $this->template->setVariable('fechadesdefr', $arreglo['fechadesdefr']);
    $this->template->setVariable('fechahastafr', $arreglo['fechahastafr']);
    $this->template->setVariable('doasignadofr', $arreglo['doasignadofr']);
    $this->template->setVariable('tiporechazofr', $arreglo['tiporechazofr']);

    $n = 1; $tpiezas_nal = $tpeso_nal = $tpiezas_ext = $tpeso_ext = 0;
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('n', $n);
      $this->template->setVariable('codigo', $value['codigo_mov']);
      $this->template->setVariable('doc_cliente', $value['numero_documento']);
      $this->template->setVariable('nombre_cliente', $value['razon_social']);
      $this->template->setVariable('orden', $value['orden']);
      $this->template->setVariable('doc_transporte', $value['doc_tte']);
      $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
      $this->template->setVariable('modelo', $value['modelo']);
      //Captura únicamente la fecha
      $this->template->setVariable('fecha_rechazo', date_format(new DateTime($value['fecha_rechazo']),'Y-m-d'));
      $this->template->setVariable('nombre_ubicacion', isset($value['nombre_ubicacion'])?$value['nombre_ubicacion']:'POR ASIGNAR');
      $this->template->setVariable('tipo_rechazo', $value['tipo_rechazo']);
      $this->template->setVariable('piezas_nal', number_format(abs($value['tc_nal']),2,".",","));
      $this->template->setVariable('peso_nal', number_format(abs($value['tp_nal']),2,".",","));
      $this->template->setVariable('piezas_ext', number_format(abs($value['tc_ext']),2,".",","));
      $this->template->setVariable('peso_ext', number_format(abs($value['tp_ext']),2,".",","));
      //Acumula Totales
      $tpiezas_nal += $value['tc_nal']; $tpiezas_ext += $value['tc_ext'];
      $tpeso_nal += $value['tp_nal']; $tpeso_ext += $value['tp_ext']; $n++;
      $this->template->parseCurrentBlock("ROW");
    }
    $this->template->setVariable('total_piezas_nal', number_format(abs($tpiezas_nal),2));
    $this->template->setVariable('total_peso_nal', number_format(abs($tpeso_nal),2));
    $this->template->setVariable('total_piezas_ext', number_format(abs($tpiezas_ext),2));
    $this->template->setVariable('total_peso_ext', number_format(abs($tpeso_ext),2));
    
    $this->template->show();
  }
  
  function mostrarListadoReferencias($arreglo) {
    $this->template->loadTemplateFile('acondicionamientos/views/tmpl/listadoReferencias.php' );
    $this->template->setVariable('COMODIN', '' );
    $this->template->setVariable('tipo_mercancia', $arreglo['tipo_mercancia']);
    $this->template->setVariable('nombre_tipo_mercancia',$arreglo['nombre_tipo_mercancia']);        
    
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
        } else {
          if($disponibles->cantidad_nonac>0) {
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
    $this->template->loadTemplateFile('acondicionamientos/views/tmpl/formAcondicionamiento.php' );
    $this->template->setVariable('COMODIN', '' );
    
    $cliente = new Clientes();
    $datosCliente = $cliente->recover($arreglo['docCliente'],'numero_documento');
    $this->template->setVariable('documento_cliente' , isset($datosCliente->numero_documento)?$datosCliente->numero_documento:'');
    $this->template->setVariable('nombre_cliente' , isset($datosCliente->razon_social)?$datosCliente->razon_social:'');
    
    $this->template->setVariable('tipo_mercancia' , $arreglo['tipo_mercancia']);
    $this->template->setVariable('nombre_tipo_mercancia',$arreglo['nombre_tipo_mercancia']);
    
    //Inicializa Placa y Destino
    $this->template->setVariable('placa','111111');
    $camion = new Camiones();
    $datosCamion = $camion->recover('111111','placa');
    $this->template->setVariable('id_camion',isset($datosCamion->codigo)?$datosCamion->codigo:'');
    $this->template->setVariable('conductor_nombre',isset($datosCamion->conductor_nombre)?$datosCamion->conductor_nombre:'');
    $this->template->setVariable('destinatario','Bodega');

    //Captura automática de fecha y hora 
    $fecha = new DateTime();
    $fecha = $fecha->format('Y-m-d H:i');
    $this->template->setVariable('fecha',$fecha);
        
    foreach($arreglo['seleccion'] as $value) {
      $referencias = new Referencias();
      $datosReferencia = $referencias->recover($value, 'codigo');

      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('cod_referencia', isset($datosReferencia->codigo)?$datosReferencia->codigo:'');
      $this->template->setVariable('codigo_referencia', isset($datosReferencia->codigo_ref)?$datosReferencia->codigo_ref:'');
      $this->template->setVariable('nombre_referencia', isset($datosReferencia->nombre)?$datosReferencia->nombre:'');
      $disponibles = $this->datos->disponiblesProducto($value,$arreglo['docCliente']);
      $this->template->setVariable('doc_tte', isset($disponibles->doc_tte)?$disponibles->doc_tte:'');

      $vrdisponible = $arreglo['tipo_mercancia']==1?(isset($disponibles->cantidad_naci)?number_format($disponibles->cantidad_naci,2,".",""):0):(isset($disponibles->cantidad_nonac)?number_format($disponibles->cantidad_nonac,2,".",""):0);
      $this->template->setVariable('disponible',$vrdisponible);
      $this->template->parseCurrentBlock("ROW");
    }
    
    $this->template->show();
  }
  
  function mostrarAcondicionamiento($arreglo) {
    $this->template->loadTemplateFile('acondicionamientos/views/tmpl/detalleAcondicionamiento.php' );
    $this->template->setVariable('COMODIN', '' );
 
    $datosMaestro = $this->datos->retornarMaestroAcondicionamiento($arreglo['id_registro']);
    
    //Habilita el botón de Acondicionar
    $this->template->setVariable('verBoton',$arreglo['verBoton']);

    $this->template->setVariable('mostrar_botones', 'block');
    $this->template->setVariable('mostrar_mensaje', 'none');
    $this->template->setVariable('n',1);
    if($datosMaestro->cierre==1) {
      $this->template->setVariable('mostrar_botones', 'none');
      $this->template->setVariable('mostrar_mensaje', 'block');
      $this->template->setVariable('n',2);
    }

    $this->template->setVariable('tipo_mercancia', $arreglo['tipo_mercancia']);
    $this->template->setVariable('nombre_tipo_mercancia', $arreglo['nombre_tipo_mercancia']);
      
    $this->template->setVariable('codigo_operacion', $datosMaestro->codigo);
    $this->template->setVariable('numero_documento', $datosMaestro->numero_documento);    
    $this->template->setVariable('razon_social', $datosMaestro->razon_social);
    $fecha = date_create($datosMaestro->fecha);
    $this->template->setVariable('fecha', date_format($fecha,'Y-m-d H:i'));
    $this->template->setVariable('id_camion', $datosMaestro->id_camion);
    $this->template->setVariable('destinatario', $datosMaestro->destinatario);
    $this->template->setVariable('ciudad', $datosMaestro->ciudad);
    $this->template->setVariable('codigo_ciudad', $datosMaestro->cod_ciudad);
    $this->template->setVariable('direccion', $datosMaestro->direccion);
    $this->template->setVariable('conductor_nombre', $datosMaestro->conductor_nombre);
    $this->template->setVariable('conductor_identificacion', $datosMaestro->conductor_identificacion);
    $this->template->setVariable('placa', $datosMaestro->placa);
    $this->template->setVariable('fmm', $datosMaestro->fmm);
    $this->template->setVariable('doc_tte', $datosMaestro->doc_tte);
    $this->template->setVariable('cod_referencia', $datosMaestro->producto);
    $this->template->setVariable('reginvima', $datosMaestro->parte_numero);
    $this->template->setVariable('codigo', 'PGP-01-F1');
    $this->template->setVariable('pedido', $datosMaestro->pedido);
    $this->template->setVariable('cantidad', number_format($datosMaestro->cantidad,2,".",","));
    $this->template->setVariable('cantidad_nac', number_format($datosMaestro->cantidad_nac,2,".",","));
    $this->template->setVariable('cantidad_ext', number_format($datosMaestro->cantidad_ext,2,".",","));
    $this->template->setVariable('observaciones', $datosMaestro->obs);
    
    $detalleAcondicionamiento = $this->datos->retornarDetalleAcondicionamiento($arreglo['id_registro']);
    $inv_entrada_mayor = 0;
    $valor_mayor = 0;
    foreach($detalleAcondicionamiento as $valueDetalle) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('orden_detalle', $valueDetalle['orden']);
      $this->template->setVariable('doc_tte', $valueDetalle['doc_tte']);
      $this->template->setVariable('inv_entrada', $valueDetalle['inventario_entrada']);
      $this->template->setVariable('codigo_referen', $valueDetalle['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $valueDetalle['nombre_referencia']);
      $this->template->setVariable('fecha_expira', $valueDetalle['fecha_expira']);
      $this->template->setVariable('fecha_detalle', $valueDetalle['fecha']);
      $this->template->setVariable('modelo', !empty($valueDetalle['modelo'])?$valueDetalle['modelo']:'POR ASIGNAR');
      $this->template->setVariable('nombre_ubicacion', isset($valueDetalle['nombre_ubicacion'])?$valueDetalle['nombre_ubicacion']:'POR ASIGNAR');
      if($valor_mayor==0){ // es la primera vez
        if(abs($valueDetalle['cantidad_nonac']) > 0){ // valida extranjero
          $valor_mayor=abs($valueDetalle['cantidad_nonac']);
          $inv_entrada_mayor=$valueDetalle['inventario_entrada'];
        } else {
          //valida nacional
          $valor_mayor=abs($valueDetalle['cantidad_naci']);
          $inv_entrada_mayor=$valueDetalle['inventario_entrada'];
        }
      }
      if($valor_mayor<> 0){ // se averigua el registro que tenga mayor cantidad
        if(abs($valueDetalle['cantidad_nonac']) > 0) { // valida extranjero
          if(abs($valueDetalle['cantidad_nonac']) > $valor_mayor) {
            $valor_mayor=abs($valueDetalle['cantidad_nonac']);
            $inv_entrada_mayor=$valueDetalle['inventario_entrada'];
          }
        } else {										//valida nacional
          if(abs($valueDetalle['cantidad_naci']) > $valor_mayor) {
            $valor_mayor=abs($valueDetalle['cantidad_naci']);
            $inv_entrada_mayor=$valueDetalle['inventario_entrada'];
          }
        }
      }
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
      $this->template->setVariable('inv_entrada_mayor', $inv_entrada_mayor);
      $this->template->parseCurrentBlock("ROW");
    }
    
    $this->template->show();
  }

  function generarOrdenAcondicionamiento($arreglo) {
    $this->template->loadTemplateFile('acondicionamientos/views/tmpl/detalleOrdenAcondicionamiento.php' );
    $this->template->setVariable('COMODIN', '' );
    
    $datosMaestro = $this->datos->retornarMaestroAcondicionamiento($arreglo['codigoMaestro']);
    
    $this->template->setVariable('mostrar_botones', 'block');
    $this->template->setVariable('mostrar_mensaje', 'none');
    if($datosMaestro->cierre==1) {
      $this->template->setVariable('mostrar_botones', 'none');
      $this->template->setVariable('mostrar_mensaje', 'block');
    }

    $this->template->setVariable('nombre_tipo_mercancia', $arreglo['nombre_tipo_mercancia']);
    $this->template->setVariable('codigo_reporte', $arreglo['codigo_reporte']);
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
    $this->template->setVariable('producto', $datosMaestro->nombre_referencia);
    $this->template->setVariable('peso', number_format(abs($datosMaestro->peso),2,".",","));
    $this->template->setVariable('valor', number_format(abs($datosMaestro->valor),2,".",","));    
    $this->template->setVariable('orden', $datosMaestro->orden);
    $this->template->setVariable('doc_tte', $datosMaestro->doc_tte);
    $this->template->setVariable('pedido', $datosMaestro->pedido);
    $this->template->setVariable('cantidad', number_format($datosMaestro->cantidad,2,".",","));
    $this->template->setVariable('cantidad_nac', number_format($datosMaestro->cantidad_nac,2,".",","));
    $this->template->setVariable('cantidad_ext', number_format($datosMaestro->cantidad_ext,2,".",","));
    $this->template->setVariable('observaciones', $datosMaestro->obs);
    
    $detalleAcondicionamiento = $this->datos->regOrdenDetalleAcondicionamiento($arreglo['codigoMaestro']);
    
    //Inicializa las variables que registran los totales
    $tot_acondicionadas = $tot_rechazadas = $tot_devueltas = 0;
    $nr = 0;

    foreach($detalleAcondicionamiento as $valueDetalle) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('nr', ++$nr);
      $this->template->setVariable('orden_detalle', $valueDetalle['orden']);
      $this->template->setVariable('doc_tte', $valueDetalle['doc_tte']);
      $this->template->setVariable('codigo_referen', $valueDetalle['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $valueDetalle['nombre_referencia']);
      $this->template->setVariable('fecha_expira', $valueDetalle['fecha_expira']);
      $this->template->setVariable('fecha_detalle', $valueDetalle['fecha_mov']);    
      $this->template->setVariable('modelo', !empty($valueDetalle['modelo'])?$valueDetalle['modelo']:'POR ASIGNAR');
      $this->template->setVariable('nombre_ubicacion', isset($valueDetalle['nombre_ubicacion'])?$valueDetalle['nombre_ubicacion']:'POR ASIGNAR');
      $this->template->setVariable('acondicionadas', number_format($valueDetalle['acondicionadas'],2,".",","));
      $this->template->setVariable('rechazadas', number_format($valueDetalle['rechazadas'],2,".",","));
      $this->template->setVariable('devueltas', number_format($valueDetalle['devueltas'],2,".",","));
      /*************************************************************************************************/
      // Registro de Totales
      $tot_acondicionadas += $valueDetalle['acondicionadas']; $tot_rechazadas += $valueDetalle['rechazadas'];
      $tot_devueltas += $valueDetalle['devueltas'];
      /*************************************************************************************************/
      $this->template->parseCurrentBlock("ROW");
    }
    // Captura los totales registrados
    $this->template->setVariable('tot_acondicionadas', number_format($tot_acondicionadas,2,".",","));
    $this->template->setVariable('tot_rechazadas', number_format($tot_rechazadas,2,".",","));
    $this->template->setVariable('tot_devueltas', number_format($tot_devueltas,2,".",","));
    
    $this->template->show();
  }

  function registroAcondicionamiento($arreglo) {
    $this->template->loadTemplateFile('acondicionamientos/views/tmpl/registroAcondicionamiento.php' );
    $this->template->setVariable('COMODIN', '' );

    //Carga el cuadro de lista Tipo de Rechazo - Tabla: estados_mcia
    $lista_tiporechazo = $this->datos->build_list("estados_mcia", "codigo", "nombre");
    $arreglo['select_tiporechazo'] = $this->datos->armSelect($lista_tiporechazo, 'Seleccione Tipo Rechazo...', 1);

    $this->template->setVariable('codigo_operacion',$arreglo['codigo_maestro']);
    $this->template->setVariable('tipo_mercancia',$arreglo['tipo_mercancia']);
    $this->template->setVariable('nombre_tipo_mercancia',$arreglo['nombre_tipo_mercancia']);
    $this->template->setVariable('doc_cliente',$arreglo['doc_cliente']);
    $this->template->setVariable('fecha',$arreglo['fecha']);
    $this->template->setVariable('id_camion',$arreglo['id_camion']);
    $this->template->setVariable('destinatario',$arreglo['destinatario']);
    $this->template->setVariable('direccion',$arreglo['direccion']);
    $this->template->setVariable('fmm',$arreglo['fmm']);
    $this->template->setVariable('doc_tte',$arreglo['doc_tte']);
    $this->template->setVariable('pedido',$arreglo['pedido']);
    $this->template->setVariable('codigo_ciudad',$arreglo['codigo_ciudad']);
    $this->template->setVariable('observaciones',$arreglo['observaciones']);

    $detalleAcondicionamiento = $this->datos->retornarDetalleAcondicionamiento($arreglo['codigo_maestro']);
    $inv_entrada_mayor=0;
    $n=$valor_mayor=0;
    foreach($detalleAcondicionamiento as $valueDetalle) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('n',$n);
      $this->template->setVariable('orden_detalle', $valueDetalle['orden']);
      $this->template->setVariable('doc_tte', $valueDetalle['doc_tte']);
      $this->template->setVariable('inv_entrada', $valueDetalle['inventario_entrada']);
      $this->template->setVariable('cod_referencia', $valueDetalle['cod_referencia']);
      $this->template->setVariable('codigo_referen', $valueDetalle['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $valueDetalle['nombre_referencia']);
      $this->template->setVariable('select_tiporechazo',$arreglo['select_tiporechazo']);
      $this->template->setVariable('acondicionar', $valueDetalle['cantidad_naci']!=0?number_format(abs($valueDetalle['cantidad_naci']),2,".",","):number_format(abs($valueDetalle['cantidad_nonac']),2,".",","));
      if($valor_mayor==0){ // es la primera vez
        if(abs($valueDetalle['cantidad_nonac']) > 0){ // valida extranjero
          $valor_mayor=abs($valueDetalle['cantidad_nonac']);
          $inv_entrada_mayor=$valueDetalle['inventario_entrada'];
        } else {
          //valida nacional
          $valor_mayor=abs($valueDetalle['cantidad_naci']);
          $inv_entrada_mayor=$valueDetalle['inventario_entrada'];
        }
      }
      if($valor_mayor<> 0){ // se averigua el registro que tenga mayor cantidad
        if(abs($valueDetalle['cantidad_nonac']) > 0) { // valida extranjero
          if(abs($valueDetalle['cantidad_nonac']) > $valor_mayor) {
            $valor_mayor=abs($valueDetalle['cantidad_nonac']);
            $inv_entrada_mayor=$valueDetalle['inventario_entrada'];
          }
        } else {										//valida nacional
          if(abs($valueDetalle['cantidad_naci']) > $valor_mayor) {
            $valor_mayor=abs($valueDetalle['cantidad_naci']);
            $inv_entrada_mayor=$valueDetalle['inventario_entrada'];
          }
        }
      }
      $this->template->setVariable('inv_entrada_mayor', $inv_entrada_mayor);
      $n++;
      $this->template->parseCurrentBlock("ROW");
    }
    
    $this->template->show();    
  }
    
  function imprimeListadoRechazadas($arreglo) {
    $this->template->loadTemplateFile('acondicionamientos/views/tmpl/verListadoRechazadas.php' );
    $this->template->setVariable('COMODIN', '');

    $n = 1; $tpiezas_nal = $tpeso_nal = $tpiezas_ext = $tpeso_ext = 0;
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('n', $n);
      $this->template->setVariable('doc_cliente', $value['numero_documento']);
      $this->template->setVariable('nombre_cliente', $value['razon_social']);
      $this->template->setVariable('orden', $value['orden']);
      $this->template->setVariable('doc_transporte', $value['doc_tte']);
      $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
      $this->template->setVariable('modelo', $value['modelo']);
      //Captura únicamente la fecha
      $this->template->setVariable('fecha_rechazo', date_format(new DateTime($value['fecha_rechazo']),'Y-m-d'));
      $this->template->setVariable('nombre_ubicacion', isset($value['nombre_ubicacion'])?$value['nombre_ubicacion']:'POR ASIGNAR');
      $this->template->setVariable('tipo_rechazo', $value['tipo_rechazo']);
      $this->template->setVariable('piezas_nal', number_format(abs($value['tc_nal']),2,".",","));
      $this->template->setVariable('peso_nal', number_format(abs($value['tp_nal']),2,".",","));
      $this->template->setVariable('piezas_ext', number_format(abs($value['tc_ext']),2,".",","));
      $this->template->setVariable('peso_ext', number_format(abs($value['tp_ext']),2,".",","));
      //Acumula Totales
      $tpiezas_nal += $value['tc_nal']; $tpiezas_ext += $value['tc_ext'];
      $tpeso_nal += $value['tp_nal']; $tpeso_ext += $value['tp_ext']; $n++;
      $this->template->parseCurrentBlock("ROW");
    }
    $this->template->setVariable('total_piezas_nal', number_format(abs($tpiezas_nal),2));
    $this->template->setVariable('total_peso_nal', number_format(abs($tpeso_nal),2));
    $this->template->setVariable('total_piezas_ext', number_format(abs($tpiezas_ext),2));
    $this->template->setVariable('total_peso_ext', number_format(abs($tpeso_ext),2));
    
    $this->template->show();
  }
}
?>