<?php 
require_once(CLASSES_PATH.'BDControlador.php');

class ReubicacionesModelo extends BDControlador {
  function ReubicacionesModelo() {
    parent :: Manejador_BD();
  }
	
  function listadoReubicaciones($arreglo) {
    $db = $_SESSION['conexion'];
    $sede = $_SESSION['sede'];
    
    //Prepara la condición del filtro
    $arreglo['where'] = '';
    if(!empty($arreglo['nitr'])) $arreglo['where'] .= "AND do_asignados.por_cuenta='$arreglo[nitr]'";
    if(!empty($arreglo['doctter'])) $arreglo['where'] .= "AND do_asignados.doc_tte = '$arreglo[doctter]'";
    if(!empty($arreglo['doasignador'])) $arreglo['where'] .= "AND do_asignados.do_asignado = '$arreglo[doasignador]'";
    if(!empty($arreglo['ubicacionr'])) $arreglo['where'] .= "AND ru.ubicacion = '$arreglo[ubicacionr]'";
    if(!empty($arreglo['referenciar'])) $arreglo['where'] .= "AND ie.referencia = '$arreglo[referenciar]'";
    
    $query = "SELECT p.nombre AS nombre_ubicacion,ru.codigo,ru.item,ru.fecha_reubica,cl.numero_documento AS documento,cl.razon_social AS nombre_cliente,ref.codigo_ref,ref.nombre AS nombre_referencia,do_asignados.doc_tte,do_asignados.do_asignado AS orden
              FROM referencias_ubicacion ru,inventario_entradas ie,referencias ref,arribos,do_asignados,
                clientes cl,posiciones p
              WHERE ru.estado_retiro = 0 AND p.codigo = ru.ubicacion
                AND ru.item = ie.codigo
                AND ie.referencia = ref.codigo
                AND ie.arribo = arribos.arribo
                AND arribos.orden = do_asignados.do_asignado
                AND do_asignados.por_cuenta = cl.numero_documento
                AND do_asignados.sede = '$sede' $arreglo[where]";

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
  
	function findReubicaciones($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT codigo,nombre FROM posiciones WHERE (nombre LIKE '%$arreglo[q]%')";

		$db->query($query);
    return $db->getArray();
	}
  
  function findReferencia($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT codigo,codigo_ref,nombre FROM referencias WHERE (nombre LIKE '%$arreglo[q]%' OR codigo_ref LIKE '%$arreglo[q]%')";

		$db->query($query);
    return $db->getArray();
	}
  
  function Reubicar($arreglo) {
    $db = $_SESSION['conexion'];

    //Reubica las ubicaciones que cambiaron    
    for($i=1;$i<=$arreglo['nr'];$i++) {
      $reubica = 'reubica'.(string) $i;
      $nubica = 'ubicacionr'.(string) $i; 
      if(!empty($arreglo[$reubica])&&(is_numeric($arreglo[$nubica]))) {
        $codigo = 'codigo'.(string) $i;
        $query = "UPDATE referencias_ubicacion SET ubicacion = $arreglo[$nubica],inicio = $arreglo[$nubica], fecha_reubica = '$arreglo[fechar]'
                  WHERE (codigo = $arreglo[$codigo])";
        $db->query($query);
      }
    }
  }

  function Agregar($arreglo) {
    $db = $_SESSION['conexion'];
    
    //Reubica las ubicaciones que cambiaron    
    for($i=1;$i<=$arreglo['nr'];$i++) {
      $reubica = 'reubica'.(string) $i;
      $nubica = 'ubicacionr'.(string) $i; 
      if(!empty($arreglo[$reubica])) {
        $item = 'item'.(string) $i;
        $query = "INSERT INTO referencias_ubicacion(item,ubicacion,rango,inicio,fin) VALUES($arreglo[$item],$arreglo[$nubica],'',$arreglo[$nubica],0)";
        $db->query($query);
      }
    }
  }
}
?>