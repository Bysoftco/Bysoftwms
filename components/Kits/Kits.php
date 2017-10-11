<?php
/**
 * Description of Kits
 *
 * @author Teresa
 */
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'Entidades/Clientes.php';
require_once COMPONENTS_PATH . 'Kits/model/KitsDatos.php';
require_once COMPONENTS_PATH . 'Kits/views/KitsVista.php';
require_once COMPONENTS_PATH . 'Entidades/KitsMaestro.php';
require_once COMPONENTS_PATH . 'Entidades/KitsDetalle.php';
require_once COMPONENTS_PATH . 'Entidades/Referencias.php';

require_once COMPONENTS_PATH . 'Entidades/InventarioMovimientos.php';
require_once COMPONENTS_PATH . 'Entidades/InventarioMaestroMovimientos.php';
require_once COMPONENTS_PATH . 'Entidades/InventarioEntradas.php';

class Kits {  
  var $vista;
  var $datos;
            
  function Kits() {
    $this->vista = new KitsVista();
    $this->datos = new KitsDatos();
  }
    
  function filtroCliente($arreglo) {
    $this->vista->filtroClientes();
  }
    
  function mostrarInfoKits($arreglo) {
    $this->vista->mostrarInfoKits($arreglo);
  }
    
  function agregarKit($arreglo) {
    $this->vista->agregarKit($arreglo);
  }
    
  function validarCodigo($arreglo) {
    $refRepetida = $this->datos->validarCodigo($arreglo['codigo_kit'],$arreglo['cod_cliente']);
    $cantidad = count($refRepetida);
    if($cantidad>=1) {
      echo'invalido';
    } elseif($cantidad==0) {
      echo'valido';
    } else {
      echo'error';
    }
  }
    
  function guardarReferencia($arreglo) {
    $arregloRef = array($arreglo);
    if(!empty($arreglo['id_referencia'])) {
      $arregloRef['codigo']=$arreglo['id_referencia'];
    }
    $arregloRef['codigo_ref']=$arreglo['codigo_kit'];
    $arregloRef['ref_prove']=$arreglo['codigo_kit'];
    $arregloRef['nombre']=$arreglo['nombre_kit'];
    $arregloRef['observaciones']=$arreglo['descripcion'];
    $arregloRef['cliente']=$arreglo['cliente'];
    $arregloRef['unidad']=1;
    $arregloRef['unidad_venta']=$arreglo["u_comercial"];
    $arregloRef['presentacion_venta']=$arreglo["p_venta"];
    $arregloRef['fecha_expira']=isset($arreglo["vence_referencia"])?$arreglo["vence_referencia"]:0;
    $arregloRef['min_stock']=isset($arreglo["minimo_stock"])?$arreglo["minimo_stock"]:0;
    $arregloRef['lote_cosecha']='0';
    $arregloRef['serial']=isset($arreglo["serial_referencia"])?$arreglo["serial_referencia"]:0;
    $arregloRef['tipo']=25;
        
    $ref = new Referencias();
    $temp = $_POST;
    $_POST = $arregloRef;
    recuperar_Post($ref);
    if(!empty($arreglo['id_referencia'])) {
      $codReferencia = $ref->save($arreglo['id_referencia'], 'codigo');
    } else {
      $codReferencia = $ref->save();
    }
    $_POST = $temp;
        return $codReferencia;
  }
    
  function guardarKit($arreglo) {
    $codReferencia = $this->guardarReferencia($arreglo);
    $_POST['id_referencia'] = $codReferencia;
        
    $idKit = 0;
    if(!empty($arreglo['idKit'])) {
      $idKit = $arreglo['idKit'];
    }
        
    $maestro = new KitsMaestro();
    recuperar_Post($maestro);
    $idMarestro = $maestro->save($idKit);
        
    $this->datos->eliminarComposicionKits($idMarestro);
        
    foreach($arreglo['cantidad'] as $key => $value) {
      if(!empty($value)) {
        $arregloDetalle = array();
        $arregloDetalle['id_kit'] = $idMarestro;
        $arregloDetalle['codigo_referencia'] = $key;
        $arregloDetalle['cantidad_en_kit'] = $value;
        $_POST = $arregloDetalle;
        $detalle = new KitsDetalle();
        recuperar_Post($detalle);
        $detalle->save();
      }
    }
    $arreglo['docCliente'] = $arreglo['cliente'];
    $this->mostrarInfoKits($arreglo);
  } 
   
  function verKit($arreglo) {
    $this->vista->verKit($arreglo);
  }

  function alistarKit($arreglo) {
    $this->vista->alistarKit($arreglo);
  }
    
  function generarAlistamiento($arreglo) {
    $this->vista->generarAlistamiento($arreglo);
  }
    
  function mostrarKitsAlistados($arreglo) {
    $this->vista->mostrarKitsAlistados($arreglo['id_registro']);
  }
    
  function cerrarKit($arreglo) {
    $this->datos->cerrarKit($arreglo['codigo_operacion']);
    $this->vista->mostrarKitsAlistados($arreglo['codigo_operacion']);
  }
    
  function devolderAlistamiento($arreglo) {
    $nuevoIngreso = $this->datos->retornarIdNuevoIngreso($arreglo['codigo_operacion']);
    $codigoNuevaEntrada = isset($nuevoIngreso->inventario_entrada)?$nuevoIngreso->inventario_entrada:0;
        
    $this->datos->eliminar('inventario_entradas', 'codigo ='.$codigoNuevaEntrada);
    $this->datos->eliminar('inventario_maestro_movimientos', 'codigo ='.$arreglo['codigo_operacion']);
    $this->datos->eliminar('inventario_movimientos', 'cod_maestro ='.$arreglo['codigo_operacion']);
        
    $arreglo_Datos = array();
    $arreglo_Datos['docCliente'] = $arreglo['doc_cliente'];
    $this->mostrarInfoKits($arreglo_Datos);
  }

  function generarPackingList($arreglo) {
    $arregloEnviar['id_registro'] = $arreglo['codigoMaestro'];
    $this->mostrarPackingList($arregloEnviar);
  }
  
  function mostrarPackingList($arreglo) {
    $this->vista->mostrarPackingList($arreglo['id_registro']);
  }
    
  function eliminarKit($arreglo) {
    $maestro = new KitsMaestro();
    $maestro->deleted($arreglo['idKit']);
    $this->mostrarInfoKits($arreglo);
  }
    
  function validarCliente($arreglo) {
    $cliente = new clientes();
    $datosCliente = $cliente->recover($arreglo['docCliente'], 'numero_documento');
    if(isset($datosCliente->numero_documento)) {
      echo 'valido';
    }
  }
}
?>