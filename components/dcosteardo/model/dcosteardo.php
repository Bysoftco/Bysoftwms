<?php 
require_once(CLASSES_PATH.'BDControlador.php');

class DCosteardoModelo extends BDControlador {
	var $numdetalle;
	var $do_asignado;
	var $codservicio;
	var $fecha;
	var $ingreso;
	var $gasto;
	
	var $table_name = "orden_costos_detalle";
	var $module_directory= 'dcosteardo';
	var $object_name = "DCosteardoModelo";
	  
	var $campos = array('numdetalle', 'do_asignado', 'codservicio', 'fecha', 'ingreso', 'gasto');
	
	function DCosteardoModelo(){
		parent :: Manejador_BD();
	}
	
	function listadoDetallec($arreglo) {
		$db = $_SESSION['conexion'];

		$orden = " ocd.numdetalle ASC ";
		
		if(isset($arreglo['orden']) && !empty($arreglo['orden'])) {
			$orden = " $arreglo[orden] $arreglo[id_orden]";
		}

		$query = "SELECT ocd.numdetalle,ocd.do_asignado,ocd.codservicio,ocd.fecha,ocd.ingreso,ocd.gasto,ocd.sede,s.nombre AS nomservicio FROM orden_costos_detalle ocd, servicios s WHERE ocd.do_asignado = $arreglo[do_asignado] AND ocd.codservicio = s.codigo AND ocd.sede = s.sede ORDER BY $orden";
		    
		$db->query($query);
		$mostrar = 6;
		$retornar['paginacion']=$this->paginar($arreglo['pagina'],$db->countRows(),$mostrar);
		
		$limit = ' LIMIT '. ($arreglo['pagina'] -1) * $mostrar . ',' . $mostrar;
		$query .= $limit;
		$db->query($query);
		$retornar['datos'] = $db->getArray();
		return $retornar;
	}
	
	function registrar($arreglo) {
		$db = $_SESSION['conexion'];
    $sede = $_SESSION['sede'];
        
    //Verifica el tipo de registro - Agregar / Editar
    if($arreglo['actualiza']) {
  		$query = "UPDATE orden_costos_detalle SET numdetalle=$arreglo[numdetalle],do_asignado=$arreglo[do_asignado],
                  codservicio=$arreglo[codservicio],fecha='$arreglo[fecha]',naturaleza='$arreglo[naturaleza]',
                  ingreso=$arreglo[ingreso],gasto=$arreglo[gasto],sede='$sede'
                WHERE (numdetalle = $arreglo[numdetalle] AND do_asignado = $arreglo[do_asignado])";     
    } else {      
      $query = "INSERT INTO orden_costos_detalle (numdetalle,do_asignado,codservicio,fecha,naturaleza,ingreso,gasto,sede)
                VALUES ($arreglo[numdetalle],$arreglo[do_asignado],$arreglo[codservicio],'$arreglo[fecha]','$arreglo[naturaleza]',$arreglo[ingreso],$arreglo[gasto],'$sede')";
    }
		
		$db->query($query);
		return true;
	}
	
  function datosDetallec($arreglo) {
    $db = $_SESSION['conexion'];
    
    $query = "SELECT ocd.numdetalle,ocd.do_asignado,ocd.codservicio,ocd.fecha,ocd.naturaleza,ocd.ingreso,ocd.gasto,ocd.sede,s.nombre AS nomservicio
              FROM orden_costos_detalle ocd, servicios s
							WHERE ocd.numdetalle=$arreglo[numdetalle] AND ocd.do_asignado = $arreglo[do_asignado]
                AND ocd.codservicio = s.codigo AND ocd.sede = s.sede";
                  
    $db->query($query);
    return $db->getArray();
  }

	function eliminarDetallec($arreglo) {
		$db = $_SESSION['conexion'];
		$query = "DELETE FROM orden_costos_detalle WHERE numdetalle = $arreglo[numdetalle] AND do_asignado = $arreglo[do_asignado];";
		
		$db->query($query);
		return true;
	}
  
  function findServicio($arreglo) {
    $db = $_SESSION['conexion'];
    $sede = $_SESSION['sede'];
    
    $query = "SELECT * FROM servicios WHERE (nombre LIKE '%$arreglo[q]%') AND (sede = '$sede')";
    
    $db->query($query);
    return $db->getArray();
  }
}
?>