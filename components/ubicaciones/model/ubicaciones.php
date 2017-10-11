<?php 
require_once(CLASSES_PATH.'BDControlador.php');

class UbicacionesModelo extends BDControlador {
  function UbicacionesModelo() {
    parent :: Manejador_BD();
  }
	
  function listadoUbicaciones($arreglo) {
    $db = $_SESSION['conexion'];
    $sede = $_SESSION['sede'];
    
    //Valida visualización de todas las ubicaciones
    if(!$arreglo[todos]) {
      $arreglo[movimiento] = "1,2,3,30";
      $arreglo[GroupBy] = "GROUP BY inv.orden, inv.codigo_ref";
      $arreglo[having] = "HAVING (TRUNCATE(c_nal,1) > 0 OR TRUNCATE(c_ext,1) > 0) AND (TRUNCATE(cantidad,1) > 0)";
      $arreglo[where] = ""; //Inicializa acumulador de requerimientos del filtro
      
      //Prepara la condición del filtro
      //Operación para no visualizar -1 en Nit
      $arreglo[nitu] = (strlen($arreglo[nitu])==1 ? -1 : $arreglo[nitu]); 
      if(!empty($arreglo[nitu])) $arreglo[where] .= " AND do_asignados.por_cuenta='$arreglo[nitu]'";
      if(!empty($arreglo[doctteu])) $arreglo[where] .= " AND do_asignados.doc_tte = '$arreglo[doctteu]'";
      if(!empty($arreglo[doasignadou])) $arreglo[where] .= " AND do_asignados.do_asignado = '$arreglo[doasignadou]'";
      if(!empty($arreglo[ubicacionu])) $arreglo[where] .= " AND ie.posicion = '$arreglo[ubicacionu]'";
      if(!empty($arreglo[referenciau])) $arreglo[where] .= " AND ie.referencia = '$arreglo[referenciau]'";
      
      //Prepara la condición del filtro
      $query = "SELECT orden, doc_tte, inventario_entrada, inventario_entrada AS item, arribo, nombre_referencia, cod_referencia, codigo_ref, documento, fecha,
                  SUM(cantidad) AS cantidad, SUM(valor) AS valor, SUM(peso) AS peso, modelo, SUM(p_ext) AS p_ext, SUM(p_nal) AS p_nal, nombre_ubicacion,
                  SUM(p_ret_ext) AS p_ret_ext, SUM(p_ret_nal) AS p_ret_nal, SUM(p_sptr_ext) AS p_sptr_ext, SUM(p_sptr_nal) AS p_sptr_nal, manifiesto,
                  SUM(p_prtt_ext) AS p_prtt_ext, SUM(p_prtt_nal) AS p_prtt_nal, SUM(p_rpk) AS p_rpk, SUM(c_kit_ext)	AS p_kit_ext, SUM(c_kit_nal) AS p_kit_nal,
                  SUM(c_ext) AS c_ext, SUM(c_nal) AS c_nal, SUM(c_ret_ext) AS c_ret_ext, SUM(c_ret_nal) AS c_ret_nal, SUM(c_sptr_ext) AS c_sptr_ext,
                  SUM(c_sptr_nal) AS c_sptr_nal, SUM(c_prtt_ext) AS c_prtt_ext, SUM(c_prtt_nal) AS c_prtt_nal, SUM(c_kit_ext)	AS c_kit_ext, ingreso,
                  SUM(c_kit_nal) AS c_kit_nal, SUM(c_rpk) AS c_rpk, SUM(v_rpk) AS v_rpk, SUM(fob) AS fob, SUM(cif) AS cif, SUM(fob_retiro) AS fob_retiro,
                  SUM(cif_retiro) AS cif_retiro, SUM(cif_proceso) AS cif_proceso, SUM(cif_ensamble) AS cif_ensamble, nombre_cliente
                FROM(SELECT im.codigo, im.tipo_movimiento, im.flg_control, ie.cantidad AS cant, do_asignados.do_asignado AS orden, do_asignados.doc_tte AS doc_tte,
                  ie.arribo, ref.nombre AS nombre_referencia, ref.codigo_ref AS codigo_ref, ref.ref_prove AS cod_referencia, ie.modelo AS modelo, ie.fecha AS fecha,
                  clientes.razon_social AS nombre_cliente, clientes.numero_documento AS documento, do_asignados.ingreso AS ingreso, arribos.manifiesto AS manifiesto,
                  p.nombre AS nombre_ubicacion,
                  CASE WHEN im.tipo_movimiento IN(1) AND (im.flg_control = 1) AND ((cantidad_naci!=0) OR (cantidad_nonac!=0)) THEN ie.cantidad ELSE 0
                  END AS cantidad,
                  CASE WHEN im.tipo_movimiento IN(1) AND (im.flg_control = 1) AND ((cantidad_naci!=0) OR (cantidad_nonac!=0)) THEN ie.peso ELSE 0
                  END AS peso,
                  CASE WHEN im.tipo_movimiento IN(1) AND (im.flg_control = 1) AND ((cantidad_naci!=0) OR (cantidad_nonac!=0)) THEN ie.valor ELSE 0
                  END AS valor,
                  CASE WHEN im.tipo_movimiento IN(1,2,30,3,7) THEN peso_nonac ELSE 0
                  END AS p_ext,
                  CASE WHEN im.tipo_movimiento IN(1,2,30,3,7) THEN peso_naci ELSE 0
                  END AS p_nal,
                  CASE WHEN im.tipo_movimiento IN(3,7) THEN peso_nonac ELSE 0
                  END AS p_ret_ext,
                  CASE WHEN im.tipo_movimiento IN(3,7) THEN peso_naci ELSE 0
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
                  CASE WHEN im.tipo_movimiento IN(1,2,30,3,7) THEN cantidad_naci ELSE 0
                  END AS c_nal,
                  CASE WHEN im.tipo_movimiento IN(1,2,30,3,7) THEN cantidad_nonac ELSE 0
                  END AS c_ext,
                  CASE WHEN im.tipo_movimiento IN(3,7) THEN cantidad_nonac ELSE 0
                  END AS c_ret_ext,
                  CASE WHEN im.tipo_movimiento IN(3,7) THEN cantidad_naci ELSE 0
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
                  CASE WHEN im.tipo_movimiento IN(1,2,3,7,8,9) THEN fob_nonac ELSE 0
                  END AS fob,
                  CASE WHEN im.tipo_movimiento IN(3,7) THEN fob_nonac ELSE 0
                  END AS fob_retiro,
                  CASE WHEN im.tipo_movimiento IN(2,3,30) THEN cif ELSE 0
                  END AS cif,
                  CASE WHEN im.tipo_movimiento IN(8,9) THEN cif ELSE 0
                  END AS cif_proceso,
                  CASE WHEN im.tipo_movimiento IN(9) THEN cif ELSE 0
                  END AS cif_ensamble,
                  CASE WHEN im.tipo_movimiento IN(3,7) THEN cif ELSE 0
                  END AS cif_retiro,
                  CASE WHEN im.tipo_movimiento IN(4,5) THEN fob_nonac ELSE 0
                  END AS v_rpk,
                  im.inventario_entrada 
                  FROM inventario_movimientos im,inventario_entradas ie,arribos,do_asignados,clientes,referencias ref,posiciones p
                WHERE im.inventario_entrada = ie.codigo
                  AND arribos.arribo = ie.arribo
                  AND arribos.orden = do_asignados.do_asignado
                  AND clientes.numero_documento = do_asignados.por_cuenta
                  AND ie.referencia = ref.codigo
                  AND p.codigo = ie.posicion
                  AND do_asignados.sede = '$sede' $arreglo[where]) AS inv
                GROUP BY orden, cod_referencia $arreglo[having]";                       
    } else {
      //Prepara la condición del filtro
      $query = "SELECT nombre AS nombre_ubicacion FROM posiciones WHERE sede = '$sede'";
    }

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
  
	function findUbicacion($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT codigo,nombre FROM posiciones WHERE (nombre LIKE '%$arreglo[q]%')";

		$db->query($query);
    return $db->getArray();
	}
  
  function findReferencia($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT codigo,nombre FROM referencias WHERE (nombre LIKE '%$arreglo[q]%')";

		$db->query($query);
    return $db->getArray();
	}
}
?>