<?php
require_once(CLASSES_PATH.'BDControlador.php');

class VencimientosModelo extends BDControlador {
  function VencimientosModelo() {
    parent :: Manejador_BD();
  }
	
  function listadoVencimientos($arreglo) {
    $db = $_SESSION['conexion'];
    $sede = $_SESSION['sede'];

    $arreglo[movimiento] = "1,2,3,10,15,30";
    $arreglo[GroupBy] = "orden,codigo_ref,modelo";
    $arreglo[having] = "HAVING (TRUNCATE(c_nal,1) > 0 OR TRUNCATE(c_ext,1) > 0) AND (TRUNCATE(cantidad,1) > 0)";
    
		//Prepara la condición del filtro
    if(!empty($arreglo[nitfe])) $arreglo[where] .= " AND do_asignados.por_cuenta='$arreglo[nitfe]'";
    if(!empty($arreglo[fechadesdefe])) $arreglo[where] .= " AND ie.fecha >= '$arreglo[fechadesdefe]'";
    if(!empty($arreglo[fechahastafe])) $arreglo[where] .= " AND ie.fecha <= '$arreglo[fechahastafe]'";
    if(!empty($arreglo[doasignadofe])) $arreglo[where] .= " AND do_asignados.do_asignado = '$arreglo[doasignadofe]'";    

    //Prepara la condición del filtro para facturados
    $query = "SELECT existencias.documento,existencias.nombre_cliente,existencias.orden,existencias.doc_tte,existencias.manifiesto,existencias.codigo_ref,
                existencias.nombre_referencia,existencias.fecha,existencias.nombre_ubicacion,existencias.cantidad,
                existencias.peso,existencias.valor,existencias.c_nal,existencias.c_ext,MAX(fm.fecha_factura) AS fult_fact,
                CASE WHEN (fm.numero_oficial != 0) AND (MAX(fm.fecha_factura) IS NOT NULL) THEN 
                  DATEDIFF(CURDATE(),MAX(fm.fecha_factura))+
                  ((CASE WHEN (DAY(existencias.fecha)+existencias.periodicidad)>30 THEN
                    (DAY(existencias.fecha)+existencias.periodicidad)-30
                  ELSE
                    DAY(existencias.fecha)+existencias.periodicidad
                  END)-DAY(MAX(fm.fecha_factura)))*-1
                ELSE DATEDIFF(CURDATE(),existencias.fecha) END AS dias,existencias.periodicidad AS periodicidad FROM facturas_maestro fm RIGHT JOIN
                (SELECT orden, doc_tte, nombre_referencia, codigo_ref, documento, fecha, modelo, nombre_cliente, periodicidad,
                  manifiesto, nombre_ubicacion,
                  ingreso, SUM(cantidad) AS cantidad, SUM(valor) AS valor, SUM(peso) AS peso, SUM(c_ext) AS c_ext, SUM(c_nal) AS c_nal FROM
                  (SELECT do_asignados.do_asignado AS orden, do_asignados.doc_tte AS doc_tte, ref.nombre AS nombre_referencia, ref.codigo_ref AS codigo_ref,
                    ref.ref_prove AS cod_referencia,ie.modelo AS modelo, ie.fecha AS fecha, clientes.razon_social AS nombre_cliente,
                    clientes.numero_documento AS documento, clientes.periodicidad AS periodicidad, do_asignados.ingreso AS ingreso, arribos.manifiesto AS manifiesto, p.nombre AS nombre_ubicacion,
                    CASE WHEN im.tipo_movimiento IN(1) AND (im.flg_control = 1) AND ((cantidad_naci!=0) OR (cantidad_nonac!=0)) THEN ie.cantidad
                    ELSE 0 END AS cantidad,
                    CASE WHEN im.tipo_movimiento IN(1) AND (im.flg_control = 1) AND ((cantidad_naci!=0) OR (cantidad_nonac!=0)) THEN ie.peso
                    ELSE 0 END AS peso,
                    CASE WHEN im.tipo_movimiento IN(1) AND (im.flg_control = 1) AND ((cantidad_naci!=0) OR (cantidad_nonac!=0)) THEN ie.valor
                    ELSE 0 END AS valor,
                    CASE WHEN im.tipo_movimiento IN(1,2,3,7,10,15,30) THEN cantidad_naci ELSE 0 END AS c_nal,
                    CASE WHEN im.tipo_movimiento IN(1,2,3,7,10,15,30) THEN cantidad_nonac ELSE 0 END AS c_ext
                  FROM inventario_movimientos im,inventario_entradas ie,arribos,do_asignados,clientes,referencias ref,posiciones p
                  WHERE im.inventario_entrada = ie.codigo AND arribos.arribo = ie.arribo AND arribos.orden = do_asignados.do_asignado
                    AND clientes.numero_documento = do_asignados.por_cuenta AND ie.referencia = ref.codigo AND p.codigo = ie.posicion
                    AND do_asignados.sede = '$sede' $arreglo[where]) AS inv
                  GROUP BY orden, cod_referencia $arreglo[having]) AS existencias ON (fm.orden = existencias.orden)
                  WHERE (1) GROUP BY existencias.orden HAVING (dias > periodicidad)";

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
}
?>