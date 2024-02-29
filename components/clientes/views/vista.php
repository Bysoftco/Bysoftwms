<?php
require_once COMPONENTS_PATH . 'clientes/model/clientes.php';

class ClientesVista {
  var $datosV;
	
  function ClientesVista() {
    $this->template = new HTML_Template_IT(COMPONENTS_PATH);
    $this->datosV = new ClientesModelo();
  }
	
  function listadoClientes($arreglo) {
    $this->template->loadTemplateFile('clientes/views/tmpl/listadoClientes.php');
    $this->template->setVariable('COMODIN','');
    $this->template->setVariable('paginacion',$arreglo['datos']['paginacion']);
    $this->template->setVariable('pagina',$arreglo['pagina']);
    $this->template->setVariable('verAlerta','none');
	
    $this->template->setVariable('orden',isset($arreglo['orden'])?$arreglo['orden']:"");
    $this->template->setVariable('id_orden',isset($arreglo['id_orden'])?$arreglo['id_orden']:"");
    $this->template->setVariable('campoBuscar',isset($arreglo['buscar'])?$arreglo['buscar']:"");
	
    if(isset($arreglo['alerta_accion'])) {
      $this->template->setVariable('alerta_accion',$arreglo['alerta_accion']);
      $this->template->setVariable('verAlerta','block');
    }
	
    $codbagcolor = 1;
    foreach($arreglo['datos']['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      if($codbagcolor==1) {
        $this->template->setVariable('id_tr_estilo','tr_blanco');
        $codbagcolor = 2;
      } else {
        $this->template->setVariable('id_tr_estilo','tr_gris_cla');	
        $codbagcolor = 1;
      }
      $this->template->setVariable('tipo_documento',$value['tipo_doc']);
      $this->template->setVariable('no_documento',$value['numero_documento']);
      $this->template->setVariable('dig_verificacion',$value['digito_verificacion']);
      $this->template->setVariable('razon_social',$value['razon_social']);
      $this->template->setVariable('tipo',$value['nombre_tipo_cliente']);
      $this->template->setVariable('actividad_economica',$value['nombre_actividad_eco']);
      $this->template->setVariable('regimen',$value['nombre_regimen']);
      $this->template->parseCurrentBlock("ROW");
    }
    $this->template->show();
  }
	
