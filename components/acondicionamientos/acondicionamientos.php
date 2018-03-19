<?php
/**
 * Description of acondicionamientos
 *
 * @author  Fredy Salom <fsalom@bysoft.us>
 * @date    10-Marzo-2018 
 */
 
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH.'acondicionamientos/views/vista.php';
require_once COMPONENTS_PATH.'acondicionamientos/model/acondicionamientos.php';
require_once(COMPONENTS_PATH.'acondicionamientos/views/tmpl/reporteExcel.php');

require_once COMPONENTS_PATH.'Entidades/Clientes.php';
require_once COMPONENTS_PATH.'Entidades/InventarioMaestroMovimientos.php';
require_once COMPONENTS_PATH.'Entidades/InventarioEntradas.php';
require_once COMPONENTS_PATH.'Entidades/InventarioMovimientos.php';

class acondicionamientos {
  var $vista;
  var $datos;

  function acondicionamientos() {
    $this->vista = new acondicionaVista();
    $this->datos = new acondicionaDatos();
  }

  function filtroCliente($arreglo) {
    $this->vista->filtroCliente();
  }
  
  function filtroRechazadas($arreglo) {
    //Carga el cuadro de lista Tipo de Rechazo - Tabla: estados_mcia
    $lista_tiporechazo = $this->datos->build_list("estados_mcia", "codigo", "nombre", "WHERE codigo>1");
    $arreglo['select_tiporechazo'] = $this->datos->armSelect($lista_tiporechazo, 'Seleccionar...', 1);
		$this->vista->filtroRechazadas($arreglo);
	}
  
  function listadoRechazadas($arreglo) {
    $arreglo['datos'] = $this->datos->listadoRechazadas($arreglo);
    
    $this->vista->listadoRechazadas($arreglo);
  }

  function imprimeListadoRechazadas($arreglo) {
    $arreglo['datos'] = $this->datos->listadoRechazadas($arreglo);
    
    $this->vista->imprimeListadoRechazadas($arreglo);
  }
  
  function exportarExcel($arreglo) {
    $arreglo['datos'] = $this->datos->listadoRechazadas($arreglo);
    
    $datosExcel = new reporteExcel();
    $datosExcel->generarExcel($arreglo);
  }
  
  function mostrarListadoReferencias($arreglo) {
    $this->vista->mostrarListadoReferencias($arreglo);
  }
  
  function listadoEtiquetar($arreglo) {
    if(!isset($arreglo['pagina']) || empty($arreglo['pagina'])) {
      $arreglo['pagina'] = 1;
    }
    $arreglo['datos'] = $this->datos->listadoEtiquetar($arreglo);
    $this->vista->listadoEtiquetar($arreglo);
  }

  function validarCliente($arreglo) {
    $cliente = new clientes();
    $datosCliente = $cliente->recover($arreglo['docCliente'], 'numero_documento');
    if(isset($datosCliente->numero_documento)) {
      echo 'valido';
    }
  }

  function acondicionarReferencias($arreglo) {
    $this->vista->acondicionarReferencias($arreglo);
  }

  function generarAcondicionamiento($arreglo) {
    $invMaestro = new InventarioMaestroMovimientos();
    recuperar_Post($invMaestro);
    $codigoMaestro = $invMaestro->save();
    
    if($arreglo['tipo_mercancia']==1) {
      $this->acondicionarNacional($arreglo, $codigoMaestro, $codigoNuevaEntrada);
    } else {
      $this->acondicionarExtranjera($arreglo, $codigoMaestro, $codigoNuevaEntrada);
    }
    $arregloEnviar['id_registro'] = $codigoMaestro;
    $arregloEnviar['tipo_mercancia'] = $arreglo['tipo_mercancia'];
    $arregloEnviar['nombre_tipo_mercancia'] = $arreglo['nombre_tipo_mercancia'];
    $this->mostrarAcondicionamiento($arregloEnviar);
  }

  function armarArregloEncabezado($arreglo) {
    $arregloMaestro = array();

    $arregloMaestro['fecha'] = $arreglo['fecha'];
    $arregloMaestro['id_camion'] = $arreglo['id_camion'];
    $arregloMaestro['tip_movimiento'] = 16;
    $arregloMaestro['destinatario'] = $arreglo['destinatario'];
    $arregloMaestro['direccion'] = $arreglo['direccion'];
    $arregloMaestro['fmm'] = $arreglo['fmm'];
    $arregloMaestro['doc_tte'] = $arreglo['doc_tte'];
    $arregloMaestro['producto'] = $arreglo['cod_referencia'];
    $arregloMaestro['unidad'] = 0;
    $arregloMaestro['valor'] = 0;
    $arregloMaestro['peso'] = 0;
    $arregloMaestro['pedido'] = $arreglo['pedido'];
    $arregloMaestro['ciudad'] = $arreglo['codigo_ciudad'];
    $arregloMaestro['obs'] = $arreglo['observaciones'];

    return $arregloMaestro;
  }

