<?php
/**
 * Description of Alistamientos
 *
 * @author  Teresa
 * @author  Fredy Salom <fsalom@bysoft.us>
 * @date    17-Marzo-2015 
 */
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'Alistamientos/views/AlistamientosVista.php';
require_once COMPONENTS_PATH . 'Alistamientos/model/AlistamientosDatos.php';

require_once COMPONENTS_PATH . 'Entidades/Clientes.php';
require_once COMPONENTS_PATH . 'Entidades/InventarioMaestroMovimientos.php';
require_once COMPONENTS_PATH . 'Entidades/InventarioEntradas.php';
require_once COMPONENTS_PATH . 'Entidades/InventarioMovimientos.php';

class Alistamientos {
  var $vista;
  var $datos;

  function Alistamientos() {
    $this->vista = new AlistamientosVista();
    $this->datos = new AlistamientosDatos();
  }

  function filtroCliente($arreglo) {
    $this->vista->filtroClientes();
  }
  
  function filtroEtiquetar($arreglo) {
		$this->vista->filtroEtiquetar($arreglo);
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

  function alistarReferencias($arreglo) {
    $this->vista->alistarReferencias($arreglo);
  }

  function generarAlistamiento($arreglo) {
    $invMaestro = new InventarioMaestroMovimientos();
    recuperar_Post($invMaestro);
    $codigoMaestro = $invMaestro->save();

    if($arreglo['tipo_mercancia']==1) {
      $this->alistarNacional($arreglo, $codigoMaestro, $codigoNuevaEntrada);
    } else if($arreglo['tipo_mercancia']==2) {
      $this->alistarExtranjera($arreglo, $codigoMaestro, $codigoNuevaEntrada);
    } else if($arreglo['tipo_mercancia']==3) {
      $this->alistarMixta($arreglo, $codigoMaestro, $codigoNuevaEntrada);
    }
    $arregloEnviar['id_registro'] = $codigoMaestro;
    $arregloEnviar['tipo_mercancia'] = $arreglo['tipo_mercancia'];
    $this->mostrarAlistamiento($arregloEnviar);
  }

  function armarArregloEncabezado($arreglo) {
    $arregloMaestro = array();

    $arregloMaestro['fecha'] = $arreglo['fecha'];
    $arregloMaestro['id_camion'] = $arreglo['id_camion'];
    $arregloMaestro['tip_movimiento'] = 15;
    $arregloMaestro['destinatario'] = $arreglo['destinatario'];
    $arregloMaestro['direccion'] = $arreglo['direccion'];
    $arregloMaestro['fmm'] = $arreglo['fmm'];
    $arregloMaestro['producto'] = 99;
    $arregloMaestro['unidad'] = 0;
    $arregloMaestro['valor'] = 0;
    $arregloMaestro['peso'] = 0;
    $arregloMaestro['pedido'] = $arreglo['pedido'];
    $arregloMaestro['ciudad'] = $arreglo['codigo_ciudad'];
    $arregloMaestro['obs'] = $arreglo['observaciones'];

    return $arregloMaestro;
  }

  function alistarNacional($arreglo, $codigoMaestro, $codigoNuevaEntrada) {
    $arregloMaestro = $this->armarArregloEncabezado($arreglo);

    $cantidadTotal = 0;
    foreach($arreglo['cantidad_retirar'] as $key => $value) {
      $cantidadTotal += $value;
      $cantidad = $value;
      $disponbiblesRetirar = $this->datos->disponiblesRetirar($key, $arreglo['doc_cliente']);

      foreach($disponbiblesRetirar as $valueDisponibles) {
        $arregloRetirar = array();
        $arregloRetirar['inventario_entrada'] = $valueDisponibles['inventario_entrada'];
        $arregloRetirar['fecha'] = date('Y-m-d H:i');
        $arregloRetirar['tipo_movimiento'] = 15;
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

          $inventarioMov = new InventarioMovimientos();
          $_POST = $arregloRetirar;
          recuperar_Post($inventarioMov);
          $inventarioMov->save();
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

  function alistarExtranjera($arreglo, $codigoMaestro, $codigoNuevaEntrada) {
    $arregloMaestro=$this->armarArregloEncabezado($arreglo);

    $cantidadTotal = 0;
    foreach($arreglo['cantidad_retirar'] as $key => $value) {
      $cantidadTotal += $value;
      $cantidad = $value;
      $disponbiblesRetirar = $this->datos->disponiblesRetirar($key, $arreglo['doc_cliente']);
      
      foreach($disponbiblesRetirar as $valueDisponibles) {
        $arregloRetirar = array();
        $arregloRetirar['inventario_entrada'] = $valueDisponibles['inventario_entrada'];
        $arregloRetirar['fecha'] = date('Y-m-d H:i');
        $arregloRetirar['tipo_movimiento'] = 15;
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
          
          $inventarioMov = new InventarioMovimientos();
          $_POST = $arregloRetirar;
          recuperar_Post($inventarioMov);
          $inventarioMov->save();
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
  
  function alistarMixta($arreglo, $codigoMaestro, $codigoNuevaEntrada) {
    $arregloMaestro = $this->armarArregloEncabezado($arreglo);
    
    $cantidadTotal = 0;
    foreach($arreglo['cantidad_retirar'] as $key => $value) {
      $cantidadTotal += $value;
      $cantidad = $value;
      $disponbiblesRetirar = $this->datos->disponiblesRetirar($key, $arreglo['doc_cliente']);
      
      foreach($disponbiblesRetirar as $valueDisponibles) {
        $arregloRetirar = array();
        $arregloRetirar['inventario_entrada'] = $valueDisponibles['inventario_entrada'];
        $arregloRetirar['fecha'] = date('Y-m-d H:i');
        $arregloRetirar['tipo_movimiento'] = 15;
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
          
          $inventarioMov = new InventarioMovimientos();
          $_POST = $arregloRetirar;
          recuperar_Post($inventarioMov);
          $inventarioMov->save();
        }
        if($cantidad==0) { break; }
      }
      
      if($cantidad>0) {
        foreach($disponbiblesRetirar as $valueDisponibles) {
          $arregloRetirar = array();
          $arregloRetirar['inventario_entrada'] = $valueDisponibles['inventario_entrada'];
          $arregloRetirar['fecha'] = date('Y-m-d H:i');
          $arregloRetirar['tipo_movimiento'] = 15;
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
            
            $inventarioMov = new InventarioMovimientos();
            $_POST = $arregloRetirar;
            recuperar_Post($inventarioMov);
            $inventarioMov->save();
          }
          if($cantidad==0) { break; }
        }
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
  
  function mostrarAlistamiento($arreglo) {
    $this->vista->mostrarAlistamiento($arreglo);
  }
  
  function mostrarEtiquetarAlistamiento($arreglo) {
    $this->vista->mostrarEtiquetarAlistamiento($arreglo);
  }
  
  function cerrarAlistamiento($arreglo) {
    $arreglo['id_registro'] = $arreglo['codigo_operacion'];
    $this->datos->cerrarAlistamiento($arreglo['codigo_operacion']);
    $this->vista->mostrarAlistamiento($arreglo);
  }
  
  function devolderAlistamiento($arreglo) {
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
  
  function mostrarDetalleAlistamiento($arreglo) {
    $this->vista->mostrarDetalleAlistamiento($arreglo['id_registro']);
  }
  
  function generarPackingList($arreglo) {
    $arregloEnviar['id_registro'] = $arreglo['codigoMaestro'];
    $this->mostrarPackingList($arregloEnviar);
  }
  
  function mostrarPackingList($arreglo) {
    $this->vista->mostrarPackingList($arreglo['id_registro']);
  }
    
  function mostrarEtiqueta($arreglo) {
    $arregloEnviar['id_registro'] = $arreglo['codigoMaestro'];
    $arregloEnviar['codigos'] = $arreglo['codigos'];    
    $this->vista->mostrarEtiqueta($arregloEnviar);
  }
}
?>