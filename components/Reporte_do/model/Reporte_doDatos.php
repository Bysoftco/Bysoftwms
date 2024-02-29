<?php
require_once(CLASSES_PATH . 'BDControlador.php');

class Reporte_doDatos extends BDControlador {
  function Reporte_doDatos() {
    parent :: Manejador_BD();
  }

  function timequery() {
    static $querytime_begin;
    list($usec, $sec) = explode(' ', microtime());

    if (!isset($querytime_begin)) {
      $querytime_begin = ((float) $usec + (float) $sec);
    } else {
      $querytime = (((float) $usec + (float) $sec)) - $querytime_begin;
      echo sprintf('<br />La consulta tardó %01.5f segundos.- <br />', $querytime);
    }
  }

  function retornarInfoOrdenes($arreglo) {
    $filtroDoc = $filtroDo = $filtroDt = $filtroNm = $filtroFd = $filtroFh = "";
        
    if(isset($arreglo["docCliente"]) && !empty($arreglo["docCliente"])) {
      $filtroDoc.=" c.numero_documento='".$arreglo["docCliente"]."' AND ";
    }
    if(isset($arreglo["do"]) && !empty($arreglo["do"])) {
      $filtroDo.=" AND da.do_asignado ='".$arreglo["do"]."'";
    }
    if(isset($arreglo["doc_transporte"]) && !empty($arreglo["doc_transporte"])) {
      $filtroDt.=" AND da.doc_tte ='".$arreglo["doc_transporte"]."'";
    }
    if(isset($arreglo["modelo"]) && !empty($arreglo["modelo"])) {
      $filtroNm.=" AND ie.modelo ='".$arreglo["modelo"]."'";
    }
    if(isset($arreglo["fecha_desde"]) && !empty($arreglo["fecha_desde"])) {
      $filtroFd.=" AND da.fecha >='".$arreglo["fecha_desde"]."'";
    }
    if(isset($arreglo["fecha_hasta"]) && !empty($arreglo["fecha_hasta"])) {
      $filtroFd.=" AND da.fecha <='".$arreglo["fecha_hasta"]."'";
    }
        
    //pendiente filtrar por agencia aduanera.
        
    $db = $_SESSION['conexion'];
    $query = "SELECT da.do_asignado AS orden, da.doc_tte, c.numero_documento AS doc_cliente,
                    c.razon_social as nombre_cliente, SUM(a.cantidad) AS piezas,
                    SUM(a.peso_bruto) AS peso, ie.modelo AS modelo
              FROM do_asignados da, arribos a, inventario_entradas ie, clientes c
              WHERE $filtroDoc
                    da.por_cuenta = c.numero_documento
                $filtroDo
                $filtroDt
                $filtroFd
                $filtroFh
                $filtroNm
                AND a.arribo = ie.arribo
                AND da.do_asignado = a.orden
              GROUP BY a.orden";

    $db->query($query);
    return $db->getArray();
  }
    
  function retornarInfoOrden($arreglo) {
    $db = $_SESSION['conexion'];
    $query = "SELECT da.do_asignado as orden, da.doc_tte, c.numero_documento,
                    c.razon_social, ie.modelo AS modelo
              FROM do_asignados da, clientes c, arribos a, inventario_entradas ie
              WHERE da.por_cuenta = c.numero_documento
                AND da.do_asignado = '$arreglo[orden]'
                AND a.orden = da.do_asignado
                AND a.arribo = ie.arribo
              GROUP BY a.orden";
	
    $db->query($query);
    return $db->fetch();
  }
    
  function retornarInfoMovimientos($orden) {
    $db = $_SESSION['conexion'];
    $query = "SELECT itm.descripcion AS nombre_tipo_movimiento,
                    im.tipo_movimiento,
                    imm.codigo AS num_operacion,
                    imm.fecha AS fecha_operacion,
                    r.codigo_ref,
                    r.nombre AS nombre_ref,
                    ie.referencia,
                    imm.fmm,
                    imm.unidad,
                    imm.lev_sia,
                    c.razon_social AS nom_sia,
                    cantidad_naci,
                    cantidad_nonac,
                    (cantidad_naci+cantidad_nonac) AS piezas,
                    um.medida AS unidad_comercial
              FROM inventario_entradas ie
                INNER JOIN inventario_movimientos im ON im.tipo_movimiento <> 30 AND im.inventario_entrada = ie.codigo
                LEFT JOIN inventario_maestro_movimientos imm ON imm.codigo = im.cod_maestro
                LEFT JOIN clientes c ON c.numero_documento = imm.lev_sia
                INNER JOIN inventario_tipos_movimiento itm ON itm.codigo = im.tipo_movimiento
                INNER JOIN referencias r ON r.codigo = ie.referencia
                LEFT JOIN unidades_medida um ON um.id = r.unidad_venta
              WHERE ie.orden = '$orden'
              ORDER BY im.fecha DESC";
          
    $db->query($query);
    return $db->getArray();
  }
    
  function retornarControl($orden) {
    $db = $_SESSION['conexion'];
    $query = "SELECT cl.*,
                    cc.nombre as item_control,
                    ce.nombre as entidad
              FROM controles_legales cl,
                    controles_entidades ce,
                    controles_control cc
              WHERE cl.orden = '$orden'
                AND cl.entidad = ce.codigo
                AND cl.control = cc.codigo
              ORDER BY fecha_registro DESC";
    $db->query($query);
    return $db->fetch();
  }
  
  //Busca el Cliente Tercero
	function findClientet($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT razon_social	FROM clientes WHERE (numero_documento = '$arreglo')";
    
		$db->query($query);
    return $db->fetch();
	}
}
?>