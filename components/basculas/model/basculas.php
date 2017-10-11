<?php 
require_once(CLASSES_PATH.'BDControlador.php');

class BasculasModelo extends BDControlador {
	var $sticker;
	var $orden;
	var $arribo;
	var $placa;
	var $peso;
  var $precinto;
  
	var $table_name = "peso_bascula";
	var $module_directory= 'basculas';
	var $object_name = "BasculasModelo";
	  
	var $campos = array('id', 'numorden', 'codreferencia', 'fecha', 'serial');
	
	function BasculasModelo(){
		parent :: Manejador_BD();
	}
	
	function listadoBasculas($arreglo) {
		$db = $_SESSION['conexion'];
		
		$orden = " fecha DESC ";
		$buscar = "";
		
		if(isset($arreglo['orden']) && !empty($arreglo['orden'])) {
			$orden = " $arreglo[orden] $arreglo[id_orden]";
		}
		if(isset($arreglo['buscar']) && !empty($arreglo['buscar'])) {
			$buscar= " AND (fecha LIKE '%$arreglo[buscar]%' OR serial LIKE '%$arreglo[buscar]%')";
		}
		
		$query = "SELECT * FROM peso_bascula WHERE numorden = $arreglo[numorden] AND codreferencia = '$arreglo[codreferencia]' $buscar ORDER BY $orden";
		
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
}
?>