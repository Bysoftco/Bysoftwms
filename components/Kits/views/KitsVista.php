<?php
/**
 * Description of KitsVista
 *
 * @author Teresa
 */
require_once COMPONENTS_PATH . 'Kits/model/KitsDatos.php';
require_once COMPONENTS_PATH . 'Entidades/Clientes.php';
require_once COMPONENTS_PATH . 'Entidades/Referencias.php';
require_once COMPONENTS_PATH . 'Entidades/KitsMaestro.php';
require_once COMPONENTS_PATH . 'Entidades/KitsDetalle.php';
require_once COMPONENTS_PATH . 'Entidades/InventarioMovimientos.php';
require_once COMPONENTS_PATH . 'Entidades/InventarioMaestroMovimientos.php';
require_once COMPONENTS_PATH . 'Entidades/InventarioEntradas.php';

class KitsVista {
  var $template;
  var $datos;
            
  function KitsVista() {
    $this->template = new HTML_Template_IT();
    $this->datos = new KitsDatos();
  }
    
  function filtroClientes() {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'Kits/views/tmpl/filtroClientes.php' );
    $this->template->setVariable('COMODIN', '' );
        
    $listaClientes = $this->datos->build_list("clientes", "numero_documento", "razon_social");
    $selectClientes = $this->datos->armSelect($listaClientes ,'Seleccione Cliente...');
    $this->template->setVariable('select_clientes', $selectClientes );
        
