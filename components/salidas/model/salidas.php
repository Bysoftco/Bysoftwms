<?php
require_once(CLASSES_PATH.'BDControlador.php');

class SalidasModelo extends BDControlador {
	function SalidasModelo() {
		parent :: Manejador_BD();
	}
	
	function listadoSalidas($arreglo) {
		$db = $_SESSION['conexion'];
		$sede = $_SESSION['sede'];

		$arreglo['movimiento'] = "2,3,4,6,7,8,9,10,11,12,16,17";
		$arreglo['where'] = "";
		$arreglo['GroupBy'] = "orden,codigo_ref";
		$arreglo['having'] = "HAVING (c_ret_nal > 0 OR c_ret_ext > 0) OR (c_sptr_nal > 0 OR c_sptr_ext> 0) OR (c_prtt_nal > 0 OR c_prtt_ext > 0) OR (c_kit_nal > 0 OR c_kit_ext > 0) OR (c_rpk > 0)";

		//Prepara la condición del filtro
		if(!empty($arreglo['nitfsl']))
			$arreglo['where'] .= " AND do_asignados.por_cuenta = ".$arreglo['nitfsl'];
		if(!empty($arreglo['fechadesdefsl']))
			$arreglo['where'] .= " AND DATE(im.fecha) >= '".$arreglo['fechadesdefsl']."'";
		if(!empty($arreglo['fechahastafsl']))
			$arreglo['where'] .= " AND DATE(im.fecha) <= '".$arreglo['fechahastafsl']."'";
		if(!empty($arreglo['doasignadofsl']))
			$arreglo['where'] .= " AND do_asignados.do_asignado = '".$arreglo['doasignadofsl']."'";
		if(!empty($arreglo['docttefsl']))
			$arreglo['where'] .= " AND do_asignados.doc_tte = '".$arreglo['docttefsl']."'";
		
		$query = "SELECT MIN(ubi_nac) AS ubic, orden, doc_tte, inventario_entrada,          inventario_entrada AS item, arribo, nombre_referencia,        cod_referencia, codigo_ref, documento, destino, modelo, nombre_ubicacion, SUM(ABS(p_ret_ext)) AS p_ret_ext, SUM(ABS(p_ret_nal)) AS p_ret_nal,
								SUM(ABS(p_sptr_ext)) AS p_sptr_ext, SUM(ABS(p_sptr_nal)) AS p_sptr_nal, fmm,
								SUM(ABS(p_prtt_ext)) AS p_prtt_ext, SUM(ABS(p_prtt_nal)) AS p_prtt_nal, SUM(ABS(p_rpk)) AS p_rpk, SUM(ABS(p_kit_ext)) AS p_kit_ext, SUM(ABS(p_kit_nal)) AS p_kit_nal,
								SUM(ABS(c_ext)) AS c_ext, SUM(ABS(c_nal)) AS c_nal, SUM(ABS(c_ret_ext)) AS c_ret_ext, SUM(ABS(c_ret_nal)) AS c_ret_nal, SUM(ABS(c_sptr_ext)) AS c_sptr_ext,
								SUM(ABS(c_sptr_nal)) AS c_sptr_nal, SUM(ABS(c_prtt_ext)) AS c_prtt_ext, SUM(ABS(c_prtt_nal)) AS c_prtt_nal, SUM(ABS(c_kit_ext))	AS c_kit_ext, SUM(ABS(c_kit_nal)) AS c_kit_nal, SUM(ABS(c_rpk)) AS c_rpk, SUM(v_rpk) AS v_rpk, SUM(fob) AS fob, SUM(cif) AS cif, SUM(fob_retiro) AS fob_retiro,
								SUM(cif_retiro) AS cif_retiro, SUM(cif_proceso) AS cif_proceso, SUM(cif_ensamble) AS cif_ensamble, nombre_cliente, MAX(fmmx) AS fmmn, fecha
							FROM(SELECT po.descripcion AS ubi_nac, im.fecha AS fecha, im.tipo_movimiento, im.flg_control, ie.cantidad AS cant, do_asignados.do_asignado AS orden, do_asignados.doc_tte AS doc_tte,
								ie.arribo, ref.nombre AS nombre_referencia, ref.codigo_ref AS codigo_ref, ref.ref_prove AS cod_referencia, ie.modelo AS modelo,
								clientes.razon_social AS nombre_cliente, clientes.numero_documento AS documento, arribos.manifiesto AS manifiesto, arribos.fmm, p.nombre AS nombre_ubicacion,
								CASE WHEN im.tipo_movimiento IN(3,7,10,15,16) THEN peso_nonac ELSE 0
								END AS p_ret_ext,
								CASE WHEN im.tipo_movimiento IN(3,7,10,15,16) THEN peso_naci ELSE 0
								END AS p_ret_nal,
								CASE WHEN im.tipo_movimiento IN(8,9) THEN peso_nonac ELSE 0
								END AS p_sptr_ext,
								CASE WHEN im.tipo_movimiento IN(8,9) THEN peso_naci ELSE 0
								END AS p_sptr_nal,
								CASE WHEN im.tipo_movimiento IN(9) THEN peso_nonac ELSE 0
								END AS p_prtt_ext,
								CASE WHEN im.tipo_movimiento IN(9) THEN peso_naci ELSE 0
								END AS p_prtt_nal,
								CASE WHEN im.tipo_movimiento IN(4,5) THEN peso_nonac ELSE 0
								END AS p_rpk,
								CASE WHEN im.tipo_movimiento IN(10) THEN peso_nonac ELSE 0
								END AS p_kit_ext,
								CASE WHEN im.tipo_movimiento IN(10) THEN peso_naci ELSE 0
								END AS p_kit_nal,
								CASE WHEN im.tipo_movimiento IN($arreglo[movimiento]) THEN cantidad_naci ELSE 0
								END AS c_nal,
								CASE WHEN im.tipo_movimiento IN($arreglo[movimiento]) THEN cantidad_nonac ELSE 0
								END AS c_ext,
								CASE WHEN im.tipo_movimiento IN($arreglo[movimiento]) THEN peso_naci ELSE 0
								END AS p_nal,
								CASE WHEN im.tipo_movimiento IN($arreglo[movimiento]) THEN peso_nonac ELSE 0
								END AS p_ext,
								CASE WHEN im.tipo_movimiento IN(3,7,10,15,16) THEN cantidad_nonac ELSE 0
								END AS c_ret_ext,
								CASE WHEN im.tipo_movimiento IN(3,7,10,15,16) THEN cantidad_naci ELSE 0
								END AS c_ret_nal,
								CASE WHEN im.tipo_movimiento IN(8,9) THEN cantidad_nonac ELSE 0
								END AS c_sptr_ext,
								CASE WHEN im.tipo_movimiento IN(8,9) THEN cantidad_naci ELSE 0
								END AS c_sptr_nal,
								CASE WHEN im.tipo_movimiento IN(9) THEN cantidad_nonac ELSE 0
								END AS c_prtt_ext,
								CASE WHEN im.tipo_movimiento IN(9) THEN cantidad_naci ELSE 0
								END AS c_prtt_nal,
								CASE WHEN im.tipo_movimiento IN(4,5) AND (im.flg_control = 1) THEN cantidad_nonac ELSE 0
								END AS c_rpk,
								CASE WHEN im.tipo_movimiento IN(10) THEN cantidad_nonac ELSE 0
								END AS c_kit_ext,
								CASE WHEN im.tipo_movimiento IN(10) THEN cantidad_naci ELSE 0
								END AS c_kit_nal,
								CASE WHEN im.tipo_movimiento IN(2,3,7,8,9,10,15,16,19) THEN fob_nonac ELSE 0
								END AS fob,
								CASE WHEN im.tipo_movimiento IN(3,7,10,15,16) THEN fob_nonac ELSE 0
								END AS fob_retiro,
								CASE WHEN im.tipo_movimiento IN(2,3,10,15,16,19,30) THEN im.cif ELSE 0
								END AS cif,
								CASE WHEN im.tipo_movimiento IN(8,9) THEN im.cif ELSE 0
								END AS cif_proceso,
								CASE WHEN im.tipo_movimiento IN(9) THEN im.cif ELSE 0
								END AS cif_ensamble,
								CASE WHEN im.tipo_movimiento IN(3,7,10,15,16) THEN im.cif ELSE 0
								END AS cif_retiro,
								CASE WHEN im.tipo_movimiento IN(4,5) THEN fob_nonac ELSE 0
								END AS v_rpk,
								im.inventario_entrada, im.fmm AS fmmx, imm.destinatario AS destino
								FROM inventario_movimientos im
							LEFT JOIN posiciones po ON (po.codigo=im.ubicacion AND po.sede='$sede')
							LEFT JOIN inventario_maestro_movimientos imm ON (imm.codigo=im.cod_maestro),inventario_entradas ie,arribos,do_asignados,clientes,referencias ref,posiciones p
							WHERE im.inventario_entrada = ie.codigo
								AND arribos.arribo = ie.arribo
								AND arribos.orden = do_asignados.do_asignado
								AND clientes.numero_documento = do_asignados.por_cuenta
								AND ie.referencia = ref.codigo
								AND p.codigo = ie.posicion AND p.sede = '$sede'
								AND do_asignados.sede = '$sede'".$arreglo['where'].") AS inv
							GROUP BY $arreglo[GroupBy] $arreglo[having]"; //cod_referencia se cambio por codigo_ref

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
	
	function findClientet($arreglo) {
		$db = $_SESSION['conexion'];
		
		$query = "SELECT razon_social	FROM clientes WHERE (numero_documento = '$arreglo')";
		
		$db->query($query);
		return $db->fetch();
	}
}
?>