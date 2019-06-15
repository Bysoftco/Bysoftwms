<?php
require_once(CLASSES_PATH.'BDControlador.php');

class AlertasDatos extends BDControlador {
  
  function AlertasDatos() {
    parent :: Manejador_BD();
  }
  
  function retornarprocParcial() {
    $db = $_SESSION['conexion'];
   
    $sql = "SELECT orden, doc_tte, inventario_entrada, inventario_entrada AS item, arribo, nombre_referencia, cod_referencia, codigo_referencia,
              cant_declaraciones, cantidad, peso, valor, modelo, SUM(peso_nonac) AS peso_nonac, SUM(peso_naci) AS peso_naci, SUM(cantidad_naci) AS cantidad_naci,
              SUM(cantidad_nonac) AS cantidad_nonac, SUM(fob_nonac) AS fob_nonac, SUM(cif) AS cif, cod_maestro, MIN(num_levante) AS num_levante, un_grupo,
              por_cuenta, fecha_operacion, fecha_manifiesto, DATE_ADD(fecha_manifiesto, INTERVAL 1 MONTH) AS fecha_limite, fmm
            FROM (SELECT im.codigo, im.fecha AS fecha_operacion, arribos.fecha_manifiesto,
                    CASE WHEN im.tipo_movimiento IN (8,9) THEN 1 ELSE 0
                    END AS movimiento, do_asignados.do_asignado AS orden, do_asignados.doc_tte AS doc_tte, ie.arribo, ref.nombre AS nombre_referencia,
                    ref.ref_prove AS cod_referencia, ref.codigo AS codigo_referencia, ie.cant_declaraciones, ie.cantidad AS cantidad, ie.peso AS peso,
                    ie.valor AS valor, ie.modelo AS modelo, ie.fmm, im.inventario_entrada, im.cod_maestro, im.num_levante, im.tipo_movimiento,
                    id.grupo AS un_grupo, do_asignados.por_cuenta,
                    CASE WHEN im.tipo_movimiento IN (8,9) THEN peso_nonac  ELSE 0
                    END AS peso_nonac,
                    CASE WHEN im.tipo_movimiento IN (8,9) THEN peso_naci	ELSE 0
                    END AS peso_naci,
                    CASE WHEN im.tipo_movimiento IN (8,9) THEN cantidad_naci ELSE 0
                    END AS cantidad_naci,
                    CASE WHEN im.tipo_movimiento IN (8,9) THEN cantidad_nonac ELSE 0
                    END AS cantidad_nonac,
                    CASE WHEN im.tipo_movimiento IN (8,9) THEN fob_nonac ELSE 0
                    END AS fob_nonac,
                    CASE WHEN im.tipo_movimiento IN (8,9) THEN cif ELSE 0
                    END AS cif
                  FROM inventario_movimientos im
                    LEFT JOIN inventario_maestro_movimientos imm ON im.cod_maestro = imm.codigo
                    LEFT JOIN inventario_declaraciones id ON im.num_levante = id.num_levante,
                      arribos,do_asignados,clientes,referencias ref,inventario_entradas ie
                    LEFT JOIN controles_legales con ON con.orden = ie.orden AND con.ingreso = ie.arribo AND control = 14
                  WHERE im.inventario_entrada = ie.codigo
                    AND arribos.arribo = ie.arribo
                    AND arribos.orden = do_asignados.do_asignado
                    AND clientes.numero_documento = do_asignados.por_cuenta
                    AND ie.referencia = ref.codigo
					AND ref.tipo NOT IN(60,80)
					) AS inv
                  GROUP BY orden,por_cuenta, cod_referencia 
                  HAVING  TRUNCATE(peso_nonac,2) > 0 OR TRUNCATE(peso_naci,2) > 0 ";
				  
          // echo   $sql;   
    $db->query($sql);
    return $db->getArray();
  }
  
