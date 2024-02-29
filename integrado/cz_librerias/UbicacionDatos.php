<?php
require_once(DB.'BDControlador.php'); 

class Ubicacion extends BDControlador {
	var $db;

  function Ubicacion() {
  	$this->db = $_SESSION['conexion'];
    $this->estilo_error = " ui-state-error";
    $this->estilo_ok = " ui-state-highlight";
  }

	function getListado($arregloDatos) {
		$fecha = date('Y-m-d:a');
		$sede = $_SESSION['sede'];

		$sql = "SELECT * FROM posiciones";
		 if(!empty($arregloDatos['nombre'])){
				$sql .= " WHERE nombre LIKE '%$arregloDatos[nombre]%'";		
		}
		if(!empty($arregloDatos['codigo']) and empty($arregloDatos['nombre'])) {
				$sql .= " WHERE codigo LIKE '%$arregloDatos[codigo]%'";		
		}

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo "<div class=error align=center> :( Error al listar Posiciones <br>$sql</div>.";  
			return FALSE;
		}
	}
	
	function getUnaUbicacion($arregloDatos) {
		$fecha = date('Y-m-d:a');
		$sede = $_SESSION['sede'];

		$sql = "SELECT * FROM posiciones";
		if(!empty($arregloDatos['codigo'])){
			$sql .= " WHERE codigo LIKE '%$arregloDatos[codigo]%'";		
		}

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo "<div class=error align=center> :( Error al listar Ubicacion <br>$sql</div>.";  
			return FALSE;
		} 
	}
	
	function updateUbicacion($arregloDatos) {
		$fecha = date('Y-m-d:a');
		$sede = $_SESSION['sede'];

		$codigo = $arregloDatos['codigo'];
		$sql = "UPDATE posiciones SET codigo = $arregloDatos[codigo],nombre = '$arregloDatos[nombre]',tipo = '$arregloDatos[tipo]',descripcion = '$arregloDatos[descripcion]' WHERE codigo=$codigo";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo "<div class=error align=center> :( Error al listar Ubicacion <br>$sql</div>.";  
			return FALSE;
		}
	}

	function findCliente($arregloDatos) {
		$sql = "SELECT nombre
						FROM posiciones  WHERE (nombre LIKE '%$arregloDatos[q]%')";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		}
	}

	function getFormaNueva($arregloDatos) { }
	
	function addUbicacion($arregloDatos) {
		$sql = "INSERT INTO posiciones (codigo, tipo, nombre, descripcion,sede)
				VALUES ($arregloDatos[codigo] ,'$arregloDatos[tipo]', '$arregloDatos[nombre]', '$arregloDatos[descripcion]', '$arregloDatos[sede]')";
		
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		}
	}

	function deleteUbicacion($arregloDatos) {
  	$sql = "DELETE FROM posiciones WHERE codigo = $arregloDatos[codigo]";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
   		$this->mensaje = "error al borrar ubicacion";
  		$this->estilo = $this->estilo_error;
  		return TRUE;
  	}
	}
}
?>