  function agregarCliente($arreglo) {
    $this->template->loadTemplateFile('clientes/views/tmpl/editarCliente.php');
    $this->template->setVariable('COMODIN','');
    
    if(isset($arreglo['datosCliente']['actividad_economica'])) {
      $this->template->setVariable('cod_actividad', $arreglo['datosCliente']['actividad_economica']);
      $this->template->setVariable('nom_actividad', "[".$arreglo['datosCliente']['actividad_economica']."] ".$arreglo['datosCliente']['nombre_actividad']);
    }
    
    $this->template->setVariable('select_tipodoc', $arreglo['select_tipodoc']);
    $this->template->setVariable('select_regimen', $arreglo['select_regimen']);
    $this->template->setVariable('select_tipocli', $arreglo['select_tipocli']);
    $this->template->setVariable('select_tipofac', $arreglo['select_tipofac']);
    $this->template->setVariable('select_actividades', $arreglo['select_actividades']);
    $this->template->setVariable('select_comercial', $arreglo['select_comercial']);
    $this->template->setVariable('select_departamento', $arreglo['select_departamento']);
    $this->template->setVariable('select_municipio', $arreglo['select_municipio']);

    if(isset($arreglo['datosCliente']['tipo_documento'])) {
      if($arreglo['datosCliente']['tipo_documento']==15 || $arreglo['datosCliente']['tipo_documento']==30) {
        $this->template->setVariable('verTexto' ,'none');
        $this->template->setVariable('verCampo' ,'block');
      } else {     
        $this->template->setVariable('verTexto' ,'block');
        $this->template->setVariable('verCampo' ,'none');
      }
    } else {     
      $this->template->setVariable('verTexto' ,'none');
      $this->template->setVariable('verCampo' ,'block');
		}
    $this->template->setVariable('tipo_identi', isset($arreglo['datosCliente']['tipo_identi'])?$arreglo['datosCliente']['tipo_identi']:'');
    $this->template->setVariable('numero_documento', isset($arreglo['datosCliente']['numero_documento'])?$arreglo['datosCliente']['numero_documento']:'');
    $this->template->setVariable('autoretenedor', isset($arreglo['datosCliente']['autoretenedor'])&&$arreglo['datosCliente']['autoretenedor']==1?'checked':'');
    $this->template->setVariable('digito_verificacion', isset($arreglo['datosCliente']['digito_verificacion'])?$arreglo['datosCliente']['digito_verificacion']:'');
    $this->template->setVariable('razon_social', isset($arreglo['datosCliente']['razon_social'])?$arreglo['datosCliente']['razon_social']:'');
	
    $this->template->setVariable('direccion', isset($arreglo['datosCliente']['direccion'])?$arreglo['datosCliente']['direccion']:'');
    $this->template->setVariable('telefonos_fijos', isset($arreglo['datosCliente']['telefonos_fijos'])?$arreglo['datosCliente']['telefonos_fijos']:'');
    $this->template->setVariable('telefonos_celulares', isset($arreglo['datosCliente']['telefonos_celulares'])?$arreglo['datosCliente']['telefonos_celulares']:'');
    $this->template->setVariable('telefonos_faxes', isset($arreglo['datosCliente']['telefonos_faxes'])?$arreglo['datosCliente']['telefonos_faxes']:'1');
    $this->template->setVariable('pagina_web', isset($arreglo['datosCliente']['pagina_web'])?$arreglo['datosCliente']['pagina_web']:'');
    $this->template->setVariable('correo_electronico', isset($arreglo['datosCliente']['correo_electronico'])?$arreglo['datosCliente']['correo_electronico']:'');
	
    $this->template->setVariable('dias_para_facturar', isset($arreglo['datosCliente']['dias_para_facturar'])?$arreglo['datosCliente']['dias_para_facturar']:'30');
    $this->template->setVariable('periodicidad', isset($arreglo['datosCliente']['periodicidad'])?$arreglo['datosCliente']['periodicidad']:'30');
    $this->template->setVariable('dias_gracia', isset($arreglo['datosCliente']['dias_gracia'])?$arreglo['datosCliente']['dias_gracia']:'1');
    $this->template->setVariable('cir170', isset($arreglo['datosCliente']['cir170'])&&$arreglo['datosCliente']['cir170']==1?'checked':'');
    $this->template->setVariable('id', isset($arreglo['id'])?$arreglo['id']:0);
    $this->template->show();
  }
  
  function vistaGeneral($arreglo) {
    $this->template->loadTemplateFile('clientes/views/tmpl/templateVista.php');
    $this->template->setVariable('COMODIN','');
    $this->template->setVariable('numero_identificacion',$arreglo['documento']);
    
    ob_start();
    $this->verCliente($arreglo);
    $info_cliente = ob_get_contents();
    ob_end_clean();
	
    ob_start();
    $this->verTarifas($arreglo);
    $info_tarifas = ob_get_contents();
    ob_end_clean();
    
    ob_start();
    $this->verReferencias($arreglo);
    $info_referencias = ob_get_contents();
    ob_end_clean();
    
    ob_start();
    $this->verDocumentos($arreglo);
    $info_documentos = ob_get_contents();
    ob_end_clean();
    
    ob_start();
    $this->retornar_header('header_info_cliente');
    $header = ob_get_contents();
    ob_end_clean();

    $this->template->setVariable('tab_que_aplica',$header);
    $this->template->setVariable('info_cliente',$info_cliente);
    $this->template->setVariable('info_tarifas',$info_tarifas);
    $this->template->setVariable('info_referencias',$info_referencias);
    $this->template->setVariable('info_documentos',$info_documentos);
	
    $this->template->show();
  }

