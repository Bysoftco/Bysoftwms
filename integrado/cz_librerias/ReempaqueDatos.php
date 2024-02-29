<?php
require_once(DB.'BDControlador.php');

class Reempaque extends BDControlador {
  var $db;

  function Reempaque() {
    $this->db = $_SESSION['conexion'];
    $this->estilo_error = "ui-state-error";
    $this->estilo_ok = "ui-state-highlight";
  }

  //Funci�n que inserta mercanc�a al inventario de entrada
  function newEntradaInventario($arregloDatos) {
    $fecha = FECHA; //Captura la fecha actual de Inserci�n del Sistema
    
    $sql = "INSERT INTO inventario_entradas
              (arribo,orden,tipo_mov,fecha,referencia,cantidad,peso,valor)
            VALUES($arregloDatos[arribo],$arregloDatos[orden_seleccion],$arregloDatos[tipo_movimiento],'$fecha','2',
              $arregloDatos[tot_cant_nonac],$arregloDatos[tot_peso_nonac2],$arregloDatos[total_fob2])";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos[mensaje] = "&nbsp;Error al crear el movimiento en el inventario de entradas";
      $arregloDatos[estilo] = $this->estilo_error;
      return TRUE;
    }

    //Obtiene el nuevo Consecutivo generado
    $ult_codigo = $this->db->getInsertID();
    return $ult_codigo; //Retorna el �ltimo consecutivo
  }
  
  //Extrae cualquier tipo de inventario
  function inventario($arregloDatos) {
    $arregloDatos['where'] = "";
    if(!empty($arregloDatos['cliente']) or !empty($arregloDatos['por_cuenta_filtro'])) {
      $arregloDatos['where'] .= " AND (do_asignados.por_cuenta='$arregloDatos[cliente]' OR do_asignados.por_cuenta='$arregloDatos[por_cuenta_filtro]')";
    }
    if(!empty($arregloDatos['orden_reempaque'])) $arregloDatos['where'] .= " AND ie.orden = '$arregloDatos[orden_reempaque]'";
    if(!empty($arregloDatos['doc_tte_reempaque'])) $arregloDatos['where'] .= " AND do_asignados.doc_tte = '$arregloDatos[doc_tte_reempaque]'";
    if(!empty($arregloDatos['fecha_desde']) && (!empty($arregloDatos['fecha_hasta'])))
      $arregloDatos['where'] .= " AND (ie.fecha >= '$arregloDatos[fecha_desde]' AND ie.fecha <= '$arregloDatos[fecha_hasta]')";
    $sql = "SELECT orden, doc_tte, inventario_entrada, inventario_entrada AS item, arribo, nombre_referencia,
              cod_referencia, codigo_referencia, cant_declaraciones, cantidad, peso, valor, modelo,
              SUM(peso_nonac) AS peso_nonac, SUM(peso_naci) AS peso_naci, SUM(cantidad_naci) AS cantidad_naci,
              SUM(cantidad_nonac) AS cantidad_nonac, SUM(fob_nonac) AS fob_nonac, SUM(cif) AS cif, cod_maestro,
              MIN(num_levante) AS  num_levante, un_grupo, referencia
            FROM(SELECT im.codigo, ie.referencia, ie.fecha,
                  CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN 1 ELSE 0
                  END AS movimiento, do_asignados.do_asignado AS orden, do_asignados.doc_tte AS doc_tte,
                  ie.arribo, ref.nombre AS nombre_referencia, ref.ref_prove AS cod_referencia,
                  ref.codigo AS codigo_referencia, ie.cant_declaraciones, ie.cantidad AS cantidad,
                  ie.peso AS peso, ie.valor AS valor, ie.modelo AS modelo, im.inventario_entrada,
                  im.cod_maestro, im.num_levante, im.tipo_movimiento, id.grupo AS un_grupo,
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
                  inventario_entradas ie, arribos, do_asignados, clientes, referencias ref
                WHERE im.inventario_entrada = ie.codigo
                  AND arribos.arribo = ie.arribo AND arribos.orden = do_asignados.do_asignado
                  AND clientes.numero_documento = do_asignados.por_cuenta 
                  AND ie.referencia = ref.codigo $arregloDatos[where] 
                  AND do_asignados.sede = '$arregloDatos[sede]') AS inv
                GROUP BY $arregloDatos[GroupBy] $arregloDatos[having] $arregloDatos[orderBy]";

    $this->db->query($sql);
    $rows = count($this->db->getArray());
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje = "&nbsp;Error al consultar Inventario";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
    if($rows==0) {
      $this->mensaje = "&nbsp;No hay registros para mostrar";
      $arregloDatos['mensaje'] = $this->mensaje;
      $this->estilo	= $this->estilo_error;
      $arregloDatos['estilo'] = $this->estilo;
    }
  }
  
  //Lista la Mercanc�a Disponible para Reempacar
  function getInvParaReempacar($arregloDatos) {
    $arregloDatos['sede'] = $_SESSION['sede'];     
    $arregloDatos['movimiento'] = "1,2,3,30";
    $arregloDatos['GroupBy'] = "inv.orden, inv.referencia";
    $arregloDatos['having'] = "HAVING TRUNCATE(peso_nonac,1) > 0";
    $this->inventario($arregloDatos);    
  }

  function listaReempacados($arregloDatos) {
    $sede = $_SESSION['sede'];
	
    $sql = "SELECT DISTINCT MAX(im.inventario_entrada) AS item, SUM(im.peso_nonac) AS peso_nonac,
              MAX(arribos.arribo) AS arribo, MAX(do_asignados.do_asignado) AS do_asignado, arribos.orden AS orden,
              do_asignados.doc_tte, arribos.fmm, arribos.ubicacion
            FROM inventario_movimientos im,inventario_entradas ie,arribos,do_asignados,referencias ref
            WHERE im.inventario_entrada = ie.codigo
              AND arribos.arribo = ie.arribo
              AND arribos.orden = do_asignados.do_asignado
              AND do_asignados.por_cuenta = '$arregloDatos[por_cuenta_filtro]'
              AND tipo_movimiento in(1,2,3,4,5)
              GROUP BY arribos.orden
            HAVING TRUNCATE(peso_nonac,1) > 0";
              
    $this->db->query($sql);
    $rows = count($this->db->getArray());
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje = "&nbsp;Error al consultar Inventario";
      $arregloDatos['mensaje'] = $this->mensaje;
      $this->estilo	= $this->estilo_error;
      $arregloDatos['estilo'] = $this->estilo;
      return TRUE;
    }
    if($rows==0) {
      $this->mensaje = "&nbsp;No hay registros para mostrar";
      $arregloDatos['mensaje'] = $this->mensaje;
      $this->estilo	= $this->estilo_error;
      $arregloDatos['estilo'] = $this->estilo;
    }
  }

  //Agrega Registros de Mercancia Seleccionada a Reempacar
  function addItemReempacar($arregloDatos) {
    $fecha = FECHA;
   
    $sql = "INSERT INTO inventario_movimientos
              (inventario_entrada,fecha,tipo_movimiento,peso_nonac,cantidad_nonac,fob_nonac,cod_maestro)
            VALUES($arregloDatos[id_itemx],'$fecha',1,$arregloDatos[tot_peso_nonac2],$arregloDatos[tot_cant_nonac],$arregloDatos[total_fob2] ,$arregloDatos[id_reempaque])";           

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje'] = "&nbsp;Error al reempacar la mercanc&iacute;a";
      $arregloDatos['estilo'] = $this->estilo_error;
      echo $sql;
      return TRUE;
    } else {
      $arregloDatos['mensaje'] = "&nbsp;Se reempac&oacute; correctamente la mercanc&iacute;a";
      $arregloDatos['estilo'] = $this->estilo_ok;      
    }  
  }

  function findDocumento($arregloDatos) {
    $sql = "SELECT DISTINCT doc_tte,do_asignado,SUM(im.peso_nonac) AS peso_nonac,SUM(im.peso_naci) AS peso_nac                
            FROM inventario_movimientos im,inventario_entradas ie,arribos,do_asignados
            WHERE im.inventario_entrada = ie.codigo
              AND arribos.arribo = ie.arribo
              AND arribos.orden = do_asignados.do_asignado
              AND do_asignados.por_cuenta = '$arregloDatos[cliente]'
              AND doc_tte LIKE '%$arregloDatos[q]%'
            GROUP BY doc_tte,do_asignado";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      return TRUE;
    }
  }

  //Ajusta el inventario con codigo = 30
  function ajusteItemReempacar($id_item,$peso,$cantidad,$fob,$id_reempaque,$tipo_movi) {
    $fecha = FECHA;
        
    $sql = "INSERT INTO inventario_movimientos
              (fecha,inventario_entrada,tipo_movimiento,peso_nonac,cantidad_nonac,fob_nonac,cod_maestro)
            VALUES('$fecha',$id_item,$tipo_movi,$peso,$cantidad,$fob,$id_reempaque)";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje'] = "&nbsp;Error al ajustar el inventario";
      $arregloDatos['estilo'] = $this->estilo_error;
      return TRUE;
    } else {
      $arregloDatos['mensaje'] = "&nbsp;Se ajust&oacute; correctamente el inventario";
      $arregloDatos['estilo'] = $this->estilo_ok;      
    } 
  }

  //Lista la Mercanc�a en el cuerpo de movimientos de nacionalizaci�n y Reempaque Integrar
  function getCuerpoReempaque($arregloDatos) {
    $sql = "SELECT codigo FROM inventario_movimientos
            WHERE inventario_entrada = '$arregloDatos[cod_item]' AND tipo_movimiento = $arregloDatos[tipo_movimiento]";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql."<br>";
      $arregloDatos['mensaje'] = "&nbsp;Error al consultar Inventario&nbsp;" . $sql;
      $arregloDatos['estilo'] = $this->estilo_error;
      return TRUE;
    }
  }

  function getReempaque($arregloDatos) { //Funci�n a revisar - versi�n anterior getLevante
    $sql = "SELECT imm.fecha, imm.fmm, imm.posicion
            FROM inventario_maestro_movimientos imm
            WHERE imm.codigo = $arregloDatos[id_reempaque] "; 

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      $this->mensaje = "error al consultar Inventario " . $sql;
      echo $sql."<br>";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  //Lista la Mercanc�a Integrada = 5 y/o Unificada = 4
  function getCuerpoReempacar($arregloDatos) {
    $sql = "SELECT im.codigo AS id_reempacar,
              inventario_entrada AS id_item,
              SUM(im.peso_nonac) AS peso_nonac,
              SUM(im.cantidad_nonac) AS cantidad_nonac,
              SUM(im.fob_nonac) AS fob_nonac,
              MAX(arribos.orden) AS orden,
              MAX(arribos.arribo) AS arribo,
              MAX(ie.codigo) AS cod_item,
              MAX(ref.nombre) AS nombre_referencia,
              MAX(ref.ref_prove) AS cod_referencia
            FROM inventario_entradas ie,arribos,do_asignados,referencias ref,inventario_movimientos im
            WHERE im.inventario_entrada = ie.codigo
              AND arribos.arribo = ie.arribo
              AND arribos.orden = do_asignados.do_asignado
              AND ie.referencia = ref.codigo
              AND im.cod_maestro = '$arregloDatos[id_reempaque]' AND tipo_movimiento = $arregloDatos[tipo_movimiento]
            GROUP BY inventario_entrada";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje = "&nbsp;Error al consultar Inventario&nbsp;" . $sql;
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  //Devuelve los datos de la Cabeza del Reempaque
  function getCabezaReempaque($arregloDatos) {
    $sql = "SELECT imm.codigo AS num_reempaque, imm.fecha, imm.fmm, imm.posicion, posiciones.nombre AS nombre_posicion, imm.obs
            FROM inventario_maestro_movimientos imm
            LEFT JOIN posiciones ON imm.posicion = posiciones.codigo
            WHERE imm.codigo = $arregloDatos[id_reempaque]";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje = "&nbsp;Error al consultar Inventario" . $sql;
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  function setCabezaReempaque($arregloDatos) {
    $sql = "UPDATE inventario_maestro_movimientos
              SET fecha = '$arregloDatos[fecha]',
              fmm = '$arregloDatos[fmm]',
              posicion = '$arregloDatos[posicion]',
              obs = '$arregloDatos[obs]'
            WHERE codigo = $arregloDatos[id_reempaque]";
            
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje = "&nbsp;Error al actualizar inventario_maestro_movimientos" . $sql;
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  function cantidadNacionalParcial($arregloDatos) {
  }

  function newReempaque($arregloDatos) {
    $fecha = FECHA; //Captura la fecha actual de Inserci�n del Sistema
 
    $sql = "INSERT INTO inventario_maestro_movimientos (fecha,tip_movimiento)
            VALUES('$fecha','$arregloDatos[tipo_movimiento]')";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje'] = "&nbsp;Error al crear el movimiento de Reempaque";
      $arregloDatos['estilo'] = $this->estilo_error;
      return TRUE;
    }

    //Obtiene el nuevo Id_Reempaque generado
    $this->getIdReempaque($arregloDatos);
    $datos = $this->db->fetch();
    return $datos->codigo; //Reenv�a el nuevo Id_Reempaque generado
  }

  //Devuelve el �ltimo ID creado en la tabla inventario_maestro_movimientos
  function getIdReempaque($arregloDatos) {
    $sql = "SELECT MAX(codigo) AS codigo FROM inventario_maestro_movimientos";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje = "&nbsp;Error al consultar ID Reempaque ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  function delReempaque($arregloDatos) {
    //Reversa Registro del Inventario de Movimientos
    $sql = "DELETE FROM inventario_movimientos
            WHERE (cod_maestro = '$arregloDatos[id_reempaque]'
              AND (tipo_movimiento IN(1,5,4,30)))";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje = "&nbsp;Error al borrar mercanc&iacute;a a reempacar";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }
  
  function getIDReferencia($arregloDatos) {
    //Busca codigo mayor de Inventario de Entrada
    $sql = "SELECT MAX(codigo) AS codigo FROM inventario_entradas
            WHERE (orden = $arregloDatos[orden_seleccion]) AND (referencia = '2')";
    
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje = "&nbsp;Error al borrar inventario de entrada";
      $this->estilo = $this->estilo_error;
      return TRUE;
    } else {
      $datos = $this->db->fetch();    
      return $datos->codigo;      
    }
  }
  
  function borrarReferencia($arregloDatos) {
    //Elimina Referencia Tipo 2 del Inventario de Entradas
    $sql = "DELETE FROM inventario_entradas
            WHERE (codigo = $arregloDatos[id_item]) AND (orden = $arregloDatos[orden_seleccion]) AND (referencia = '2')";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje = "&nbsp;Error al borrar referencia en el inventario de entradas";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }
    
  function existeCliente($arregloDatos) {
    $sql = "SELECT numero_documento,razon_social FROM clientes
            WHERE  numero_documento = '$arregloDatos[por_cuenta]'";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      return TRUE;
    }
  }

  function getCliente($arregloDatos) {
    $sql = "SELECT numero_documento,razon_social FROM clientes
            WHERE numero_documento = '$arregloDatos[por_cuenta_filtro]'";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
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
	
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      return FALSE;
    } else {
      $arreglo = array();
      while($obj=$this->db->fetch()) {
        $arreglo[$obj->codigo] = ucwords($obj->nombre);
      }
      return $arreglo;
    }
  }

  function hayReempaques($arregloDatos) {
    $sql = "SELECT codigo FROM inventario_movimientos
            WHERE inventario_entrada = '$arregloDatos[cod_item]' AND tipo_movimiento = 1";

    $this->db->query($sql);
    $rows = count($this->db->getArray());
    $resultado = $this->db->query($sql);
    return $rows;
  }

  function getToolbar($arregloDatos) {
  }
  
  function filtroReempaque($arregloDatos) {
  }
  
  function setControl($inventario_entrada, $movimiento) {
    $sql = "UPDATE inventario_movimientos SET flg_control = 0 WHERE (inventario_entrada = $inventario_entrada)
              AND tipo_movimiento IN($movimiento)";
              
    $this->db->query($sql);
  }
  
  function setControla($arribo, $movimiento) {
    $sql = "UPDATE inventario_movimientos AS im INNER JOIN inventario_entradas AS ie
            ON im.inventario_entrada = ie.codigo SET flg_control = 0 WHERE (ie.arribo = $arribo)
              AND im.tipo_movimiento IN($movimiento) AND (ie.referencia = 2)";
              
    $this->db->query($sql);
  }  
}
?>