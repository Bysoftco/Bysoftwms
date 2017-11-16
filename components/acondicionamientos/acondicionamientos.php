<?php
/**
 * Description of acondicionamientos
 *
 * @author  Fredy Salom <fsalom@bysoft.us>
 * @date    10-Octubre-2017 
 */
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'acondicionamientos/views/vista.php';
require_once COMPONENTS_PATH . 'acondicionamientos/model/acondicionamientos.php';

require_once COMPONENTS_PATH . 'Entidades/Clientes.php';
require_once COMPONENTS_PATH . 'Entidades/InventarioMaestroMovimientos.php';
require_once COMPONENTS_PATH . 'Entidades/InventarioEntradas.php';
require_once COMPONENTS_PATH . 'Entidades/InventarioMovimientos.php';

class acondicionamientos {
  var $vista;
  var $datos;

  function acondicionamientos() {
    $this->vista = new acondicionaVista();
    $this->datos = new acondicionaDatos();
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
    foreach($arreglo['cantidad_retirar'] as $key => $value) {
      $cantidadTotal += $value;
      $cantidad = $value;
      $disponbiblesRetirar = $this->datos->disponiblesRetirar($key, $arreglo['doc_cliente']);

      foreach($disponbiblesRetirar as $valueDisponibles) {
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

  function acondicionarExtranjera($arreglo, $codigoMaestro, $codigoNuevaEntrada) {
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
  
  function acondicionarMixta($arreglo, $codigoMaestro, $codigoNuevaEntrada) {
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
  
  function mostrarAcondicionamiento($arreglo) {
    //Carga el cuadro de lista Tipo de Rechazo - Tabla: estados_mcia
    $lista_tiporechazo = $this->datos->build_list("estados_mcia", "codigo", "nombre");
    $arreglo['select_tiporechazo'] = $this->datos->armSelect($lista_tiporechazo, 'Seleccione Tipo Rechazo...', 1);
    $this->vista->mostrarAcondicionamiento($arreglo);
  }
  
  function mostrarEtiquetarAcondicionamiento($arreglo) {
    $this->vista->mostrarEtiquetarAcondicionamiento($arreglo);
  }
  
  function registrarAcondicionamiento($arreglo) {
    $arreglo['id_registro'] = $arreglo['codigo_operacion'];
    //Actualización cantidad acondicionada en inventario_movimientos
    $this->datos->registrarAcondicionamiento(1,$arreglo);
    //Inserción novedades de Rechazo y Devolución con estado de la mercancia en inventario_movimientos
    $this->datos->registrarAcondicionamiento(2,$arreglo);
    //Actualización cantidad acondicionada en inventario_maestro_movimientos
    $this->datos->registrarAcondicionamiento(3,$arreglo);
    $this->mostrarAcondicionamiento($arreglo);
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