  function retornar_header($activar) {
    $tmpl = new HTML_Template_IT(COMPONENTS_PATH);
    $tmpl->loadTemplateFile('clientes/views/tmpl/header_barra.php');
    $tmpl->setVariable('COMODIN','');
    $tmpl->setVariable('mostrar_header',$activar);
    return $tmpl->show();
  }
	
  function verCliente($arreglo) {
    $template = new HTML_Template_IT(COMPONENTS_PATH);
    $template->loadTemplateFile('clientes/views/tmpl/verCliente.php');
    $template->setVariable('COMODIN','');
    $template->setVariable('tipo_identificacion',$arreglo['datosCliente']['tipo_identi']);
    $template->setVariable('tipo_cliente',$arreglo['datosCliente']['nombre_tipo_cliente']);
    $template->setVariable('numero_identificacion',$arreglo['datosCliente']['numero_documento']);
    if($arreglo['datosCliente']['autoretenedor']==1)
      {$template->setVariable('seleccionado','checked');}
    $template->setVariable('digito_verificacion',$arreglo['datosCliente']['digito_verificacion']);
    $template->setVariable('regimen',$arreglo['datosCliente']['nombre_regimen']);
    $template->setVariable('razon_social',$arreglo['datosCliente']['razon_social']);
    $template->setVariable('actividad_economica',$arreglo['datosCliente']['nombre_actividad']);
    $template->setVariable('direccion',$arreglo['datosCliente']['direccion']);
    $template->setVariable('celular',$arreglo['datosCliente']['telefonos_celulares']);
    $template->setVariable('telefonos_fijos',$arreglo['datosCliente']['telefonos_fijos']);
    $template->setVariable('telefax',$arreglo['datosCliente']['telefonos_faxes']);
    $template->setVariable('pagina_web',$arreglo['datosCliente']['pagina_web']);
    $template->setVariable('correo_electronico',$arreglo['datosCliente']['correo_electronico']);
    $template->setVariable('tipo_facturacion',$arreglo['datosCliente']['nombre_tipo_facturacion']);
    $template->setVariable('dias_pago',$arreglo['datosCliente']['dias_para_facturar']);
    $template->setVariable('periodicidad',$arreglo['datosCliente']['periodicidad']);
    $template->setVariable('dias_gracia',$arreglo['datosCliente']['dias_gracia']);
    if($arreglo['datosCliente']['cir170'] == 1)
      {$template->setVariable('seleccionado170','checked');}
    $template->setVariable('comercial',$arreglo['datosCliente']['nombre_vendedor']);
	
    $template->show();
  }
	
  function verTarifas($arreglo) {
    $template = new HTML_Template_IT(COMPONENTS_PATH);
    $template->loadTemplateFile('clientes/views/tmpl/verTarifas.php');
    $template->setVariable('COMODIN','');
    $template->setVariable('numero_identificacion',$arreglo['datosCliente']['numero_documento']);
    $contador = 0;
    foreach($arreglo['datosTarifas'] as $key => $value) {
      foreach($value['servicios'] as $keyS =>$valueS) {
        $template->setCurrentBlock("ROW2");
        $template->setVariable('servicio',$valueS['servicios']);
        $template->setVariable('base',$valueS['base']);
        $template->setVariable('valor_minimo',$valueS['valor_minimo']);
        $template->setVariable('tope',$valueS['tope']);
        $template->setVariable('valor',$valueS['valor']);
        $template->setVariable('adicional',$valueS['adicional']);
        $template->setVariable('dias',$valueS['dias']);
        $template->setVariable('vigencia',$valueS['vigencia']);
        $template->parseCurrentBlock("ROW2");
      }
		
      $template->setCurrentBlock("ROW");
      $template->setVariable('id_tarifa',$value['id']);
      $template->setVariable('nombre_tarifa',$value['nombre_tarifa']);
      if($value['general']==1){$template->setVariable('general','Tarifa General');}
      $template->setVariable('contador',$contador);
      $contador++;
      $template->parseCurrentBlock("ROW");
    }
    $template->show();
  }
	