  function retornarNoNacional() {
    $db = $_SESSION['conexion'];
    $arregloDatos['movimiento'] = "1,2,30,3,7";
    
    $query = "SELECT * FROM (SELECT cl.numero_documento AS doc_cliente, cl.razon_social AS nom_cliente, ie.arribo, ie.orden, da.doc_tte,
                ref.codigo AS cod_referencia, ref.nombre AS nom_referencia, imm.fmm, ie.fecha AS fecha_registro, a.fecha_manifiesto,
                CASE WHEN DATE_ADD(a.fecha_manifiesto, INTERVAL 1 MONTH) > (CASE WHEN ISNULL(MAX(con.fecha)) THEN '0000-00-00' ELSE MAX(con.fecha) END)
                THEN DATE_ADD(a.fecha_manifiesto, INTERVAL 1 MONTH) ELSE MAX(con.fecha) END AS fecha_limite,
                CASE WHEN DATE_ADD(ie.fecha, INTERVAL 1 MONTH) > (CASE WHEN ISNULL(MAX(con.fecha)) THEN '0000-00-00' ELSE MAX(con.fecha) END)
                THEN 'NO' ELSE 'SI' END AS prorroga, SUM(im.peso_nonac) AS sum_peso_nonac, SUM(im.cantidad_nonac) AS sum_cantidad_nonac,
                SUM(im.fob_nonac) AS sum_valor_nonac, um.medida AS unidad_comercial, CASE WHEN ISNULL(con_final.nombre) THEN 'NO APLICA'
                ELSE con_final.nombre END AS control_final
              FROM inventario_movimientos im
                LEFT JOIN inventario_maestro_movimientos imm ON im.cod_maestro = imm.codigo
                INNER JOIN inventario_entradas ie ON im.inventario_entrada = ie.codigo
                LEFT JOIN controles_legales con ON con.orden = ie.orden AND con.ingreso = ie.arribo AND control = 9
                LEFT JOIN controles_legales fin ON fin.orden = ie.orden AND fin.ingreso = ie.arribo
                LEFT JOIN controles_control con_final ON con_final.codigo = fin.control
                INNER JOIN do_asignados da ON da.do_asignado = ie.orden
                INNER JOIN clientes cl ON da.por_cuenta = cl.numero_documento
                INNER JOIN sedes se ON se.codigo = cl.sede AND se.tipo_sede = 2
                INNER JOIN referencias ref ON ref.codigo = ie.referencia
                LEFT JOIN unidades_medida um ON um.id = ref.unidad_venta
                INNER JOIN arribos a ON a.arribo = ie.arribo AND a.orden = da.do_asignado
              WHERE im.tipo_movimiento IN (".$arregloDatos['movimiento'].")
              GROUP BY ie.orden, ie.arribo, ref.codigo) AS nacional
            WHERE nacional.sum_cantidad_nonac > 0 ORDER BY nacional.fecha_limite";
			
		

    $db->query($query);
	
    return $db->getArray();
  }
  
  function retornarNoNacional2() {
    $db = $_SESSION['conexion'];
    $arregloDatos['movimiento'] = "1,2,30,3,7";
    
    $query = "SELECT t.* FROM ( SELECT orden, doc_tte, inventario_entrada, inventario_entrada AS item, arribo, nombre_referencia, cod_referencia,
                                  codigo_referencia, cant_declaraciones, cantidad, peso, valor, modelo, SUM(peso_nonac) AS peso_nonac,
                                  SUM(peso_naci) AS peso_naci, SUM(peso_nonac)+SUM(peso_naci) AS peso_mixto, SUM(cantidad_naci) AS cantidad_naci,
                                  SUM(cantidad_nonac) AS cantidad_nonac, SUM(cantidad_naci)+SUM(cantidad_nonac) AS cantidad_mixto, SUM(fob_nonac) AS fob_nonac,
                                  SUM(cif) AS cif, SUM(fob_nonac)+SUM(cif) AS valor_mixto, cod_maestro, MIN(num_levante) AS num_levante, un_grupo, referencia
                                FROM (SELECT im.codigo, ie.referencia, CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN 1 ELSE 0
                                        END AS movimiento, do_asignados.do_asignado AS orden, do_asignados.doc_tte AS doc_tte, ie.arribo,
                                        ref.nombre AS nombre_referencia, ref.ref_prove AS cod_referencia, ref.codigo AS codigo_referencia,
                                        ie.cant_declaraciones, ie.cantidad AS cantidad, ie.peso AS peso, ie.valor AS valor, ie.modelo AS modelo,
                                        im.inventario_entrada, im.cod_maestro, im.num_levante, im.tipo_movimiento, id.grupo AS un_grupo,
                                        CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN peso_nonac ELSE 0 END AS peso_nonac,
                                        CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN peso_naci ELSE 0 END AS peso_naci,
                                        CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN cantidad_naci ELSE 0 END AS cantidad_naci,
                                        CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN cantidad_nonac ELSE 0 END AS cantidad_nonac,
                                        CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN fob_nonac ELSE 0 END AS fob_nonac,
                                        CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN cif ELSE 0 END AS cif
                                      FROM inventario_movimientos im
                                        LEFT JOIN inventario_maestro_movimientos imm ON im.cod_maestro = imm.codigo
                                        LEFT JOIN inventario_declaraciones id ON im.num_levante = id.num_levante,
                                        inventario_entradas ie, arribos, do_asignados, clientes, referencias ref
                                      WHERE im.inventario_entrada = ie.codigo
                                        AND arribos.arribo = ie.arribo AND arribos.orden = do_asignados.do_asignado
                                        AND clientes.numero_documento = do_asignados.por_cuenta AND ie.referencia = ref.codigo) AS inv
                                      GROUP BY codigo_referencia) t
                                WHERE t.cantidad_nonac > 0";
    
    echo"<pre>";print_r($query);echo"</pre>";
    $db->query($query);
    return $db->getArray();
  }
  
  function retornarProrroga($orden, $id_control="") {
    $db = $_SESSION['conexion'];
    
    $query = "SELECT cl.*, cc.nombre AS nombre_control FROM controles_legales cl LEFT JOIN controles_control cc ON cc.codigo = cl.control
              WHERE orden = $orden ";
    
    if(!empty($id_control)) {
      $query .= " AND control = $id_control ";
    }
    
    $query .= " ORDER BY fecha_registro LIMIT 1 ";
    $db->query($query);
    return $db->fetch();
  }
  
  function retornarAlerta($orden, $id_control="") {
	  echo "ready";
  }  
  
}
?>