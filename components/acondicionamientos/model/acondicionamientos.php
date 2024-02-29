<?php
/**
 * Description of acondicionaDatos
 *
 * @author Fredy Salom <fsalom@bysoft.us>
 */

require_once(CLASSES_PATH.'BDControlador.php');

class acondicionaDatos extends BDControlador {
  function acondicionaDatos() {
    parent :: Manejador_BD();
  }
  
  function disponiblesProducto($codigo_ref, $docCliente) {
    $db = $_SESSION['conexion'];
    
    $arregloDatos['movimiento'] = "1,2,3,7,10,15,16,19,30";
    
    $query = "SELECT orden,
                doc_tte, 
                inventario_entrada, 
                inventario_entrada AS item, 
                arribo, nombre_referencia,
                cod_referencia, 
                codigo_referencia, 
                cant_declaraciones, 
                cantidad, 
                peso, 
                valor, 
                modelo,
                SUM(peso_nonac) AS peso_nonac, 
                SUM(peso_naci) AS peso_naci,
                SUM(cantidad_naci) AS cantidad_naci,
                SUM(cantidad_nonac) AS cantidad_nonac,  
                SUM(fob_nonac) AS fob_nonac, 
                SUM(cif) AS cif,
                cod_maestro,
                MIN(num_levante) AS num_levante, 
                un_grupo, 
                referencia
              FROM(
                SELECT im.codigo, ie.referencia,
                  CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento])
                    THEN 1 ELSE 0
                  END AS movimiento, do_asignados.do_asignado AS orden, do_asignados.doc_tte AS doc_tte,
                  ie.arribo, ref.nombre AS nombre_referencia, ref.ref_prove AS cod_referencia,
                  ref.codigo AS codigo_referencia, ie.cant_declaraciones, ie.cantidad AS cantidad,
                  ie.peso AS peso, ie.valor AS valor, ie.modelo AS modelo, im.inventario_entrada,
                  im.cod_maestro, im.num_levante, im.tipo_movimiento, id.grupo AS un_grupo,
                  CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento])
                    THEN peso_nonac ELSE 0
                  END AS peso_nonac,
                  CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento])
                    THEN peso_naci ELSE 0
                  END AS peso_naci,
                  CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento])
                    THEN cantidad_naci ELSE 0
                  END AS cantidad_naci,
                  CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento])
                    THEN cantidad_nonac ELSE 0
                  END AS cantidad_nonac,
                  CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento])
                    THEN fob_nonac ELSE 0
                  END AS fob_nonac,
                  CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento])
                    THEN cif ELSE 0
                  END AS cif
                FROM inventario_movimientos im
                  LEFT JOIN inventario_maestro_movimientos imm ON im.cod_maestro = imm.codigo
                  LEFT JOIN inventario_declaraciones id ON im.num_levante = id.num_levante,
                  inventario_entradas ie, arribos, do_asignados, clientes, referencias ref
                WHERE im.inventario_entrada = ie.codigo
                  AND arribos.arribo = ie.arribo AND arribos.orden = do_asignados.do_asignado
                  AND clientes.numero_documento = do_asignados.por_cuenta
                  AND ie.referencia = ref.codigo
                  AND ref.codigo = $codigo_ref
                  AND clientes.numero_documento = '$docCliente') AS inv
                GROUP BY codigo_referencia";

    $db->query($query);
    return $db->fetch();
  }
  
  function disponiblesRetirar($codigo_ref, $docCliente) {
    $db = $_SESSION['conexion'];
		$sede = $_SESSION['sede'];

    $arreglo[movimiento] = "1,2,3,7,10,15,16,30";
    $arreglo[GroupBy] = "orden,codigo_ref";
    $arreglo[having] = "HAVING (TRUNCATE(cantidad_nacional,1) > 0 OR TRUNCATE(cantidad_no_nacional,1) > 0)";

    $query = "SELECT orden, doc_tte, inventario_entrada,
                arribo, nombre_referencia, cod_referencia, codigo_ref, documento, fecha,
                modelo, SUM(p_nal) AS peso_nacional, SUM(p_ext) AS peso_no_nacional,
                nombre_ubicacion, manifiesto, SUM(c_nal) AS cantidad_nacional,
                SUM(c_ext) AS cantidad_no_nacional, ingreso,
                SUM(cif) AS cif, SUM(fob) AS fob_nonac, nombre_cliente
              FROM(SELECT im.codigo, im.tipo_movimiento, do_asignados.do_asignado AS orden, do_asignados.doc_tte AS doc_tte,
                ie.arribo, ref.nombre AS nombre_referencia, ref.codigo_ref AS codigo_ref, ref.ref_prove AS cod_referencia, ie.modelo AS modelo, ie.fecha AS fecha,
                clientes.razon_social AS nombre_cliente, clientes.numero_documento AS documento, do_asignados.ingreso AS ingreso,
                arribos.manifiesto AS manifiesto, p.nombre AS nombre_ubicacion,
                CASE WHEN im.tipo_movimiento IN($arreglo[movimiento]) THEN peso_nonac ELSE 0
                END AS p_ext,
                CASE WHEN im.tipo_movimiento IN($arreglo[movimiento]) THEN peso_naci ELSE 0
                END AS p_nal,
                CASE WHEN im.tipo_movimiento IN($arreglo[movimiento]) THEN cantidad_naci ELSE 0
                END AS c_nal,
                CASE WHEN im.tipo_movimiento IN($arreglo[movimiento]) THEN cantidad_nonac ELSE 0
                END AS c_ext,
                CASE WHEN im.tipo_movimiento IN(1,2,3,7,8,9,10,15,16) THEN fob_nonac ELSE 0
                END AS fob,
                CASE WHEN im.tipo_movimiento IN(2,3,10,15,16,30) THEN cif ELSE 0
                END AS cif,
                im.inventario_entrada 
                FROM inventario_movimientos im,inventario_entradas ie,arribos,do_asignados,clientes,referencias ref,posiciones p
              WHERE im.inventario_entrada = ie.codigo
                AND arribos.arribo = ie.arribo
                AND arribos.orden = do_asignados.do_asignado
                AND clientes.numero_documento = do_asignados.por_cuenta
                AND ie.referencia = ref.codigo
                AND ref.codigo = $codigo_ref
                AND clientes.numero_documento = '$docCliente'
                AND p.codigo = ie.posicion
                AND im.tipo_movimiento IN (".$arreglo['movimiento'].")
                AND do_asignados.sede = '$sede' $arreglo[where]) AS inv
              GROUP BY $arreglo[GroupBy] $arreglo[having]"; //cod_referencia se cambio por codigo_ref
    
    $db->query($query);
    return $db->getArray();
  }
    
  function retornarOrden($idMaestro) {
    $db = $_SESSION['conexion'];
    
    $query = "SELECT * FROM inventario_entradas WHERE codigo = $idMaestro";
    
    $db->query($query);
    return $db->fetch();
  }
  
  function retornarDatos($arreglo) {
    $db = $_SESSION['conexion'];
    
    $query = "SELECT * FROM inventario_movimientos WHERE codigo = $arreglo[codigo_mov]";
    
    $db->query($query);
    return $db->fetch();
  }
  
  function retornarMaestroAcondicionamiento($idRegistro) {
    $db = $_SESSION['conexion'];
    
    $query = "SELECT imm.*, ref.nombre AS nombre_referencia, te.nombre AS nombre_unidad_empaque,
                cl.numero_documento, cl.razon_social, cm.conductor_nombre, ref.parte_numero,
                cm.conductor_identificacion, cm.placa, ci.nombre AS ciudad, ci.codigo AS cod_ciudad,
                ref.codigo_ref
              FROM inventario_maestro_movimientos imm
                INNER JOIN referencias ref ON ref.codigo = imm.producto
                INNER JOIN embalajes te ON te.codigo = imm.unidad
                INNER JOIN do_asignados da ON da.do_asignado = imm.orden
                INNER JOIN clientes cl ON cl.numero_documento = da.por_cuenta
                LEFT JOIN camiones cm ON imm.id_camion = cm.codigo
                LEFT JOIN ciudades ci ON imm.ciudad = ci.codigo
              WHERE imm.codigo = $idRegistro";
 
    $db->query($query);
    return $db->fetch();
  }
  
  function retornarDetalleAcondicionamiento($idRegistro) {
    $db = $_SESSION['conexion'];
    
    $query = "SELECT im.*, ie.*, da.*, ref.codigo AS cod_referencia,
                ref.nombre AS nombre_referencia, ref.codigo_ref,
                p.nombre AS nombre_ubicacion, em.nombre AS nombre_mcia
              FROM inventario_movimientos im
                INNER JOIN inventario_entradas ie ON ie.codigo = im.inventario_entrada
                INNER JOIN referencias ref ON ref.codigo = ie.referencia
                INNER JOIN estados_mcia em ON em.codigo = im.estado_mcia
                INNER JOIN do_asignados da ON da.do_asignado = ie.orden
                LEFT JOIN posiciones p ON p.codigo = ie.posicion
              WHERE cod_maestro = $idRegistro AND tipo_movimiento = 16";

    $db->query($query);
    return $db->getArray();
  }
  
  function registrarDetalleAcondicionamiento($idRegistro) {
    $db = $_SESSION['conexion'];
    
    $query = "SELECT im.codigo AS cod_movimiento,im.cod_maestro,im.tipo_movimiento,
                im.inventario_entrada,im.peso_naci,im.peso_nonac,im.cantidad_naci,
                im.peso_nonac,im.cantidad_nonac,im.cif,im.fob_nonac,im.estado_mcia,
                ref.codigo AS codigo_ref
              FROM inventario_movimientos im
                INNER JOIN inventario_maestro_movimientos imm ON imm.codigo = im.cod_maestro
                INNER JOIN inventario_entradas ie ON ie.codigo = im.inventario_entrada
                INNER JOIN referencias ref ON ref.codigo = ie.referencia
              WHERE cod_maestro = $idRegistro AND tipo_movimiento = 16";

    $db->query($query);
    return $db->getArray();
  }
  
  function regOrdenDetalleAcondicionamiento($idRegistro) {
    $db = $_SESSION['conexion'];

    $arreglo[GroupBy] = "orden,codigo_ref";    
    $query = "SELECT orden,codigo_ref,nombre_referencia,doc_tte,fecha_expira,fecha_mov,modelo,
                ABS(SUM(tac_nac)+SUM(tac_ext)) AS acondicionadas,
                ABS(SUM(trc_nac)+SUM(trc_ext)) AS rechazadas,
                ABS(SUM(tdc_nac)+SUM(tdc_ext)) AS devueltas,nombre_ubicacion
              FROM (SELECT im.codigo AS cod_movimiento,im.cod_maestro,im.tipo_movimiento,
                  im.inventario_entrada,im.estado_mcia,im.fecha AS fecha_mov,
                  CASE WHEN im.estado_mcia>1 THEN im.cantidad_naci ELSE 0
                  END AS trc_nac,
                  CASE WHEN im.estado_mcia=1 THEN im.cantidad_naci ELSE 0
                  END AS tac_nac,
                  CASE WHEN im.estado_mcia=0 THEN im.cantidad_naci ELSE 0
                  END AS tdc_nac,
                  CASE WHEN im.estado_mcia>1 THEN im.cantidad_nonac ELSE 0
                  END AS trc_ext,
                  CASE WHEN im.estado_mcia=1 THEN im.cantidad_nonac ELSE 0
                  END AS tac_ext,
                  CASE WHEN im.estado_mcia=0 THEN im.cantidad_nonac ELSE 0
                  END AS tdc_ext,im.cif,im.fob_nonac,
                  ref.codigo_ref,ie.orden,ref.nombre AS nombre_referencia,
                  da.doc_tte,ie.fecha_expira,ie.posicion,ie.modelo,p.nombre AS nombre_ubicacion
                FROM inventario_movimientos im
                  INNER JOIN inventario_maestro_movimientos imm ON imm.codigo = im.cod_maestro
                  INNER JOIN inventario_entradas ie ON ie.codigo = im.inventario_entrada
                  INNER JOIN referencias ref ON ref.codigo = ie.referencia
                  INNER JOIN do_asignados da ON da.do_asignado = ie.orden
                  LEFT JOIN posiciones p ON p.codigo = ie.posicion
                WHERE cod_maestro = $idRegistro AND tipo_movimiento = 16) AS inv
              GROUP BY $arreglo[GroupBy]";

    $db->query($query);
    return $db->getArray();
  }

  function cerrarAcondicionamiento($codigo) {
    $db = $_SESSION['conexion'];
    $query = "UPDATE inventario_maestro_movimientos SET cierre = 1 WHERE codigo = $codigo";
    $db->query($query);
  }
  
  function listadoRechazadas($arreglo) {
    $db = $_SESSION['conexion'];

    $arreglo['GroupBy'] = "inventario_entrada";
    $arreglo['having'] = "HAVING (TRUNCATE(ABS(tc_nal),1) > 0 OR TRUNCATE(ABS(tc_ext),1) > 0)";
    $arreglo['where'] = "";

		//Prepara la condición del filtro
    if(!empty($arreglo['nitfr'])) $arreglo['where'] .= " AND da.por_cuenta = '$arreglo[nitfr]'";
    if(!empty($arreglo['fechadesdefr'])) $arreglo['where'] .= " AND DATE(im.fecha) >= '$arreglo[fechadesdefr]'";
    if(!empty($arreglo['fechahastafr'])) $arreglo['where'] .= " AND DATE(im.fecha) <= '$arreglo[fechahastafr]'";
    if(!empty($arreglo['doasignadofr'])) $arreglo['where'] .= " AND da.do_asignado = '$arreglo[doasignadofr]'";
    if(!empty($arreglo['tiporechazofr'])) $arreglo['where'] .= " AND im.estado_mcia = '$arreglo[tiporechazofr]'";

    $query = "SELECT im.*,im.fecha AS fecha_rechazo,im.codigo AS codigo_mov,ie.*,ref.nombre AS nombre_referencia,
                ref.codigo_ref,p.nombre AS nombre_ubicacion,em.nombre AS tipo_rechazo,
                imm.doc_tte, cl.numero_documento,cl.razon_social,
                SUM(cantidad_naci) AS tc_nal,
                SUM(peso_naci) AS tp_nal,
                SUM(cantidad_nonac) AS tc_ext,
                SUM(peso_nonac) AS tp_ext
              FROM inventario_movimientos im
                INNER JOIN inventario_entradas ie ON ie.codigo = im.inventario_entrada
                INNER JOIN referencias ref ON ref.codigo = ie.referencia
                INNER JOIN inventario_maestro_movimientos imm ON imm.codigo = im.cod_maestro
                INNER JOIN do_asignados da ON da.do_asignado = imm.orden
                INNER JOIN clientes cl ON cl.numero_documento = da.por_cuenta
                INNER JOIN estados_mcia em ON em.codigo = im.estado_mcia
                LEFT JOIN posiciones p ON p.codigo = ie.posicion
              WHERE ((tipo_movimiento = 16 AND estado_mcia > 1) OR
                (tipo_movimiento = 19 AND estado_mcia = 1)) $arreglo[where]
              GROUP BY $arreglo[GroupBy] $arreglo[having] ORDER BY im.codigo";

    $db->query($query);
    return $db->getArray();
  }
  
  function retornarIdNuevoIngreso($codigo) {
    $db = $_SESSION['conexion'];
    $query = "SELECT * FROM inventario_movimientos WHERE cod_maestro = $codigo AND tipo_movimiento = 1";
    
    $db->query($query);
    return $db->fetch();
  }
  
  function eliminar($table, $where) {
    $db = $_SESSION['conexion'];
    $query = "DELETE FROM $table WHERE $where";
    $db->query($query);
  }
  
  function listadoEtiquetar($arreglo) {
    $db = $_SESSION['conexion'];
		$sede = $_SESSION['sede'];
	
    $orden = " acondicionamiento DESC ";
    $buscar = "";
    if(isset($arreglo['orden']) && !empty($arreglo['orden'])) {
      $orden = " $arreglo[orden] $arreglo[id_orden]";
    }
    if(isset($arreglo['buscar']) && !empty($arreglo['buscar'])) {
      $buscar = " AND (acondicionamiento LIKE '%$arreglo[buscar]%' 
									OR nombre_cliente LIKE '%$arreglo[buscar]%'
                  OR fecha LIKE '%$arreglo[buscar]%'
                  OR pedido LIKE '%$arreglo[buscar]%'
                  OR fmm LIKE '%$arreglo[buscar]%')";
    }

    $query = "SELECT DISTINCT imm.codigo AS acondicionamiento,imm.fecha,im.tipo_movimiento,imm.fmm,imm.pedido,cl.numero_documento,
                cl.razon_social AS nombre_cliente
              FROM inventario_movimientos im,inventario_entradas ie,clientes cl,arribos,do_asignados,
                inventario_maestro_movimientos imm
              WHERE im.cod_maestro = imm.codigo
                AND im.tipo_movimiento = 16
                AND im.inventario_entrada = ie.codigo
                AND arribos.arribo = ie.arribo
                AND arribos.orden = do_asignados.do_asignado
                AND do_asignados.por_cuenta = cl.numero_documento
                AND imm.cierre = 1
                AND do_asignados.sede = '$sede'$buscar";
              		
		//Prepara la condición de filtro
    if(!empty($arreglo['niteac'])) $query .= " AND do_asignados.por_cuenta = '$arreglo[niteac]'";
		if(!empty($arreglo['fechadesdeeac'])) $query .= " AND imm.fecha >= '$arreglo[fechadesdeeac]'";
		if(!empty($arreglo['fechahastaeac'])) $query .= " AND imm.fecha <= '$arreglo[fechahastaeac]'";
		if(!empty($arreglo['nacondiciona'])) $query .= " AND im.cod_maestro = '$arreglo[nacondiciona]'";
    
		//Ordena el Listado
		$query .= " ORDER BY $orden";

    $db->query($query);
    $mostrar = 10;
    $retornar['paginacion'] = $this->paginar($arreglo['pagina'],$db->countRows(),$mostrar);
		
    $limit= ' LIMIT '. ($arreglo['pagina'] -1) * $mostrar . ',' . $mostrar;
    $query.=$limit;
    $db->query($query);
    $retornar['datos']=$db->getArray();
    return $retornar;
  }
  
  function reintegroMercancia($arreglo) {
    $db = $_SESSION['conexion'];
    
    //Inserta el movimiento de Reintegro en inventario_movimientos
    $query = "INSERT INTO inventario_movimientos(inventario_entrada,fecha,tipo_movimiento,
                cod_maestro,peso_naci,peso_nonac,cantidad_naci,cantidad_nonac,cif,fob_nonac,
                estado_mcia)
              VALUES($arreglo[inventario_entrada],'$arreglo[fecha]',$arreglo[tipo_movimiento],
                $arreglo[cod_maestro],$arreglo[peso_naci]*-1,$arreglo[peso_nonac]*-1,
                $arreglo[cantidad_naci]*-1,$arreglo[cantidad_nonac]*-1,$arreglo[cif]*-1,
                $arreglo[fob_nonac]*-1,$arreglo[estado_mcia])";

    $db->query($query);
  }
}
?>