  function agregarTarifa($arreglo) {
    $this->template->loadTemplateFile('clientes/views/tmpl/agregarTarifa.php');
    $this->template->setVariable('COMODIN','');
    $this->template->setVariable('nombre_tarifa',$arreglo['infoTarifa']->nombre_tarifa);
    if($arreglo['infoTarifa']->general==1) {
      $this->template->setVariable('checked_general','checked');
    }
	
    foreach($arreglo['serviciosAtados'] as $key => $value) {
      $this->template->setCurrentBlock("ROW");
      ob_start();
      $this->nuevoServicio($value, $key);
      $serv = ob_get_contents();
      ob_end_clean();
      $this->template->setVariable('servicio_atado',$serv);
      $this->template->parseCurrentBlock("ROW");
    }
    $this->template->setVariable('cliente',$arreglo['numero_documento']);
    $this->template->setVariable('numeral',count($arreglo['serviciosAtados']));
    $this->template->show();
  }

  function nuevoServicio($value, $codigo) {
    $template = new HTML_Template_IT(COMPONENTS_PATH);
    $template->loadTemplateFile('clientes/views/tmpl/servicios.php');
    $template->setVariable('COMODIN','');

    $lista_servicio = $this->datosV->build_list("servicios","codigo","nombre");
    $select_servicio = $this->datosV->armSelect($lista_servicio,'Seleccione Servicio...',isset($value['servicios'])?$value['servicios']:'');

    $lista_base = $this->datosV->build_list("conceptos_tarifas","codigo","nombre");
    $select_base = $this->datosV->armSelect($lista_base ,'Seleccione Base...',isset($value['base'])?$value['base']:'');
    	
    $template->setVariable('cod',$codigo);
    $template->setVariable('select_servicio',$select_servicio);
    $template->setVariable('select_base',$select_base);
    $template->setVariable('valor_mminimo',isset($value['valor_minimo'])?$value['valor_minimo']:'');
    $template->setVariable('tope',isset($value['tope'])?$value['tope']:'');
    $template->setVariable('valor',isset($value['valor'])?$value['valor']:'');
    $template->setVariable('adicional',isset($value['adicional'])?$value['adicional']:'');
    $template->setVariable('dias',isset($value['dias'])?$value['dias']:'');	
    $template->setVariable('vigencia',isset($value['vigencia'])?$value['vigencia']:'');
    $template->show();
  }

  function verReferencias($arreglo) {
    $template = new HTML_Template_IT(COMPONENTS_PATH);
    $template->loadTemplateFile('clientes/views/tmpl/verReferencias.php');
    $template->setVariable('COMODIN','');
    $template->setVariable('numero_identificacion',$arreglo['documento']);
    
    if(!is_null($arreglo['datosReferencias'])) {
      foreach($arreglo['datosReferencias'] as $key => $value) {
        $template->setCurrentBlock("ROW");
        $template->setVariable('id_referencia',$value['codigo_ref']);
        $template->setVariable('nombre_referencia',$value['nombre']);
        $template->setVariable('tipo_referencia',$value['tipo']);
        $template->setVariable('SKU_Proveedor',$value['ref_prove']);
        $template->setVariable('unidad_referencia',$value['unidad_venta']);
        $template->setVariable('presenta_venta',$value['presentacion_venta']);
        $template->setVariable('ancho_referencia',$value['ancho']);
        $template->setVariable('largo_referencia',$value['largo']);
        $template->setVariable('alto_referencia',$value['alto']);
        $template->setVariable('embalaje_referencia',$value['unidad']);
        $template->setVariable('vence_referencia',$value['fecha_expira']);
        $template->setVariable('serial_referencia',$value['serial']);
        $template->setVariable('minimo_stock',$value['min_stock']);
        $template->setVariable('grupo_items',$value['grupo_item']);
        $template->setVariable('factor_conversion',$value['factor_conversion']);
        $template->setVariable('parte_numero',$value['parte_numero']);
        $template->setVariable('vigencia',$value['vigencia']);
        $template->parseCurrentBlock("ROW");
      }  
    }
    
    $template->show();
  }