  function acondicionarNacional($arreglo, $codigoMaestro, $codigoNuevaEntrada) {
    $arregloMaestro = $this->armarArregloEncabezado($arreglo);
 
    $cantidadTotal = 0;
    foreach($arreglo['cantidad_acondicionar'] as $key => $value) {
      $cantidadTotal += $value;
      $cantidad = $value;
      $disponiblesRetirar = $this->datos->disponiblesRetirar($key, $arreglo['doc_cliente']);
      
      foreach($disponiblesRetirar as $valueDisponibles) {
        $arregloRetirar = array();
        $arregloRetirar['inventario_entrada'] = $valueDisponibles['inventario_entrada'];
        $arregloRetirar['fecha'] = date('Y-m-d H:i');
        $arregloRetirar['tipo_movimiento'] = 16;
        $arregloRetirar['cod_maestro'] = $codigoMaestro;

        if($valueDisponibles['cantidad_nacional']>0) {
          if($valueDisponibles['cantidad_nacional']<=$cantidad) {
            $arregloRetirar['peso_naci'] = $valueDisponibles['peso_nacional'] * -1;
            $arregloRetirar['cantidad_naci'] = $valueDisponibles['cantidad_nacional'] * -1;
            $arregloRetirar['cif'] = $valueDisponibles['cif'] * -1;
            $cantidad -= $valueDisponibles['cantidad_nacional'];
          } else {
            $arregloRetirar['peso_naci'] = (($cantidad*$valueDisponibles['peso_nacional'])/($valueDisponibles['cantidad_nacional'])) * -1;
            $arregloRetirar['cantidad_naci'] = $cantidad * -1;
            $arregloRetirar['cif'] = (($cantidad*$valueDisponibles['cif'])/($valueDisponibles['cantidad_nacional'])) * -1;
            $cantidad = 0;
          }
          $arregloMaestro['valor'] += abs($arregloRetirar['cif']);
          $arregloMaestro['peso'] += abs($arregloRetirar['peso_naci']);
          $inv_entrada = $valueDisponibles['inventario_entrada'];
          $ordenAsignar = $this->datos->retornarOrden($valueDisponibles['inventario_entrada']);
          
          //Guarda en inventario_movimientos la Mercancía Nacional a Acondicionar
          $inventarioMov = new InventarioMovimientos();
          $_POST = $arregloRetirar;
          recuperar_Post($inventarioMov);
          $inventarioMov->save();

          //Valida Existencia de Mercancía Nacional Rechazada
          if($arreglo['cantidad_rechazar'][$key]!=0) {
            $peso_uni = $valueDisponibles['peso_nacional'] / $valueDisponibles['cantidad_nacional'];
            $valor_uni = $valueDisponibles['cif'] / $valueDisponibles['cantidad_nacional'];
            //Registra Mercancía Nacional Rechazada
            $arregloRetirar['peso_naci'] = $arreglo['cantidad_rechazar'][$key] * $peso_uni * -1;
            $arregloRetirar['cantidad_naci'] = $arreglo['cantidad_rechazar'][$key] * -1;
            $arregloRetirar['cif'] = $arreglo['cantidad_rechazar'][$key] * $valor_uni * -1;
            
            $arregloRetirar['estado_mcia'] = $arreglo['tipo_rechazo'][$key];
            
            //Guarda en inventario_movimientos la Mercancía Nacional a Rechazar
            $inventarioMov = new InventarioMovimientos();
            $_POST = $arregloRetirar;
            recuperar_Post($inventarioMov);
            $inventarioMov->save();
          }
          
          //Valida Existencia de Mercancía Nacional Devuelta
          if($arreglo['cantidad_devueltas'][$key]!=0) {
            $peso_uni = $valueDisponibles['peso_nacional'] / $valueDisponibles['cantidad_nacional'];
            $valor_uni = $valueDisponibles['cif'] / $valueDisponibles['cantidad_nacional'];
            //Registra Mercancía Nacional Devuelta
            $arregloRetirar['peso_naci'] = $arreglo['cantidad_devueltas'][$key] * $peso_uni;
            $arregloRetirar['cantidad_naci'] = $arreglo['cantidad_devueltas'][$key];
            $arregloRetirar['cif'] = $arreglo['cantidad_devueltas'][$key] * $valor_uni;
            
            $arregloRetirar['estado_mcia'] = 0;
            
            //Guarda en inventario_movimientos la Mercancía Nacional a Devolver
            $inventarioMov = new InventarioMovimientos();
            $_POST = $arregloRetirar;
            recuperar_Post($inventarioMov);
            $inventarioMov->save();
            
            $arregloRetirar['tipo_movimiento'] = 30;
            $arregloRetirar['peso_naci'] = $arregloRetirar['peso_naci'] * -1;
            $arregloRetirar['cantidad_naci'] = $arregloRetirar['cantidad_naci'] * -1;
            $arregloRetirar['cif'] = $arregloRetirar['cif'] * -1;
            
            //Guarda en inventario_movimientos el Comodin de Devolución
            $inventarioMov = new InventarioMovimientos();
            $_POST = $arregloRetirar;
            recuperar_Post($inventarioMov);
            $inventarioMov->save();
          }
        }
        if($cantidad==0) { break; }
      }
    }
    $arregloMaestro['orden'] = isset($ordenAsignar->orden) ? $ordenAsignar->orden : "";
    $arregloMaestro['arribo'] = isset($ordenAsignar->arribo) ? $ordenAsignar->arribo : 0;
    $arregloMaestro['cantidad'] = $cantidadTotal;
    $arregloMaestro['cantidad_nac'] = $cantidadTotal;

    $invMaestro = new InventarioMaestroMovimientos();
    $_POST = $arregloMaestro;
    recuperar_Post($invMaestro);
    $codigoMaestro = $invMaestro->save($codigoMaestro, 'codigo');
  }

