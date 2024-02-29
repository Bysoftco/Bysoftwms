<?php 
require_once(CLASSES_PATH.'BDControlador.php');

class OcupacionModelo extends BDControlador {
  function OcupacionModelo() {
    parent :: Manejador_BD();
  }
	
  function listadoOcupacion($arreglo) {
    $db = $_SESSION['conexion'];
    $sede = $_SESSION['sede'];
    
    //Prepara la condición del filtro
    $arreglo['where'] = '';
    if(!empty($arreglo['nito'])) $arreglo['where'] .= " AND do_asignados.por_cuenta='$arreglo[nito]'";
    if(!empty($arreglo['doctteo'])) $arreglo['where'] .= " AND do_asignados.doc_tte = '$arreglo[doctteo]'";
    if(!empty($arreglo['doasignadoo'])) $arreglo['where'] .= " AND do_asignados.do_asignado = '$arreglo[doasignadoo]'";
    if(!empty($arreglo['ocupaciono'])) $arreglo['where'] .= " AND ru.ubicacion = '$arreglo[ocupaciono]'";
    if(!empty($arreglo['referenciao'])) $arreglo['where'] .= " AND ie.referencia = '$arreglo[referenciao]'";
    
    $query = "SELECT p.nombre AS nombre_ubicacion,ru.codigo,cl.numero_documento AS documento,
                cl.razon_social AS nombre_cliente,ref.codigo_ref,ref.nombre AS nombre_referencia,
                do_asignados.doc_tte,do_asignados.do_asignado AS orden
              
			  
			  FROM referencias_ubicacion ru,inventario_entradas ie,referencias ref,arribos,do_asignados,
                clientes cl,posiciones p
              WHERE ru.estado_retiro = 0 AND p.codigo = ru.ubicacion 
                AND ru.item = ie.codigo
                AND ie.referencia = ref.codigo
				AND ie.codigo_ref=ref.codigo_ref
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
  
	function findOcupacion($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT codigo,codigo_ref,nombre FROM posiciones WHERE (nombre LIKE '%$arreglo[q]%' OR codigo_ref LIKE '%$arreglo[q]%')";

		$db->query($query);
    return $db->getArray();
	}
  
  function findReferencia($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT codigo,nombre FROM referencias WHERE (nombre LIKE '%$arreglo[q]%')";

		$db->query($query);
    return $db->getArray();
	}
  
  function Eliminar($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "DELETE FROM referencias_ubicacion WHERE (codigo = $arreglo[codigo])";

		$db->query($query);    
  }
  
 	function findClientet($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT razon_social	FROM clientes WHERE (numero_documento = '$arreglo')";
    
		$db->query($query);
    return $db->fetch();
	}
}
?>