  //Abre ventana formulario nueva referencia y editar referencia
  function agregarReferencia($arreglo) {
    $this->template->loadTemplateFile('clientes/views/tmpl/agregarReferencia.php');
    $this->template->setVariable('COMODIN','');
    $this->template->setVariable('id_referencia',isset($arreglo['infoReferencia']->codigo_ref)?$arreglo['infoReferencia']->codigo_ref:'');
    $this->template->setVariable('nombre_referencia',isset($arreglo['infoReferencia']->nombre)?$arreglo['infoReferencia']->nombre:'');
    $this->template->setVariable('select_tiporef',$arreglo['select_tiporef']);
    $this->template->setVariable('SKU_Proveedor',isset($arreglo['infoReferencia']->ref_prove)?$arreglo['infoReferencia']->ref_prove:'');
    $this->template->setVariable('select_unidad',$arreglo['select_unidad']);
    $this->template->setVariable('select_tipoemb',$arreglo['select_tipoemb']);
    $this->template->setVariable('cod_uniref',$arreglo['cod_uniref'][0]['codigo']);
    if(isset($arreglo['infoReferencia']->fecha_expira)) {
      if($arreglo['infoReferencia']->fecha_expira==1) $this->template->setVariable('vence_referencia','checked');
    }
    if(isset($arreglo['infoReferencia']->serial)) {
      if($arreglo['infoReferencia']->serial==1) $this->template->setVariable('serial_referencia','checked');
    }
    if(isset($arreglo['infoReferencia']->min_stock)) {
      if($arreglo['infoReferencia']->min_stock==1) $this->template->setVariable('minimo_stock','checked');
    }
    $this->template->setVariable('grupo_item',$arreglo['grupo_item']->nombre);
    $this->template->setVariable('factor_conversion',isset($arreglo['infoReferencia']->factor_conversion)?$arreglo['infoReferencia']->factor_conversion:1);
    $this->template->setVariable('lote_cosecha',isset($arreglo['infoReferencia']->lote_cosecha)?$arreglo['infoReferencia']->lote_cosecha:'');
    $this->template->setVariable('parte_numero', isset($arreglo['infoReferencia']->parte_numero)?$arreglo['infoReferencia']->parte_numero:1);
    $this->template->setVariable('ancho', isset($arreglo['infoReferencia']->ancho)?$arreglo['infoReferencia']->ancho:1);
    $this->template->setVariable('largo', isset($arreglo['infoReferencia']->largo)?$arreglo['infoReferencia']->largo:1);
    $this->template->setVariable('alto', isset($arreglo['infoReferencia']->alto)?$arreglo['infoReferencia']->alto:1);
    $this->template->setVariable('cliente',$arreglo['numero_documento']);
    $this->template->setVariable('accion',$arreglo['accion']);
    $this->template->show();
  }
  
  function verDocumentos($arreglo) {
    $template = new HTML_Template_IT(COMPONENTS_PATH);
    $template->loadTemplateFile('clientes/views/tmpl/verDocumentos.php');
    $template->setVariable('COMODIN','');
    $template->setVariable('numero_identificacion',$arreglo['datosCliente']['numero_documento']);
    
    if(!is_null($arreglo['datosDocumentos'])) {
      $n = 1;
      foreach($arreglo['datosDocumentos'] as $key => $value) {
        $template->setCurrentBlock("ROW");
        $template->setVariable('n',$n);
        $template->setVariable('numdoc',$value['numero_documento']);
        $template->setVariable('fechadoc',$value['fecha_documento']);
        $template->setVariable('nomdoc',$value['nombre_documento'] );
        $n++;
        $template->parseCurrentBlock("ROW");
      }  
    }
    
    $template->show();
  }
  
  function agregarDocumento($arreglo) {
    $this->template->loadTemplateFile('clientes/views/tmpl/agregarDocumento.php');
    $this->template->setVariable('COMODIN','');
    $this->template->setVariable('cliente',$arreglo['numero_documento']);
    $this->template->show();
  }
}
?>