  function acondicionarExtranjera($arreglo, $codigoMaestro, $codigoNuevaEntrada) {
    $arregloMaestro=$this->armarArregloEncabezado($arreglo);

    $cantidadTotal = 0;
    foreach($arreglo['cantidad_acondicionar'] as $key => $value) {
      $cantidadTotal += $value;
      $cantidad = $value;
      $disponiblesRetirar = $this->datos->disponiblesRetirar($key, $arreglo['doc_cliente']);
      
      foreach($disponiblesRetirar as $valueDisponibles) {
        $arregloRetirar = array();
        $arregloRetirar['inventario_entrada'] = $valueDisponibles['inventario_entrada'];
        $arregloRetirar['fecha'] = date('Y-m-d H:i');
        $arregloRetirar['tipo_movimiento'] = 16;
        $arregloRetirar['cod_maestro'] = $codigoMaestro;
        
        if($valueDisponibles['cantidad_no_nacional']>0) {
          if($valueDisponibles['cantidad_no_nacional']<=$cantidad) {
            $arregloRetirar['peso_nonac'] = $valueDisponibles['peso_no_nacional'] * -1;
            $arregloRetirar['cantidad_nonac'] = $valueDisponibles['cantidad_no_nacional'] * -1;
            $arregloRetirar['fob_nonac'] = $valueDisponibles['fob_nonac'] * -1;
            $cantidad -= $valueDisponibles['cantidad_no_nacional'];
          } else {
            $arregloRetirar['peso_nonac'] = (($cantidad*$valueDisponibles['peso_no_nacional'])/($valueDisponibles['cantidad_no_nacional'])) * -1;
            $arregloRetirar['cantidad_nonac'] = $cantidad * -1;
            $arregloRetirar['fob_nonac'] = (($cantidad*$valueDisponibles['fob_nonac'])/($valueDisponibles['cantidad_no_nacional'])) * -1;
            $cantidad = 0;
          }
          
          $arregloMaestro['valor'] += abs($arregloRetirar['fob_nonac']);
          $arregloMaestro['peso'] += abs($arregloRetirar['peso_nonac']);
          $inv_entrada = $valueDisponibles['inventario_entrada'];
          $ordenAsignar = $this->datos->retornarOrden($valueDisponibles['inventario_entrada']);

          //Guarda en inventario_movimientos la Mercancía Extranjera a Acondicionar
          $inventarioMov = new InventarioMovimientos();
          $_POST = $arregloRetirar;
          recuperar_Post($inventarioMov);
          $inventarioMov->save();

          //Valida Existencia de Mercancía Extranjera Rechazada
          if($arreglo['cantidad_rechazar'][$key]!=0) {
            $peso_uni = $valueDisponibles['peso_no_nacional'] / $valueDisponibles['cantidad_no_nacional'];
            $valor_uni = $valueDisponibles['fob_nonac'] / $valueDisponibles['cantidad_no_nacional'];
            //Registra Mercancía Extranjera Rechazada
            $arregloRetirar['peso_nonac'] = $arreglo['cantidad_rechazar'][$key] * $peso_uni * -1;
            $arregloRetirar['cantidad_nonac'] = $arreglo['cantidad_rechazar'][$key] * -1;
            $arregloRetirar['fob_nonac'] = $arreglo['cantidad_rechazar'][$key] * $valor_uni * -1;
            
            $arregloRetirar['estado_mcia'] = $arreglo['tipo_rechazo'][$key];
            
            //Guarda en inventario_movimientos la Mercancía Extranjera a Rechazar
            $inventarioMov = new InventarioMovimientos();
            $_POST = $arregloRetirar;
            recuperar_Post($inventarioMov);
            $inventarioMov->save();
          }
          
          //Valida Existencia de Mercancía Extranjera Devuelta
          if($arreglo['cantidad_devueltas'][$key]!=0) {
            $peso_uni = $valueDisponibles['peso_no_nacional'] / $valueDisponibles['cantidad_no_nacional'];
            $valor_uni = $valueDisponibles['fob_nonac'] / $valueDisponibles['cantidad_no_nacional'];
            //Registra Mercancía Extranjera Devuelta
            $arregloRetirar['peso_nonac'] = $arreglo['cantidad_devueltas'][$key] * $peso_uni;
            $arregloRetirar['cantidad_nonac'] = $arreglo['cantidad_devueltas'][$key];
            $arregloRetirar['fob_nonac'] = $arreglo['cantidad_devueltas'][$key] * $valor_uni;
            
            $arregloRetirar['estado_mcia'] = 0;
            
            //Guarda en inventario_movimientos la Mercancía Extranjera a Devolver
            $inventarioMov = new InventarioMovimientos();
            $_POST = $arregloRetirar;
            recuperar_Post($inventarioMov);
            $inventarioMov->save();
            
            $arregloRetirar['tipo_movimiento'] = 30;
            $arregloRetirar['peso_nonac'] = $arregloRetirar['peso_nonac'] * -1;
            $arregloRetirar['cantidad_nonac'] = $arregloRetirar['cantidad_nonac'] * -1;
            $arregloRetirar['fob_nonac'] = $arregloRetirar['fob_nonac'] * -1;
            
            //Guarda en inventario_movimientos el Comodin de Devolución
            $inventarioMov = new InventarioMovimientos();
            $_POST = $arregloRetirar;
            recuperar_Post($inventarioMov);
            $inventarioMov->save();
          }
        }
        if($cantidad==0) { break; }
      }
    }
    $arregloMaestro['orden'] = isset($ordenAsignar->orden) ? $ordenAsignar->orden : "";
    $arregloMaestro['arribo'] = isset($ordenAsignar->arribo) ? $ordenAsignar->arribo : 0;
    $arregloMaestro['cantidad'] = $cantidadTotal;
    $arregloMaestro['cantidad_ext'] = $cantidadTotal;
    
    $invMaestro = new InventarioMaestroMovimientos();
    $_POST = $arregloMaestro;
    recuperar_Post($invMaestro);
    $codigoMaestro = $invMaestro->save($codigoMaestro, 'codigo');
  }

