<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of KitsDatos
 *
 * @author Teresa
 */
require_once(CLASSES_PATH.'BDControlador.php');
class KitsDatos extends BDControlador {
  function KitsDatos() {
    parent :: Manejador_BD();
  }

  function validarCodigo($codigo, $cliente) {
    $db = $_SESSION['conexion'];
    
    $query = "SELECT * FROM referencias WHERE codigo_ref = '$codigo'
                AND cliente = '$cliente'";

    $db->query($query);
    return $db->getArray();
  }

  function eliminarComposicionKits($idKit) {
    $db = $_SESSION['conexion'];

    $query = "DELETE FROM kits_detalle WHERE id_kit = '$idKit'";

    $db->query($query);
  }

  function disponiblesProducto($codigo_ref, $docCliente) {
    $db = $_SESSION['conexion'];

    $arregloDatos['movimiento'] ="1,2,3,7,15,30";

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
                SUM(peso_nonac)+SUM(peso_naci) AS peso_mixto,
                SUM(cantidad_naci) AS cantidad_naci,
                SUM(cantidad_nonac) AS cantidad_nonac, 
                SUM(cantidad_naci) + SUM(cantidad_nonac) AS cantidad_mixto, 
                SUM(fob_nonac) AS fob_nonac, 
                SUM(cif) AS cif,
                SUM(fob_nonac) + SUM(cif) AS valor_mixto,
                cod_maestro,
                MIN(num_levante) AS num_levante, 
                un_grupo, 
                referencia
              FROM(SELECT im.codigo, ie.referencia,
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
                    AND ie.referencia = ref.codigo
                    AND ref.codigo = $codigo_ref
                    AND clientes.numero_documento = '$docCliente') AS inv 
                GROUP BY codigo_referencia";

    $db->query($query);
    return $db->fetch();
  }

  function disponiblesRetirar($codigo_ref, $docCliente) {
    $db = $_SESSION['conexion'];
    $arregloDatos['movimiento'] ="1,2,3,7,15,30";

    $query = "SELECT
                im.inventario_entrada,
                SUM(im.peso_naci) AS peso_nacional,
                SUM(im.peso_nonac) AS peso_no_nacional,
                SUM(im.cantidad_naci) AS cantidad_nacional, 
                SUM(im.cantidad_nonac) AS cantidad_no_nacional,
                SUM(im.cif) AS cif,
                SUM(im.fob_nonac) AS fob_nonac,
                imm.orden
              FROM inventario_movimientos im
                LEFT JOIN inventario_maestro_movimientos imm ON im.cod_maestro = imm.codigo
                LEFT JOIN inventario_declaraciones id ON im.num_levante = id.num_levante,
                inventario_entradas ie, arribos, do_asignados, clientes, referencias ref
              WHERE im.inventario_entrada = ie.codigo
                AND arribos.arribo = ie.arribo AND arribos.orden = do_asignados.do_asignado
                AND clientes.numero_documento = do_asignados.por_cuenta
                AND ie.referencia = ref.codigo
                AND ref.codigo = '$codigo_ref'
                AND clientes.numero_documento = '$docCliente'
                AND im.tipo_movimiento IN (".$arregloDatos['movimiento'].")
              GROUP BY inventario_entrada";

    $db->query($query);
    return $db->getArray();
  }
    
  function retornarOrdenKit($idMaestro) {
    $db = $_SESSION['conexion'];
        
    $query="SELECT * FROM inventario_entradas WHERE codigo = $idMaestro";
        
    $db->query($query);
    return $db->fetch();
  }
    
  function retornarDetalleAlistamiento($idRegistro) {
    $db = $_SESSION['conexion'];
        
    $query = "SELECT im.*, ie.*, ref.nombre as nombre_referencia, ref.codigo_ref
              FROM inventario_movimientos im
                INNER JOIN inventario_entradas ie ON ie.codigo = im.inventario_entrada
                INNER JOIN referencias ref ON ref.codigo = ie.referencia
              WHERE cod_maestro = $idRegistro
                AND tipo_movimiento = 10";
        
    $db->query($query);
    return $db->getArray();
  }
    
  function retornarMaestroAlistamiento($idRegistro) {
    $db = $_SESSION['conexion'];
        
    $query = "SELECT imm.*, ref.nombre as nombre_kit, te.nombre as nombre_unidad_empaque,
                cl.numero_documento, cl.razon_social, km.codigo_kit
              FROM inventario_maestro_movimientos imm
                INNER JOIN referencias ref ON ref.codigo = imm.producto
                INNER JOIN tipos_embalaje te ON te.codigo = imm.unidad
                INNER JOIN inventario_movimientos im ON im.cod_maestro = imm.codigo AND im.tipo_movimiento = 1
                INNER JOIN inventario_entradas ie ON ie.codigo = im.inventario_entrada
                INNER JOIN do_asignados da ON da.do_asignado = ie.orden
                INNER JOIN clientes cl ON cl.numero_documento = da.por_cuenta
                INNER JOIN kits_maestro km ON km.id_referencia = ref.codigo
              WHERE imm.codigo = $idRegistro";
        
    $db->query($query);
    return $db->fetch();
  }
    
  function cerrarKit($codigo) {
    $db = $_SESSION['conexion'];
    $query = "UPDATE inventario_maestro_movimientos SET cierre = 1 WHERE codigo = $codigo";

    $db->query($query);
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
}
?>