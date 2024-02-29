<?php
require_once(CLASSES_PATH.'BDControlador.php');

class ReportelevantesModelo extends BDControlador {
  function ReportelevantesModelo() {
    parent :: Manejador_BD();
  }
	
  function listadoReportelevantes($arreglo) {
    $db = $_SESSION['conexion'];
    $sede = $_SESSION['sede'];

    //Prepara la condición del filtro
    $arreglo['where'] = '';
    if(!empty($arreglo['nitrl'])) $arreglo['where'] .= " AND cl.numero_documento='$arreglo[nitrl]'";
    if(!empty($arreglo['fechadesderl'])) $arreglo['where'] .= " AND DATE(im.fecha) >= '$arreglo[fechadesderl]'";
    if(!empty($arreglo['fechahastarl'])) $arreglo['where'] .= " AND DATE(im.fecha) <= '$arreglo[fechahastarl]'";
    if(!empty($arreglo['doctterl'])) $arreglo['where'] .= " AND da.doc_tte = '$arreglo[doctterl]'";
    if(!empty($arreglo['doasignadorl'])) $arreglo['where'] .= " AND da.do_asignado = '$arreglo[doasignadorl]'";
    if(!empty($arreglo['referenciarl'])) $arreglo['where'] .= " AND rf.codigo = '$arreglo[referenciarl]'";
    if(!empty($arreglo['movimiento'])) $arreglo['where'] .= " AND im.cod_maestro = '$arreglo[movimiento]'";

    $query = "SELECT cl.numero_documento AS documento,cl.razon_social AS nombre_cliente,
                da.do_asignado AS orden,da.doc_tte,rf.codigo_ref,rf.nombre AS nombre_referencia,
                rf.ancho,im.fecha,im.cantidad_naci AS piezas,im.peso_naci AS peso,im.cif AS valor,
                id.subpartida AS partida,id.fob,id.fletes,id.arancel,id.iva,id.trm,id.num_levante
              FROM inventario_movimientos im
                INNER JOIN inventario_entradas ie ON ie.codigo = im.inventario_entrada
                INNER JOIN do_asignados da ON ie.orden = da.do_asignado
                INNER JOIN referencias rf ON ie.referencia = rf.codigo
                INNER JOIN inventario_declaraciones id ON id.codigo = im.cod_declaracion
                INNER JOIN clientes cl ON da.por_cuenta = cl.numero_documento
                INNER JOIN sedes sd ON sd.codigo = '$sede'
              WHERE tipo_movimiento = 2$arreglo[where]";

    $db->query($query);
    return $db->getArray();
  }
  
	function findCliente($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT numero_documento,razon_social,correo_electronico,v.nombre as nvendedor
						FROM clientes, vendedores v WHERE (razon_social LIKE '%$arreglo[q]%') AND (v.codigo = vendedor)";

		$db->query($query);
    return $db->getArray();
	}

  function findDocumento($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT doc_tte,do_asignado FROM do_asignados
              WHERE doc_tte LIKE '%$arreglo[q]%' AND por_cuenta = '$arreglo[cliente]'";    

		$db->query($query);
    return $db->getArray();
  }
  
  function findReferencia($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT codigo,nombre FROM referencias WHERE (nombre LIKE '%$arreglo[q]%')";

		$db->query($query);
    return $db->getArray();
	}

  
	function findClientet($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT razon_social	FROM clientes WHERE (numero_documento = '$arreglo')";
    
		$db->query($query);
    return $db->fetch();
	}
}
?>