  function mostrarAcondicionamiento($arreglo) {
    $this->vista->mostrarAcondicionamiento($arreglo);
  }
  
  function mostrarEtiquetarAcondicionamiento($arreglo) {
    $this->vista->mostrarEtiquetarAcondicionamiento($arreglo);
  }
  
  function cerrarAcondicionamiento($arreglo) {
    $arreglo['id_registro'] = $arreglo['codigo_operacion'];
    $this->datos->cerrarAcondicionamiento($arreglo['codigo_operacion']);
    $this->mostrarAcondicionamiento($arreglo);
    //$this->vista->mostrarAcondicionamiento($arreglo);
  }
  
  function devolderAcondicionamiento($arreglo) {
    $nuevoIngreso = $this->datos->retornarIdNuevoIngreso($arreglo['codigo_operacion']);
    $codigoNuevaEntrada = isset($nuevoIngreso->inventario_entrada) ? $nuevoIngreso->inventario_entrada : 0;
    
    $this->datos->eliminar('inventario_entradas', 'codigo = '.$codigoNuevaEntrada);
    $this->datos->eliminar('inventario_maestro_movimientos', 'codigo = '.$arreglo['codigo_operacion']);
    $this->datos->eliminar('inventario_movimientos', 'cod_maestro = '.$arreglo['codigo_operacion']);
    
    $arreglo_Datos = array();
    $arreglo_Datos['docCliente'] = $arreglo['doc_cliente'];
    $arreglo_Datos['tipo_mercancia'] = $arreglo['tipo_mercancia'];
    $this->mostrarListadoReferencias($arreglo_Datos);
  }
  
  function mostrarDetalleAcondicionamiento($arreglo) {
    $this->vista->mostrarDetalleAcondicionamiento($arreglo['id_registro']);
  }
  
  function generarOrdenAcondicionamiento($arreglo) {
    $this->vista->generarOrdenAcondicionamiento($arreglo);
  }
    
  function mostrarEtiqueta($arreglo) {
    $arregloEnviar['id_registro'] = $arreglo['codigoMaestro'];
    $arregloEnviar['codigos'] = $arreglo['codigos'];    
    $this->vista->mostrarEtiqueta($arregloEnviar);
  }
}
?>