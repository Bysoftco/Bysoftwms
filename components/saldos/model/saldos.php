<?php
require_once(CLASSES_PATH.'BDControlador.php');

class SaldosModelo extends BDControlador {
  function SaldosModelo() {
    parent :: Manejador_BD();
  }
	
  function listadoSaldos($arreglo) {
    $db = $_SESSION['conexion'];
		$sede = $_SESSION['sede'];

    $arreglo[movimiento] = "1,2,3,7,10,15,16,30";
    $arreglo[GroupBy] = "orden,codigo_ref";
    $arreglo[having] = "HAVING (TRUNCATE(c_nal,1) > 0 OR TRUNCATE(c_ext,1) > 0) AND (TRUNCATE(cantidad,1) > 0)";

		//Prepara la condición del filtro
    if(!empty($arreglo[nitfs])) $arreglo[where] .= " AND do_asignados.por_cuenta='$arreglo[nitfs]'";
    if(!empty($arreglo[fechadesdefs])) $arreglo[where] .= " AND ie.fecha >= '$arreglo[fechadesdefs]'";
    if(!empty($arreglo[fechahastafs])) $arreglo[where] .= " AND ie.fecha <= '$arreglo[fechahastafs]'";
    if(!empty($arreglo[doasignadofs])) $arreglo[where] .= " AND do_asignados.do_asignado = '$arreglo[doasignadofs]'";
    
    $query = "SELECT orden, doc_tte, inventario_entrada, inventario_entrada AS item, arribo, nombre_referencia, cod_referencia, codigo_ref, documento, fecha,
                SUM(cantidad) AS cantidad, SUM(valor) AS valor, SUM(peso) AS peso, modelo, SUM(p_ext) AS p_ext, SUM(p_nal) AS p_nal, nombre_ubicacion, fmm,
                SUM(p_ret_ext) AS p_ret_ext, SUM(p_ret_nal) AS p_ret_nal, SUM(p_sptr_ext) AS p_sptr_ext, SUM(p_sptr_nal) AS p_sptr_nal, manifiesto, ucomercial,
                SUM(p_prtt_ext) AS p_prtt_ext, SUM(p_prtt_nal) AS p_prtt_nal, SUM(p_rpk) AS p_rpk, SUM(c_kit_ext)	AS p_kit_ext, SUM(c_kit_nal) AS p_kit_nal,
                SUM(c_ext) AS c_ext, SUM(c_nal) AS c_nal, SUM(c_ret_ext) AS c_ret_ext, SUM(c_ret_nal) AS c_ret_nal, SUM(c_sptr_ext) AS c_sptr_ext,
                SUM(c_sptr_nal) AS c_sptr_nal, SUM(c_prtt_ext) AS c_prtt_ext, SUM(c_prtt_nal) AS c_prtt_nal, SUM(c_kit_ext)	AS c_kit_ext, ingreso,
                SUM(c_kit_nal) AS c_kit_nal, SUM(c_rpk) AS c_rpk, SUM(v_rpk) AS v_rpk, SUM(fob) AS fob, SUM(cif) AS cif, SUM(fob_retiro) AS fob_retiro,
                SUM(cif_retiro) AS cif_retiro, SUM(cif_proceso) AS cif_proceso, SUM(cif_ensamble) AS cif_ensamble, nombre_cliente, fecha_expira
              FROM(SELECT im.codigo, im.tipo_movimiento, im.flg_control, ie.cantidad AS cant, do_asignados.do_asignado AS orden, do_asignados.doc_tte AS doc_tte,
                ie.arribo, ref.nombre AS nombre_referencia, ref.codigo_ref AS codigo_ref, ref.ref_prove AS cod_referencia, ie.modelo AS modelo, ie.fecha AS fecha,
                ie.fmm AS fmm, ie.fecha_expira, clientes.razon_social AS nombre_cliente, clientes.numero_documento AS documento, do_asignados.ingreso AS ingreso,
                arribos.manifiesto AS manifiesto, p.nombre AS nombre_ubicacion, um.medida AS ucomercial,
                CASE WHEN im.tipo_movimiento IN(1) AND (im.flg_control = 1) AND ((cantidad_naci!=0) OR (cantidad_nonac!=0)) THEN ie.cantidad ELSE 0
                END AS cantidad,
                CASE WHEN im.tipo_movimiento IN(1) AND (im.flg_control = 1) AND ((cantidad_naci!=0) OR (cantidad_nonac!=0)) THEN ie.peso ELSE 0
                END AS peso,
                CASE WHEN im.tipo_movimiento IN(1) AND (im.flg_control = 1) AND ((cantidad_naci!=0) OR (cantidad_nonac!=0)) THEN ie.valor ELSE 0
                END AS valor,
                CASE WHEN im.tipo_movimiento IN($arreglo[movimiento]) THEN peso_nonac ELSE 0
                END AS p_ext,
                CASE WHEN im.tipo_movimiento IN($arreglo[movimiento]) THEN peso_naci ELSE 0
                END AS p_nal,
                CASE WHEN im.tipo_movimiento IN(3,7,10,15) THEN peso_nonac ELSE 0
                END AS p_ret_ext,
                CASE WHEN im.tipo_movimiento IN(3,7,10,15) THEN peso_naci ELSE 0
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
                CASE WHEN im.tipo_movimiento IN(3,7,10,15) THEN cantidad_nonac ELSE 0
                END AS c_ret_ext,
                CASE WHEN im.tipo_movimiento IN(3,7,10,15) THEN cantidad_naci ELSE 0
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
                CASE WHEN im.tipo_movimiento IN(1,2,3,7,8,9,10,15) THEN fob_nonac ELSE 0
                END AS fob,
                CASE WHEN im.tipo_movimiento IN(3,7,10,15) THEN fob_nonac ELSE 0
                END AS fob_retiro,
                CASE WHEN im.tipo_movimiento IN(2,3,10,15,30) THEN cif ELSE 0
                END AS cif,
                CASE WHEN im.tipo_movimiento IN(8,9) THEN cif ELSE 0
                END AS cif_proceso,
                CASE WHEN im.tipo_movimiento IN(9) THEN cif ELSE 0
                END AS cif_ensamble,
                CASE WHEN im.tipo_movimiento IN(3,7,10,15) THEN cif ELSE 0
                END AS cif_retiro,
                CASE WHEN im.tipo_movimiento IN(4,5) THEN fob_nonac ELSE 0
                END AS v_rpk,
                im.inventario_entrada 
                FROM inventario_movimientos im,inventario_entradas ie
                  LEFT JOIN unidades_medida um ON ie.un_empaque = um.id,
                  arribos,do_asignados,clientes,referencias ref,posiciones p
              WHERE im.inventario_entrada = ie.codigo
                AND arribos.arribo = ie.arribo
                AND arribos.orden = do_asignados.do_asignado
                AND clientes.numero_documento = do_asignados.por_cuenta
                AND ie.referencia = ref.codigo
                AND ie.posicion = p.codigo AND p.sede='$sede'
                AND do_asignados.sede = '$sede' $arreglo[where]) AS inv
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
  
	function findClientet($arregloDatos) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT razon_social	FROM clientes WHERE (numero_documento = '$arregloDatos')";
    
		$db->query($query);
    return $db->fetch();
	}
}
?>