<?php
require_once("MYDB.php");
require_once("OrdenDatos.php");

class Control extends MYDB {
  function Control() {
    $this->estilo_error = "ui-state-error";
    $this->estilo_ok = "ui-state-highlight";
  }

  //Extrae cualquier tipo de inventario
  function inventario($arregloDatos) {
    if(!empty($arregloDatos[cliente]) or !empty($arregloDatos[por_cuenta_filtro])) {
      $arregloDatos[where] .= " AND (do_asignados.por_cuenta='$arregloDatos[cliente]' OR do_asignados.por_cuenta='$arregloDatos[por_cuenta_filtro]')";
    }
    
    $sql = "SELECT orden, CONCAT(sigla,'-',orden) AS do_asignado_full, doc_tte, inventario_entrada, inventario_entrada AS item, arribo, manifiesto, fecha_manifiesto,
              nombre_referencia, cod_referencia, codigo_referencia, cant_declaraciones, cantidad, peso, valor, modelo,
              SUM(peso_nonac) AS peso_nonac, SUM(peso_naci) AS peso_naci, SUM(cantidad_naci) AS cantidad_naci,
              SUM(cantidad_nonac) AS cantidad_nonac, SUM(fob_nonac) AS fob_nonac, SUM(cif) AS cif, cod_maestro,
              MIN(num_levante) AS  num_levante, un_grupo, referencia
            FROM(SELECT im.codigo, dob.sigla, ie.referencia,
                  CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN 1 ELSE 0
                  END AS movimiento, do_asignados.do_asignado AS orden, do_asignados.doc_tte AS doc_tte,
                  ie.arribo, arribos.manifiesto AS manifiesto, arribos.fecha_manifiesto AS fecha_manifiesto,
                  ref.nombre AS nombre_referencia, ref.ref_prove AS cod_referencia, ref.codigo AS codigo_referencia,
                  ie.cant_declaraciones, ie.cantidad AS cantidad, ie.peso AS peso, ie.valor AS valor, ie.modelo AS modelo,
                  im.inventario_entrada, im.cod_maestro, im.num_levante, im.tipo_movimiento, id.grupo AS un_grupo,             
                  CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN peso_nonac ELSE 0
                  END AS peso_nonac,
                  CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN peso_naci ELSE 0
                  END AS peso_naci,
                  CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN cantidad_naci ELSE 0
                  END AS cantidad_naci,
                  CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN cantidad_nonac ELSE 0
                  END AS cantidad_nonac,
                  CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN fob_nonac ELSE 0
                  END AS fob_nonac,
                  CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN cif ELSE 0
                  END AS cif
                FROM inventario_movimientos im
                LEFT JOIN inventario_maestro_movimientos imm ON im.cod_maestro = imm.codigo
                LEFT JOIN inventario_declaraciones id ON im.num_levante = id.num_levante,
                  inventario_entradas ie, arribos, do_asignados, clientes, referencias ref, do_bodegas dob
                WHERE im.inventario_entrada = ie.codigo
                  AND arribos.arribo = ie.arribo AND arribos.orden = do_asignados.do_asignado
                  AND clientes.numero_documento = do_asignados.por_cuenta
                  AND do_asignados.sede = dob.sede
                  AND ie.referencia = ref.codigo $arregloDatos[where]) AS inv 
                GROUP BY $arregloDatos[GroupBy] $arregloDatos[having] $arregloDatos[orderBy]";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "&nbsp;Error al consultar Inventario";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
    if($this->N==0) {
      $this->mensaje = "&nbsp;No hay registros para mostrar";
      $arregloDatos[mensaje] = $this->mensaje;
      $this->estilo	= $this->estilo_error;
      $arregloDatos[estilo] = $this->estilo;
    }
  }
  
  //Lista la Mercancia Disponible para Bloquear
  function getMercanciaBloquear($arregloDatos) {
    $arregloDatos[cliente] = $arregloDatos[por_cuenta_filtro];
    //Configura parámetros para consulta en inventario
    $arregloDatos[movimiento] = "1,2,3,10,15,30";
    $arregloDatos[where] = ($arregloDatos[doc_tte_control]!= "") ? "AND do_asignados.doc_tte = '$arregloDatos[doc_tte_control]'" : "";
    $arregloDatos[GroupBy] = "inv.orden, inv.referencia";
    $arregloDatos[having] = "HAVING TRUNCATE(peso_nonac,1) > 0 OR TRUNCATE(peso_naci,1) > 0";
    $this->inventario($arregloDatos);    
  }

  //Agrega Registros de Bloqueos
  function addItemBloquear($arregloDatos) {
    $fecha_actual = FECHA;
    
    $arregloDatos[bloquear] = isset($arregloDatos[bloquear]) ? $arregloDatos[bloquear] : "No";
  
    $sql = "INSERT INTO controles_legales
              (orden,ingreso,entidad,control,auto_adm,bloquea,periodicidad,observaciones,fecha,fecha_registro)
            VALUES($arregloDatos[do_asignado],'$arregloDatos[arribo]',$arregloDatos[entidades],$arregloDatos[controles],
                    '$arregloDatos[auto_adm]','$arregloDatos[bloquear]',$arregloDatos[periodicidad],'$arregloDatos[obs]',
                    '$arregloDatos[fecha]','$fecha_actual')";           

    $this->query($sql);
    if($this->_lastError) {
      $arregloDatos[mensaje] = "&nbsp;Error al bloquear el Documento de Transporte:&nbsp;"+$arregloDatos[doc_tte];
      $arregloDatos[estilo] = $this->estilo_error;
      echo $sql;
      return TRUE;
    }
    $arregloDatos[mensaje] = "&nbsp;Se bloque&oacute; correctamente el Documento de Transporte:&nbsp;"+$arregloDatos[doc_tte];
    $arregloDatos[estilo] = $this->estilo_ok;  
  }