    $this->template->show();
  }
    
  function mostrarInfoKits($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'Kits/views/tmpl/listadoKits.php' );

    $cliente = new Clientes();
    $datosCliente = $cliente->recover($arreglo['docCliente'],'numero_documento');
    $this->template->setVariable('COMODIN', '' );
    $this->template->setVariable('verAlerta' , 'none');
    $this->template->setVariable('documento_cliente' , isset($datosCliente->numero_documento)?$datosCliente->numero_documento:'');
    $this->template->setVariable('nombre_cliente' , isset($datosCliente->razon_social)?$datosCliente->razon_social:'');
            
    $referencias = new Referencias();
    $maestro = new KitsMaestro();
    $kitsCliente = $maestro->get_listed(" cliente = ".$arreglo['docCliente']." AND eliminado = 0 ");
       
    //Recorre los Kits del cliente para listarlos y colocar la info correspondiente
    foreach($kitsCliente as $key => $value) {
      $disponibles = $this->retornarDisponiblesKits($value['id'], $arreglo['docCliente']);
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('id', $value['id']);
      $this->template->setVariable('codigo_kit', $value['codigo_kit']);
      $this->template->setVariable('nombre_kit', $value['nombre_kit']);
      $this->template->setVariable('descripcion', $value['descripcion']);
      $this->template->setVariable('fecha_creacion', $value['fecha_creacion']);
      $this->template->setVariable('cantidad_disponible_nal', $disponibles->nacional);
      $this->template->setVariable('cantidad_disponible_nonal', $disponibles->no_nacional);
      $this->template->setVariable('cantidad_disponible_mixta', $disponibles->mixta);
      $this->template->parseCurrentBlock("ROW");
    }
    $this->template->show();
  }
    
  function retornarDisponiblesKits($idKit, $docCliente) {
    $detalle = new KitsDetalle();
    $infoDetalle = $detalle->get_listed(" id_kit = $idKit ");
    $cantDisponibleNal = 0;
    $cantDisponibleNoNal = 0;
    $cantDisponibleMixto = 0;
    $primera = true;
    foreach($infoDetalle as $key => $value) {
      $referencias = new Referencias();
      $datosReferencia = $referencias->get_listed("codigo_ref='".$value['codigo_referencia']."' AND cliente=$docCliente");    
       
      if(count($datosReferencia)>0) {
        $datosReferencia=(object)$datosReferencia[0];
      }
        
      $idReferencia = isset($datosReferencia->codigo)?$datosReferencia->codigo:"";
      // Corrección de disponbibles por disponibles
      $disponibles = $this->datos->disponiblesProducto($idReferencia, $docCliente);   
      $enKit = isset($value['cantidad_en_kit'])?$value['cantidad_en_kit']:0;
            
      if($primera) {
        if($enKit>0) {
          $cantDisponibleNal = (isset($disponibles->cantidad_naci)?$disponibles->cantidad_naci:0)/$enKit;
          $cantDisponibleNoNal = (isset($disponibles->cantidad_nonac)?$disponibles->cantidad_nonac:0)/$enKit;
          $cantDisponibleMixto = (isset($disponibles->cantidad_mixto)?$disponibles->cantidad_mixto:0)/$enKit;
        }
        $primera = false;
      } else {
        if($enKit>0) {
          $Nal = (isset($disponibles->cantidad_naci)?$disponibles->cantidad_naci:0)/$enKit;
          $NoNal = (isset($disponibles->cantidad_nonac)?$disponibles->cantidad_nonac:0)/$enKit;
          $Mixta = (isset($disponibles->cantidad_mixto)?$disponibles->cantidad_mixto:0)/$enKit;
          if($Nal<$cantDisponibleNal) { $cantDisponibleNal = $Nal; }
          if($NoNal<$cantDisponibleNoNal) { $cantDisponibleNoNal = $NoNal; }
          if($Mixta<$cantDisponibleMixto) { $cantDisponibleMixto = $Mixta; }
        }
      }
    }
    $disponibles->nacional = (int)$cantDisponibleNal;
    $disponibles->no_nacional = (int)$cantDisponibleNoNal;
    $disponibles->mixta = (int)$cantDisponibleMixto;
    return $disponibles;
  }
    
  function agregarKit($arreglo) {
    $cliente = new Clientes();
    $datosCliente = $cliente->recover($arreglo['docCliente'],'numero_documento');
        
    $referencias = new Referencias();
    $datosReferencias = $referencias->get_listed(" cliente= ".$arreglo['docCliente']." AND tipo<>25 ");
        
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'Kits/views/tmpl/formularioKits.php' );
    $this->template->setVariable('COMODIN', '');
        
    $this->template->setVariable('titulo_accion', 'Formulario Kit');
    $this->template->setVariable('documento_cliente' , isset($datosCliente->numero_documento)?$datosCliente->numero_documento:'');
    $this->template->setVariable('nombre_cliente' , isset($datosCliente->razon_social)?$datosCliente->razon_social:'');
        
    $lista_ucomercial = $this->datos->build_list("unidades_medida", "id", "medida");
    $lista_pventa = $this->datos->build_list("tipos_embalaje", "codigo", "nombre");
        
    $infoDetalle = array();
    if(isset($arreglo['idKit'])) {
      $maestro = new KitsMaestro();
      $detalle = new KitsDetalle();
      $infoMaestro = $maestro->recover($arreglo['idKit'], 'id');
      $infoDetalle = $detalle->get_listed(" id_kit=".$arreglo['idKit']." ");
      $this->template->setVariable('codigo_kit', isset($infoMaestro->codigo_kit)?$infoMaestro->codigo_kit:'');
      $this->template->setVariable('nombre_kit', isset($infoMaestro->nombre_kit)?$infoMaestro->nombre_kit:'');
      $this->template->setVariable('descripcion_kit', isset($infoMaestro->descripcion)?$infoMaestro->descripcion:'');
      $this->template->setVariable('id_kit', $arreglo['idKit']);
      $this->template->setVariable('ocultar_codigo', 'none');
      $this->template->setVariable('id_referencia', isset($infoMaestro->id_referencia)?$infoMaestro->id_referencia:'');
            
      $referencia = new Referencias();
      $referencia->recover($infoMaestro->id_referencia, 'codigo');
            
      $ucomercial = $this->datos->armSelect($lista_ucomercial,'Seleccione...', $referencia->unidad_venta);
      $pventa = $this->datos->armSelect($lista_pventa,'Seleccione...', $referencia->presentacion_venta);
            
      if($referencia->fecha_expira==1) { $this->template->setVariable('vence_referencia' , 'checked'); }
      if($referencia->min_stock==1) { $this->template->setVariable('minimo_stock' , 'checked'); }
      if($referencia->serial==1) { $this->template->setVariable('serial_referencia' , 'checked'); }
    } else {
      $ucomercial = $this->datos->armSelect($lista_ucomercial ,'Seleccione...');
      $pventa = $this->datos->armSelect($lista_pventa ,'Seleccione...'); 
    }
        
    $this->template->setVariable('select_u_comercial', $ucomercial);
    $this->template->setVariable('select_p_venta', $pventa);
       
    foreach($datosReferencias as $key => $value) {
      $this->template->setCurrentBlock("ROW");
      foreach($infoDetalle as $keyDet => $valueDet) {
        if($valueDet['codigo_referencia']==$value['codigo_ref']) {
          $this->template->setVariable('cantidad_ref', $valueDet['cantidad_en_kit']);
        }
      }
      $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
      $this->template->setVariable('descripcion_referencia', $value['nombre']);
      $this->template->parseCurrentBlock("ROW");
    }
    $this->template->show();
  }
            
  function verKit($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'Kits/views/tmpl/vistaKit.php' );
    $this->template->setVariable('COMODIN', '' );
    $maestro = new KitsMaestro();
    $cliente = new Clientes();
    $infoKit = $maestro->recover($arreglo['idKit']);
        
    $disponibles = $this->retornarDisponiblesKits($arreglo['idKit'], $arreglo['cliente']);
        
    $datosCliente = $cliente->recover($arreglo['cliente'],'numero_documento');
    $this->template->setVariable('documento_cliente' , isset($datosCliente->numero_documento)?$datosCliente->numero_documento:'');
    $this->template->setVariable('nombre_cliente' , isset($datosCliente->razon_social)?$datosCliente->razon_social:'');
        
    $this->template->setVariable('id', $infoKit->id );
    $this->template->setVariable('nombre_kit',     isset($infoKit->nombre_kit)?$infoKit->nombre_kit:"" );
    $this->template->setVariable('codigo_kit',     isset($infoKit->codigo_kit)?$infoKit->codigo_kit:"" );
    $this->template->setVariable('descripcion',    isset($infoKit->descripcion)?$infoKit->descripcion:"" );
    $this->template->setVariable('fecha_creacion', isset($infoKit->fecha_creacion)?$infoKit->fecha_creacion:"" );
        
    $this->template->setVariable('disponible_nal',     $disponibles->nacional );
    $this->template->setVariable('disponible_nonal',   $disponibles->no_nacional );
    $this->template->setVariable('disponible_mixta',   $disponibles->mixta );
        
    $detalle = new KitsDetalle();
    $infoDetalle = $detalle->get_listed(" id_kit = ".$arreglo['idKit']." ");
        
    $referencias = new Referencias();
    $cantDisponibleNal = 0;
    $cantDisponibleNoNal = 0;
    $primera = true;
        
    foreach($infoDetalle as $key => $value) {
      $datosReferencia = $referencias->get_listed("codigo_ref='".$value['codigo_referencia']."' AND cliente=".$arreglo['cliente']);
      if(count($datosReferencia)>0){
        $datosReferencia = (object)$datosReferencia[0];
      }
      $idReferencia = isset($datosReferencia->codigo)?$datosReferencia->codigo:"";
      $docCliente = isset($datosCliente->numero_documento)?$datosCliente->numero_documento:'';
      $disponbibles = $this->datos->disponiblesProducto($idReferencia, $docCliente);
            
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('codigo_referencia', isset($datosReferencia->codigo_ref)?$datosReferencia->codigo_ref:"");
      $this->template->setVariable('descripcion_referencia', isset($datosReferencia->nombre)?$datosReferencia->nombre:"");
      $this->template->setVariable('cantidad',               isset($value['cantidad_en_kit'])?$value['cantidad_en_kit']:"");
      $this->template->setVariable('diponible_nacional',     isset($disponbibles->cantidad_naci)?$disponbibles->cantidad_naci:0 );
      $this->template->setVariable('diponible_no_nacional',  isset($disponbibles->cantidad_nonac)?$disponbibles->cantidad_nonac:0 );
      $this->template->parseCurrentBlock("ROW");
    }
    $this->template->show();
  }
    
  function alistarKit($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'Kits/views/tmpl/alistamientoKits.php' );
    $this->template->setVariable('COMODIN', '' );
        
    $lista_unidades = $this->datos->build_list("tipos_embalaje", "codigo", "nombre");
    $unidadesEmpaque = $this->datos->armSelect($lista_unidades ,'Seleccione Unidad...');
        
    $cliente = new Clientes();
    $datosCliente = $cliente->recover($arreglo['cliente'],'numero_documento');
    $this->template->setVariable('documento_cliente' , isset($datosCliente->numero_documento)?$datosCliente->numero_documento:'');
    $this->template->setVariable('nombre_cliente' , isset($datosCliente->razon_social)?$datosCliente->razon_social:'');
        
    $maestro = new KitsMaestro();
    $infoKit = $maestro->recover($arreglo['idKit']);
    $this->template->setVariable('nombre_kit', isset($infoKit->nombre_kit)?$infoKit->nombre_kit:"");
    $this->template->setVariable('codigo_kit', isset($infoKit->codigo_kit)?$infoKit->codigo_kit:"");
        
    $disponibles = $this->retornarDisponiblesKits($arreglo['idKit'], $arreglo['cliente']);
    $this->template->setVariable('disponible_nal', $disponibles->nacional);
    $this->template->setVariable('disponible_nonal', $disponibles->no_nacional);
    $this->template->setVariable('disponible_mixto', $disponibles->mixta);
    $this->template->setVariable('id_kit', $arreglo['idKit']);
    $this->template->setVariable('doc_cliente', $arreglo['cliente']);
    $this->template->setVariable('unidades_empaque', $unidadesEmpaque);
    $this->template->show();
  }
    
  function generarAlistamiento($arreglo) {
    $invMaestro = new InventarioMaestroMovimientos();
    $_POST = array();
    $_POST['fecha'] = date('Y-m-d');
    recuperar_Post($invMaestro);
    $codigoMaestro = $invMaestro->save();
        
    /*$invEntradas = new InventarioEntradas();
    $_POST = array();
    $_POST['fecha'] = date('Y-m-d');
    recuperar_Post($invEntradas);
    $codigoNuevaEntrada = $invEntradas->save();*/
        
    $detalle = new KitsDetalle();
    $infoDetalle = $detalle->get_listed(" id_kit = ".$arreglo['id_kit']);
    $referencias = new Referencias();
        
    if($arreglo['radio_mercancia']==="nal") {
      $this->alistarNacional($arreglo, $infoDetalle, $referencias, $codigoMaestro, $codigoNuevaEntrada);
    } else if($arreglo['radio_mercancia']==="nonal") {
      $this->alistarNoNacional($arreglo, $infoDetalle, $referencias, $codigoMaestro, $codigoNuevaEntrada);
    } else if($arreglo['radio_mercancia']==="mixta") {
      $this->alistarMixta($arreglo, $infoDetalle, $referencias, $codigoMaestro, $codigoNuevaEntrada);
    }
    $this->mostrarKitsAlistados($codigoMaestro);
  }
    
  function armarArregloEncabezado($arreglo) {
    $arregloMaestro = array();   
    $maestro = new KitsMaestro();
    $infoKit = $maestro->recover($arreglo['id_kit']);
        
    $arregloMaestro['fecha'] = date('Y-m-d');
    $arregloMaestro['fmm'] = $arreglo['fmm'];
    $arregloMaestro['unidad'] = $arreglo['unidad_empaque'];
    $arregloMaestro['obs'] = $arreglo['observaciones'];
    $arregloMaestro['tip_movimiento'] = 10;
    $arregloMaestro['producto'] = isset($infoKit->id_referencia)?$infoKit->id_referencia:50;
    $arregloMaestro['valor'] = 0;
    $arregloMaestro['peso'] = 0;
    return $arregloMaestro;
  }
    
  function alistarNacional($arreglo, $infoDetalle, $referencias, $codigoMaestro, $codigoNuevaEntrada) {
    $arregloMaestro = $this->armarArregloEncabezado($arreglo);
    $arregloMaestro['cantidad'] = $arreglo['cantidad_nacional'];
    $arregloMaestro['cantidad_nac'] = $arreglo['cantidad_nacional'];
        
    foreach($infoDetalle as $value) {
      $cantidad = $arreglo['cantidad_nacional']*$value['cantidad_en_kit'];
      $datosReferencia = $referencias->get_listed("codigo_ref='".$value['codigo_referencia']."' AND cliente=".$arreglo['doc_cliente']);
      if(count($datosReferencia)>0) {
        $datosReferencia = (object)$datosReferencia[0];
      }
      $idReferencia = isset($datosReferencia->codigo)?$datosReferencia->codigo:"";
      $disponbiblesRetirar = $this->datos->disponiblesRetirar($idReferencia, $arreglo['doc_cliente']);
            
      foreach($disponbiblesRetirar as $valueDisponibles) {
        $arregloRetirar = array();
        $arregloRetirar['inventario_entrada'] = $valueDisponibles['inventario_entrada'];
        $arregloRetirar['fecha'] = date('Y-m-d');
        $arregloRetirar['tipo_movimiento'] = 10;
        $arregloRetirar['cod_maestro'] = $codigoMaestro;
                
        if($valueDisponibles['cantidad_nacional']>0) {
          if($valueDisponibles['cantidad_nacional']<=$cantidad) {
            $arregloRetirar['peso_naci'] = $valueDisponibles['peso_nacional']*-1;
            $arregloRetirar['cantidad_naci'] = $valueDisponibles['cantidad_nacional']*-1;
            $arregloRetirar['cif'] = $valueDisponibles['cif']*-1;
            $cantidad -= $valueDisponibles['cantidad_nacional'];
          } else {
            $arregloRetirar['peso_naci'] = (($cantidad*$valueDisponibles['peso_nacional'])/($valueDisponibles['cantidad_nacional']))*-1;
            $arregloRetirar['cantidad_naci'] = $cantidad*-1;
            $arregloRetirar['cif'] = (($cantidad*$valueDisponibles['cif'])/($valueDisponibles['cantidad_nacional']))*-1;
            $cantidad = 0;
          }
                    
          $arregloMaestro['valor'] += abs($arregloRetirar['cif']);
          $arregloMaestro['peso'] += abs($arregloRetirar['peso_naci']);
          $inv_entrada = $valueDisponibles['inventario_entrada'];
          $ordenAsignar = $this->datos->retornarOrdenKit($valueDisponibles['inventario_entrada']);
                    
          $inventarioMov = new InventarioMovimientos();
          $_POST = $arregloRetirar;
          recuperar_Post($inventarioMov);
          $inventarioMov->save();
                    
          /*$_POST['tipo_movimiento'] = 10;
          $_POST['peso_naci'] = abs($_POST['peso_naci']);
          $_POST['cantidad_naci'] = abs($_POST['cantidad_naci']);
          $_POST['cif'] = abs($_POST['cif']);
          recuperar_Post($inventarioMov);
          $inventarioMov->save();*/
        }
        if($cantidad==0){ break; }
      }
    }
    $arregloMaestro['orden'] = isset($ordenAsignar->orden)?$ordenAsignar->orden:"";
    $arregloMaestro['arribo'] = isset($ordenAsignar->arribo)?$ordenAsignar->arribo:0;
        
    $invMaestro = new InventarioMaestroMovimientos();
    $_POST = $arregloMaestro;
    recuperar_Post($invMaestro);
    $codigoMaestro = $invMaestro->save($codigoMaestro, 'codigo');
        
    /*$arregloIngreso = array();
    $arregloIngreso['inventario_entrada'] = $codigoNuevaEntrada;
    $arregloIngreso['fecha'] = date('Y-m-d');
    $arregloIngreso['tipo_movimiento'] = 1;
    $arregloIngreso['cod_maestro'] = $codigoMaestro;
    $arregloIngreso['peso_naci'] = $arregloMaestro['peso'];
    $arregloIngreso['cantidad_naci'] = $arregloMaestro['cantidad'];
    $arregloIngreso['cif'] = $arregloMaestro['valor'];
        
    $inventarioMov = new InventarioMovimientos();
    $_POST = $arregloIngreso;
    recuperar_Post($inventarioMov);
    $inventarioMov->save($codigoNuevaEntrada,'inventario_entrada');
        
    $this->editarNuevoIngreso($arregloMaestro, $codigoNuevaEntrada);*/
  }
    
  function alistarNoNacional($arreglo, $infoDetalle, $referencias, $codigoMaestro, $codigoNuevaEntrada) {
    $arregloMaestro = $this->armarArregloEncabezado($arreglo);
    $arregloMaestro['cantidad'] = $arreglo['cantidad_no_nacional'];
    $arregloMaestro['cantidad_ext'] = $arreglo['cantidad_no_nacional'];
    //$inventarioMov = new InventarioMovimientos();
        
    foreach($infoDetalle as $value) {
      $cantidad = $arreglo['cantidad_no_nacional']*$value['cantidad_en_kit'];
            
      $datosReferencia = $referencias->get_listed("codigo_ref='".$value['codigo_referencia']."' AND cliente=".$arreglo['doc_cliente']);
      if(count($datosReferencia)>0) {
        $datosReferencia=(object)$datosReferencia[0];
      }
      $idReferencia = isset($datosReferencia->codigo)?$datosReferencia->codigo:"";
      $disponbiblesRetirar = $this->datos->disponiblesRetirar($idReferencia, $arreglo['doc_cliente']);
            
      foreach($disponbiblesRetirar as $valueDisponibles) {
        $arregloRetirar = array();
        $arregloRetirar['inventario_entrada'] = $valueDisponibles['inventario_entrada'];
        $arregloRetirar['fecha'] = date('Y-m-d');
        $arregloRetirar['tipo_movimiento'] = 10;
        $arregloRetirar['cod_maestro'] = $codigoMaestro;
                
        if($valueDisponibles['cantidad_no_nacional']>0) {
          if($valueDisponibles['cantidad_no_nacional']<=$cantidad) {
            $arregloRetirar['peso_nonac'] = $valueDisponibles['peso_no_nacional']*-1;
            $arregloRetirar['cantidad_nonac'] = $valueDisponibles['cantidad_no_nacional']*-1;
            $arregloRetirar['fob_nonac'] = $valueDisponibles['fob_nonac']*-1;
            $cantidad -= $valueDisponibles['cantidad_no_nacional'];
          } else {
            $arregloRetirar['peso_nonac'] = (($cantidad*$valueDisponibles['peso_no_nacional'])/($valueDisponibles['cantidad_no_nacional']))*-1;
            $arregloRetirar['cantidad_nonac'] = $cantidad*-1;
            $arregloRetirar['fob_nonac'] = (($cantidad*$valueDisponibles['fob_nonac'])/($valueDisponibles['cantidad_no_nacional']))*-1;
            $cantidad = 0;
          }
                    
          $arregloMaestro['valor'] += abs($arregloRetirar['fob_nonac']);
          $arregloMaestro['peso'] += abs($arregloRetirar['peso_nonac']);
          $inv_entrada=$valueDisponibles['inventario_entrada'];
          $ordenAsignar = $this->datos->retornarOrdenKit($valueDisponibles['inventario_entrada']);
                    
          $inventarioMov = new InventarioMovimientos();
          $_POST = $arregloRetirar;
          recuperar_Post($inventarioMov);
          $inventarioMov->save();
                    
          /*$_POST['tipo_movimiento'] = 10;
          $_POST['peso_nonac'] = abs($_POST['peso_nonac']);
          $_POST['cantidad_nonac'] = abs($_POST['cantidad_nonac']);
          $_POST['fob_nonac'] = abs($_POST['fob_nonac']);
          recuperar_Post($inventarioMov);
          $inventarioMov->save();*/
        }
        if($cantidad==0) { break; }
      }
    }
    $arregloMaestro['orden'] = isset($ordenAsignar->orden)?$ordenAsignar->orden:"";
    $arregloMaestro['arribo'] = isset($ordenAsignar->arribo)?$ordenAsignar->arribo:0;
        
    $invMaestro = new InventarioMaestroMovimientos();
    $_POST = $arregloMaestro;
    recuperar_Post($invMaestro);
    $codigoMaestro = $invMaestro->save($codigoMaestro, 'codigo');
        
    /*$arregloIngreso = array();
    $arregloIngreso['inventario_entrada'] = $codigoNuevaEntrada;
    $arregloIngreso['fecha'] = date('Y-m-d');
    $arregloIngreso['tipo_movimiento'] = 1;
    $arregloIngreso['cod_maestro'] = $codigoMaestro;
    $arregloIngreso['peso_nonac'] = $arregloMaestro['peso'];
    $arregloIngreso['cantidad_nonac'] = $arregloMaestro['cantidad'];
    $arregloIngreso['fob_nonac'] = $arregloMaestro['valor'];
        
    $inventarioMov = new InventarioMovimientos();
    $_POST = $arregloIngreso;
    recuperar_Post($inventarioMov);
    $inventarioMov->save($codigoNuevaEntrada,'inventario_entrada');
        
    $this->editarNuevoIngreso($arregloMaestro, $codigoNuevaEntrada);*/
  }

  function alistarMixta($arreglo, $infoDetalle, $referencias, $codigoMaestro, $codigoNuevaEntrada) {
    $arregloMaestro = $this->armarArregloEncabezado($arreglo);
    $arregloMaestro['cantidad'] = $arreglo['cantidad_mixto'];
    $arregloMaestro['cantidad_ext'] = $arreglo['cantidad_mixto'];
        
    foreach($infoDetalle as $value) {
      $cantidad = $arreglo['cantidad_mixto']*$value['cantidad_en_kit'];
            
      $datosReferencia = $referencias->get_listed("codigo_ref=".$value['codigo_referencia']." AND cliente=".$arreglo['doc_cliente']);
      if(count($datosReferencia)>0) {
        $datosReferencia=(object)$datosReferencia[0];
      }
      $idReferencia = isset($datosReferencia->codigo)?$datosReferencia->codigo:"";
      $disponbiblesRetirar = $this->datos->disponiblesRetirar($idReferencia, $arreglo['doc_cliente']);
            
      foreach($disponbiblesRetirar as $valueDisponibles) {
        $arregloRetirar = array();
        $arregloRetirar['inventario_entrada'] = $valueDisponibles['inventario_entrada'];
        $arregloRetirar['fecha'] = date('Y-m-d');
        $arregloRetirar['tipo_movimiento'] = 10;
        $arregloRetirar['cod_maestro'] = $codigoMaestro;
                
        if($valueDisponibles['cantidad_no_nacional']>0) {
          if($valueDisponibles['cantidad_no_nacional']<=$cantidad) {
            $arregloRetirar['peso_nonac'] = $valueDisponibles['peso_no_nacional']*-1;
            $arregloRetirar['cantidad_nonac'] = $valueDisponibles['cantidad_no_nacional']*-1;
            $arregloRetirar['fob_nonac'] = $valueDisponibles['fob_nonac']*-1;
            $cantidad -= $valueDisponibles['cantidad_no_nacional'];
          } else {
            $arregloRetirar['peso_nonac'] = (($cantidad*$valueDisponibles['peso_no_nacional'])/($valueDisponibles['cantidad_no_nacional']))*-1;
            $arregloRetirar['cantidad_nonac'] = $cantidad*-1;
            $arregloRetirar['fob_nonac'] = (($cantidad*$valueDisponibles['fob_nonac'])/($valueDisponibles['cantidad_no_nacional']))*-1;
            $cantidad = 0;
          }
                    
          $arregloMaestro['valor'] += abs($arregloRetirar['fob_nonac']);
          $arregloMaestro['peso'] += abs($arregloRetirar['peso_nonac']);
          $inv_entrada = $valueDisponibles['inventario_entrada'];
          $ordenAsignar = $this->datos->retornarOrdenKit($valueDisponibles['inventario_entrada']);
                    
          $inventarioMov = new InventarioMovimientos();
          $_POST = $arregloRetirar;
          recuperar_Post($inventarioMov);
          $inventarioMov->save();
                    
          /*$_POST['tipo_movimiento'] = 10;
          $_POST['peso_nonac'] = abs($_POST['peso_nonac']);
          $_POST['cantidad_nonac'] = abs($_POST['cantidad_nonac']);
          $_POST['fob_nonac'] = abs($_POST['fob_nonac']);
          recuperar_Post($inventarioMov);
          $inventarioMov->save();*/
        }
        if($cantidad==0) { break; }
      }
            
      if($cantidad>0) {
        foreach($disponbiblesRetirar as $valueDisponibles) {
          $arregloRetirar = array();
          $arregloRetirar['inventario_entrada'] = $valueDisponibles['inventario_entrada'];
          $arregloRetirar['fecha'] = date('Y-m-d');
          $arregloRetirar['tipo_movimiento'] = 10;
          $arregloRetirar['cod_maestro'] = $codigoMaestro;

          if($valueDisponibles['cantidad_nacional']>0) {
            if($valueDisponibles['cantidad_nacional']<=$cantidad) {
              $arregloRetirar['peso_naci'] = $valueDisponibles['peso_nacional']*-1;
              $arregloRetirar['cantidad_naci'] = $valueDisponibles['cantidad_nacional']*-1;
              $arregloRetirar['cif'] = $valueDisponibles['cif']*-1;
              $cantidad -= $valueDisponibles['cantidad_nacional'];
            } else {
              $arregloRetirar['peso_naci'] = (($cantidad*$valueDisponibles['peso_nacional'])/($valueDisponibles['cantidad_nacional']))*-1;
              $arregloRetirar['cantidad_naci'] = $cantidad*-1;
              $arregloRetirar['cif'] = (($cantidad*$valueDisponibles['cif'])/($valueDisponibles['cantidad_nacional']))*-1;
              $cantidad = 0;
            }

            $arregloMaestro['valor'] += abs($arregloRetirar['cif']);
            $arregloMaestro['peso'] += abs($arregloRetirar['peso_naci']);
            $inv_entrada = $valueDisponibles['inventario_entrada'];
            $ordenAsignar = $this->datos->retornarOrdenKit($valueDisponibles['inventario_entrada']);

            $inventarioMov = new InventarioMovimientos();
            $_POST = $arregloRetirar;
            recuperar_Post($inventarioMov);
            $inventarioMov->save();
                        
            /*$_POST['tipo_movimiento'] = 10;
            $_POST['peso_naci'] = abs($_POST['peso_naci']);
            $_POST['cantidad_naci'] = abs($_POST['cantidad_naci']);
            $_POST['cif'] = abs($_POST['cif']);
            recuperar_Post($inventarioMov);
            $inventarioMov->save();*/
          }
          if($cantidad==0) { break; }
        }
      }
    }
    $arregloMaestro['orden'] = isset($ordenAsignar->orden)?$ordenAsignar->orden:"";
    $arregloMaestro['arribo'] = isset($ordenAsignar->arribo)?$ordenAsignar->arribo:0;
        
    $invMaestro = new InventarioMaestroMovimientos();
    $_POST = $arregloMaestro;
    recuperar_Post($invMaestro);
    $codigoMaestro = $invMaestro->save($codigoMaestro, 'codigo');
        
    /*$arregloIngreso = array();
    $arregloIngreso['inventario_entrada'] = $codigoNuevaEntrada;
    $arregloIngreso['fecha'] = date('Y-m-d');
    $arregloIngreso['tipo_movimiento'] = 1;
    $arregloIngreso['cod_maestro'] = $codigoMaestro;
    $arregloIngreso['peso_nonac'] = $arregloMaestro['peso'];
    $arregloIngreso['cantidad_nonac'] = $arregloMaestro['cantidad'];
    $arregloIngreso['fob_nonac'] = $arregloMaestro['valor'];
        
    $inventarioMov = new InventarioMovimientos();
    $_POST = $arregloIngreso;
    recuperar_Post($inventarioMov);
    $inventarioMov->save($codigoNuevaEntrada,'inventario_entrada');
        
    $this->editarNuevoIngreso($arregloMaestro, $codigoNuevaEntrada);*/
  }
    
  function mostrarKitsAlistados($idRegistro) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'Kits/views/tmpl/detalleKitAlistado.php' );
    $this->template->setVariable('COMODIN', '');
        
    $datosMaestro = $this->datos->retornarMaestroAlistamiento($idRegistro);
        
    $this->template->setVariable('mostrar_botones', 'block');
    $this->template->setVariable('mostrar_mensaje', 'none');
    $this->template->setVariable('n',1);
    if($datosMaestro->cierre==1) {
      $this->template->setVariable('mostrar_botones', 'none');
      $this->template->setVariable('mostrar_mensaje', 'block');
      $this->template->setVariable('n',2);
    }

    $this->template->setVariable('tipo_mercancia', $arreglo['tipo_mercancia']);
        
    $this->template->setVariable('razon_social', $datosMaestro->razon_social);
    $this->template->setVariable('numero_documento', $datosMaestro->numero_documento);
    $this->template->setVariable('codigo_operacion', $datosMaestro->codigo);
    $this->template->setVariable('fecha', $datosMaestro->fecha);
    $this->template->setVariable('fmm', $datosMaestro->fmm);
    $this->template->setVariable('codigo_kit', $datosMaestro->codigo_kit);
    $this->template->setVariable('producto', $datosMaestro->nombre_kit);
    $this->template->setVariable('orden', $datosMaestro->orden);
    $this->template->setVariable('unidad', $datosMaestro->nombre_unidad_empaque);
    $this->template->setVariable('cantidad', number_format($datosMaestro->cantidad,2,".",","));
    $this->template->setVariable('cantidad_nac', number_format($datosMaestro->cantidad_nac,2,".",","));
    $this->template->setVariable('cantidad_ext', number_format($datosMaestro->cantidad_ext,2,".",","));
    $this->template->setVariable('observaciones', $datosMaestro->obs);
    //$this->template->setVariable('observaciones', $datosMaestro->obs);
        
    $detalleAlistamiento=$this->datos->retornarDetalleAlistamiento($idRegistro);
        
    foreach($detalleAlistamiento as $valueDetalle) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('orden_detalle', $valueDetalle['orden']);
      $this->template->setVariable('arribo', $valueDetalle['arribo']);
      $this->template->setVariable('codigo_referen', $valueDetalle['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $valueDetalle['nombre_referencia']);            
      $this->template->setVariable('fecha_detalle', $valueDetalle['fecha']);
      $this->template->setVariable('nombre_ubicacion', isset($valueDetalle['nombre_ubicacion'])?$valueDetalle['nombre_ubicacion']:'POR ASIGNAR');
      $this->template->setVariable('cantidad_nacional', number_format(abs($valueDetalle['cantidad_naci']),2,".",","));
      $this->template->setVariable('peso_nacional', number_format(abs($valueDetalle['peso_naci']),2,".",","));
      $this->template->setVariable('valor_cif', number_format(abs($valueDetalle['cif']),2,'.',','));
      $this->template->setVariable('cantidad_extranjera', number_format(abs($valueDetalle['cantidad_nonac']),2,".",","));
      $this->template->setVariable('peso_extranjera', number_format(abs($valueDetalle['peso_nonac']),2,".",","));
      $this->template->setVariable('valor_fob', number_format(abs($valueDetalle['fob_nonac']),2,'.',','));
      $this->template->parseCurrentBlock("ROW");
    }
        
    $this->template->show();
  }
    
  function editarNuevoIngreso($arregloMaestro, $codigoNuevaEntrada) {
    $arregloNuevoIngreso = array();
    $arregloNuevoIngreso['arribo'] = $arregloMaestro['arribo'];
    $arregloNuevoIngreso['orden'] = $arregloMaestro['orden'];
    $arregloNuevoIngreso['referencia'] = $arregloMaestro['producto'];
    $arregloNuevoIngreso['cantidad'] = $arregloMaestro['cantidad'];
    $arregloNuevoIngreso['peso'] = $arregloMaestro['peso'];
    $arregloNuevoIngreso['valor'] = $arregloMaestro['valor'];
    $arregloNuevoIngreso['fmm'] = $arregloMaestro['fmm'];
    $arregloNuevoIngreso['un_empaque'] = $arregloMaestro['unidad'];
    $arregloNuevoIngreso['observacion'] = $arregloMaestro['obs'];
        
    $editInvEntradas = new InventarioEntradas();
    $_POST = $arregloNuevoIngreso;
    recuperar_Post($editInvEntradas);
    $editInvEntradas->save($codigoNuevaEntrada, 'codigo');
  }
  
  function mostrarPackingList($idRegistro) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'Kits/views/tmpl/detallePackingList.php' );
    $this->template->setVariable('COMODIN', '' );
    
    $datosMaestro = $this->datos->retornarMaestroAlistamiento($idRegistro);
    
    $this->template->setVariable('mostrar_botones', 'block');
    $this->template->setVariable('mostrar_mensaje', 'none');
    if($datosMaestro->cierre==1) {
      $this->template->setVariable('mostrar_botones', 'none');
      $this->template->setVariable('mostrar_mensaje', 'block');
    }
    
    $this->template->setVariable('razon_social', $datosMaestro->razon_social);
    $this->template->setVariable('numero_documento', $datosMaestro->numero_documento);
    $this->template->setVariable('codigo_operacion', $datosMaestro->codigo);
    $this->template->setVariable('fecha', $datosMaestro->fecha);
    $this->template->setVariable('fmm', $datosMaestro->fmm);
    $this->template->setVariable('producto', $datosMaestro->nombre_kit);
    $this->template->setVariable('orden', $datosMaestro->orden);
    $this->template->setVariable('unidad', $datosMaestro->nombre_unidad_empaque);
    $this->template->setVariable('pedido', $datosMaestro->pedido);
    $this->template->setVariable('cantidad', number_format($datosMaestro->cantidad,2,".",","));
    $this->template->setVariable('cantidad_nac', number_format($datosMaestro->cantidad_nac,2,".",","));
    $this->template->setVariable('cantidad_ext', number_format($datosMaestro->cantidad_ext,2,".",","));
    $this->template->setVariable('observaciones', $datosMaestro->obs);
    
    $detalleAlistamiento = $this->datos->retornarDetalleAlistamiento($idRegistro);
    
    //Inicializa las variables que registran los totales
    $tot_piezas_naci = $tot_peso_naci = $tot_valor_cif = 0;
    $tot_piezas_ext = $tot_peso_ext = $tot_valor_fob = 0;

    foreach($detalleAlistamiento as $valueDetalle) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('orden_detalle', $valueDetalle['orden']);
      $this->template->setVariable('arribo', $valueDetalle['arribo']);
      $this->template->setVariable('codigo_referen', $valueDetalle['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $valueDetalle['nombre_referencia']);
      $this->template->setVariable('fecha_detalle', $valueDetalle['fecha']);
      $this->template->setVariable('nombre_ubicacion', isset($valueDetalle['nombre_ubicacion'])?$valueDetalle['nombre_ubicacion']:'POR ASIGNAR');
      $this->template->setVariable('modelo', !empty($valueDetalle['modelo'])?$valueDetalle['modelo']:'POR ASIGNAR');    
      $this->template->setVariable('cantidad_nacional', number_format(abs($valueDetalle['cantidad_naci']),2,".",","));
      $this->template->setVariable('peso_nacional', number_format(abs($valueDetalle['peso_naci']),2,".",","));
      $this->template->setVariable('valor_cif', number_format(abs($valueDetalle['cif']),2,".",","));
      $this->template->setVariable('cantidad_extranjera', number_format(abs($valueDetalle['cantidad_nonac']),2,".",","));
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
} 
?>