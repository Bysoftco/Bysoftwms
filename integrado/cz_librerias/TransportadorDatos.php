<?php
require_once(DB.'BDControlador.php');  

class Transportador extends BDControlador {
	var $db;

  function Transportador() {
  	$this->db = $_SESSION['conexion'];
    $this->estilo_error = " ui-state-error";
    $this->estilo_ok = " ui-state-highlight";
  }

	function getListado($arregloDatos) {
		$fecha = date('Y-m-d:a');
		$sede = $_SESSION['sede'];

		$sql = "SELECT * FROM transportador";
		if(!empty($arregloDatos['nombre'])) {
			$sql .= " WHERE nombre LIKE '%$arregloDatos[nombre]%'";		
		}
		if(!empty($arregloDatos['codigo']) && empty($arregloDatos['nombre'])) {
				$sql .= " WHERE codigo LIKE '%$arregloDatos[codigo]%'";		
		}

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo "<div class=error align=center> :( Error al listar Transportador <br>$sql</div>.";  
			return FALSE;
		}
	}
	
	function getUnTransportador($arregloDatos) {
		$fecha=date('Y-m-d:a');
		$sede=$_SESSION['sede'];

		$sql="SELECT * FROM transportador";
		if(!empty($arregloDatos['codigo'])) {
			$sql.=" where codigo like '%$arregloDatos[codigo]%'";		
		}

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo "<div class=error align=center> :( Error al listar Transportador <br>$sql</div>.";  
			return FALSE;
		}
	}
	
	function updateTransportador($arregloDatos) {
		$fecha = date('Y-m-d:a');
		$sede = $_SESSION['sede'];
		$codigo = trim($arregloDatos['codigo']);
		$sql = "UPDATE transportador SET codigo = '$arregloDatos[codigo]', nombre = '$arregloDatos[nombre]', contacto = '$arregloDatos[contacto]', telefono = '$arregloDatos[telefono]', tipo_transporte = '$arregloDatos[tipo_transporte]' WHERE TRIM(codigo)='$codigo'";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo "<div class=error align=center> :( Error al listar Transportador <br>$sql</div>.";  
			return FALSE;
		}
	}

	function findCliente($arregloDatos) {
		$sql = "SELECT nombre FROM transportador WHERE (nombre LIKE '%$arregloDatos[q]%')";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		}
	}

	function getFormaNueva($arregloDatos) { }
	
	function addTransportador($arregloDatos) {
		$sql = "INSERT INTO transportador (codigo,nombre,contacto,telefono,tipo_transporte) VALUES ('$arregloDatos[codigo]','$arregloDatos[nombre]','$arregloDatos[contacto]','$arregloDatos[telefono]','$arregloDatos[tipo_transporte]')";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		}
	}

	function deleteTransportador($arregloDatos) {
		$sql = "DELETE FROM transportador WHERE codigo='$arregloDatos[codigo]'";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			$this->mensaje = "error al borrar transportador";
			$this->estilo = $this->estilo_error;
			return TRUE;
		}
	}
}
?>