  //Lista la Mercancía en el TAB de Control
  function getControlDocumento($arregloDatos) {
    $sede = $_SESSION['sede'];
    
    //Lista los documentos disponibles
    $sql = "SELECT cl.orden, cl.ingreso AS arribo, cl.fecha AS fecha_control, do_asignados.doc_tte AS documento_transporte,
              cl.bloquea, arribos.manifiesto, arribos.fecha_manifiesto, cc.nombre AS nombre_control, ce.nombre AS nombre_entidad
            FROM controles_legales cl, do_asignados, arribos, controles_control cc, controles_entidades ce
            WHERE cl.orden = do_asignados.do_asignado
              AND cl.orden = arribos.orden
              AND cl.ingreso = arribos.arribo
              AND cl.control = cc.codigo
              AND cl.entidad = ce.codigo
              AND do_asignados.sede = '$sede'
              AND arribos.cantidad > 0";
    
    if(!empty($arregloDatos[por_cuenta_filtro])) $sql .= " AND do_asignados.por_cuenta = '$arregloDatos[por_cuenta_filtro]'";
		if(!empty($arregloDatos[fecha_desde]) AND (!empty($arregloDatos[fecha_hasta])))
      $sql .= " AND cl.fecha >= '$arregloDatos[fecha_desde]' AND cl.fecha <= '$arregloDatos[fecha_hasta]'";
    $sql .= " ORDER BY cl.codigo DESC";

    $this->query($sql);
    if($this->_lastError) {
      $arregloDatos[mensaje] = "&nbsp;Error al mostrar documentos bloqueados&nbsp;" . $sql;
      $arregloDatos[estilo] = $this->estilo_error;
      return TRUE;
    }
  }

  // Codifica el Tipo de Control y la Entidad
  function codTipoControl($arregloDatos) {
    $sql = "SELECT cc.nombre AS nombre_control, ce.nombre AS nombre_entidad
            FROM controles_control cc, controles_entidades ce
            WHERE cc.codigo = $arregloDatos[controles]
              AND ce.codigo = $arregloDatos[entidades]";

    $this->query($sql);
    $this->fetch();
		return array($this->nombre_entidad,$this->nombre_control);
  }
  
  //Lista controles asignados a un tipo documento de transporte
  function getListaControles($arregloDatos) {
    $sede = $_SESSION['sede'];
    
    $sql = "SELECT cl.orden, a.manifiesto, ce.nombre AS nombre_entidad, cc.nombre AS nombre_control, cl.auto_adm, cl.fecha,
              cl.bloquea, cl.periodicidad, cl.observaciones
            FROM controles_legales cl, controles_entidades ce, controles_control cc, arribos a
            WHERE (cl.orden = '$arregloDatos[orden_aux]')
              AND (ce.codigo = cl.entidad)
              AND (cc.codigo = cl.control)
              AND (cl.ingreso = a.arribo)";

    if(!empty($arregloDatos[fecha_desde]) AND (!empty($arregloDatos[fecha_hasta])))
      $sql .= " AND cl.fecha >= '$arregloDatos[fecha_desde]' AND cl.fecha <= '$arregloDatos[fecha_hasta]'";
    $sql .= " ORDER BY cl.codigo DESC";
    
    $this->query($sql);
    if($this->_lastError) {
      $arregloDatos[mensaje] = "&nbsp;Error al listar los controles&nbsp;" . $sql;
      $arregloDatos[estilo] = $this->estilo_error;
      return TRUE;
    }
  }  
    
  function existeCliente($arregloDatos) {
    $sql = "SELECT numero_documento,razon_social FROM clientes
            WHERE  numero_documento = '$arregloDatos[por_cuenta]'";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
  }

  function getCliente($arregloDatos) {
    $sql = "SELECT numero_documento,razon_social FROM clientes
            WHERE numero_documento = '$arregloDatos[por_cuenta_filtro]'";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }  
  }

  function lista($tabla,$condicion = NULL,$campoCondicion = NULL) {
    $sql = "SELECT codigo, nombre FROM $tabla WHERE codigo NOT IN('0')";

    if($condicion <> NULL AND $condicion <> '%') {
      $sql.=" AND $campoCondicion IN ('$condicion')" ;
    }

    $sql.="	ORDER BY nombre	" ;
	
    $this->query($sql); 
    if($this->_lastError) {
      return FALSE;
    } else {
      $arreglo = array();
      while($this->fetch()) {
        $arreglo[$this->codigo] =  ucwords($this->nombre);
      }
    }
    return $arreglo;
  }

  function getToolbar($arregloDatos) {
  }
  
  function findDocumento($arregloDatos) {
    $sql = "SELECT DISTINCT doc_tte, do_asignado, arribos.arribo, arribos.manifiesto,
              arribos.fecha_manifiesto  
            FROM inventario_entradas ie, arribos, do_asignados
            WHERE ie.tipo_mov = 0
              AND arribos.arribo = ie.arribo
              AND arribos.orden = do_asignados.do_asignado
              AND do_asignados.por_cuenta = '$arregloDatos[cliente]'
              AND doc_tte LIKE '%$arregloDatos[q]%'
            GROUP BY doc_tte, do_asignado";
            
    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
  }
}
?>