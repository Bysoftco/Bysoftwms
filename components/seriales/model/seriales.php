<?php 
require_once(CLASSES_PATH.'BDControlador.php');

class SerialesModelo extends BDControlador {
	var $id;
	var $numorden;
	var $codreferencia;
	var $fecha;
	var $serial;
	
	var $table_name = "orden_seriales";
	var $module_directory= 'seriales';
	var $object_name = "SerialesModelo";
	  
	var $campos = array('id', 'numorden', 'codreferencia', 'fecha', 'serial');
	
	function SerialesModelo(){
		parent :: Manejador_BD();
	}
	
	function listadoSeriales($arreglo) {
		$db = $_SESSION['conexion'];
		
		$orden = " id ASC ";
		$buscar = "";
		
		if(isset($arreglo['orden']) && !empty($arreglo['orden'])) {
			$orden = " $arreglo[orden] $arreglo[id_orden]";
		}
		if(isset($arreglo['buscar']) && !empty($arreglo['buscar'])) {
			$buscar= " AND (fecha LIKE '%$arreglo[buscar]%' OR serial LIKE '%$arreglo[buscar]%')";
		}
		
		$query = "SELECT * FROM orden_seriales WHERE numorden = $arreglo[numorden] AND codreferencia = '$arreglo[codreferencia]' $buscar ORDER BY $orden";
		
		$db->query($query);
		$mostrar = 7;
		$retornar['paginacion']=$this->paginar($arreglo['pagina'],$db->countRows(),$mostrar);
		
		$limit = ' LIMIT '. ($arreglo['pagina'] -1) * $mostrar . ',' . $mostrar;
		$query .= $limit;
		$db->query($query);
		$retornar['datos'] = $db->getArray();
		return $retornar;
	}
	
	function validarRepetido($arreglo) {
		$db = $_SESSION['conexion'];
		$query = "SELECT * FROM orden_seriales WHERE serial = '$arreglo[serial]'";
		
		$db->query($query);
		return $db->getArray();
	}
	
	function cargarSeriales($arreglo) {
		$db = $_SESSION['conexion'];
    
    $lineas = $arreglo[idmetodo] == '2' ? explode("\n",$arreglo[seriales]) : file($arreglo[nombrefile]);
		$fecha = date("Y-m-d");
		//Conversión de datos a numérico
		$arreglo[numorden] = (int) $arreglo[numorden];
		foreach ($lineas as $linea_num => $linea) {
			$datos = explode("\n",$linea);
			$arreglo[serial] = trim($datos[0]);
			//Procedimiento de validación
			$existe = $this->validarRepetido($arreglo);
			if(count($existe) == 0) {
				$query = "INSERT INTO orden_seriales(numorden, codreferencia, fecha, serial) 
									VALUES($arreglo[numorden],'$arreglo[codreferencia]','$fecha','$arreglo[serial]');";
				$db->query($query);
			}
		}
		return true;
	}
	
	function buscarTodos($arreglo) {
		$db = $_SESSION['conexion'];
		$query="SELECT * FROM orden_seriales WHERE serial = '$arreglo[serial]'";
		
		$db->query($query);
		return $db->getArray();
	}
	
	function eliminarSeriales($arreglo) {
		$db = $_SESSION['conexion'];
		$query = "DELETE FROM orden_seriales WHERE numorden = $arreglo[numorden] AND codreferencia = '$arreglo[codreferencia]';";
		
		$db->query($query);
		return true;
	}
	
	function eliminarSerial($arreglo) {
		$db = $_SESSION['conexion'];
		$query = "DELETE FROM orden_seriales WHERE id = $arreglo[id];";
		
		$db->query($query);
		return true;
	}
  
  function findSerial($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT serial,numorden FROM orden_seriales WHERE (serial LIKE '%$arreglo[q]%')";

		$db->query($query);
    return $db->getArray();
	}
  
  function buscarSerial($arreglo) {
		$db = $_SESSION['conexion'];
		$query = "SELECT serial,numorden,da.doc_tte,p.nombre FROM posiciones p,orden_seriales
                INNER JOIN do_asignados da ON numorden = da.do_asignado
                INNER JOIN arribos a ON numorden = a.orden
              WHERE serial = '$arreglo[serial]' AND a.ubicacion = p.codigo";
		
    $db->query($query);
		return $db->getArray();
	}
}
?>