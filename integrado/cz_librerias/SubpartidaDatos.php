<?php
require_once(DB.'BDControlador.php');

class Subpartida extends BDControlador {
  var $db;

  function Subpartida() {
		$this->db = $_SESSION['conexion'];
    $this->estilo_error = " ui-state-error";
    $this->estilo_ok = " ui-state-highlight";
  }
  
	function getListado($arregloDatos) {
    $fecha = date('Y-m-d:a');
    $sede = $_SESSION['sede'];

    $sql = "SELECT * FROM subpartidas ";
		if(!empty($arregloDatos['subpartida'])) {
			$sql .= " WHERE subpartida LIKE '%$arregloDatos[subpartida]%'";
		}
		if(!empty($arregloDatos['descripcion']) and empty($arregloDatos['subpartida'])) {
   		$sql .= " WHERE descripcion LIKE '%$arregloDatos[descripcion]%'";		
		}

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
    	echo "<div class=error align=center> :( Error al listar Subpartidas <br>$sql</div>.";  
      return FALSE;
		}
	}
	
	function getUnaSubpartida($arregloDatos) {
    $fecha = date('Y-m-d:a');
    $sede = $_SESSION['sede'];
    
    $sql = "SELECT * FROM subpartidas ";
		if(!empty($arregloDatos['subpartida'])) {
   		$sql .= " WHERE subpartida LIKE '%$arregloDatos[subpartida]%'";		
		}
	   
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
    	echo "<div class=error align=center> :( Error al listar Subpartidas <br>$sql</div>.";  
      return FALSE;
		}
	}
	
	function updateSubpartida($arregloDatos) {
    $fecha = date('Y-m-d:a');
    $sede = $_SESSION['sede'];

    $subpartida = trim($arregloDatos['subpartida']);
    $sql = "UPDATE subpartidas SET arancel=$arregloDatos[arancel], descripcion='$arregloDatos[descripcion]' WHERE TRIM(subpartida) = '$subpartida'";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
    	$arregloDatos['mensaje'] = 'Error al editar';
      return FALSE;
    } else {
    	$arregloDatos['mensaje'] = 'se edito correctamente';
    } 
	}

	function findCliente($arregloDatos) {
		$sql = "SELECT subpartida,descripcion
						FROM subpartidas  WHERE (descripcion LIKE '%$arregloDatos[q]%') OR (subpartida  LIKE '%$arregloDatos[q]%')";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		}
	}

	function getFormaNueva($arregloDatos) {	}
	
	function addSubpartida($arregloDatos) {
		$sql = "INSERT INTO subpartidas (subpartida, descripcion, arancel, detalle, obs) VALUES ('$arregloDatos[subpartida]', '$arregloDatos[descripcion]', $arregloDatos[arancel], '$arregloDatos[detalle]', '$arregloDatos[obs]')";
		
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		}	
	}

	function deleteSubpartida($arregloDatos) {
  	$sql = "DELETE FROM subpartidas WHERE subpartida = '$arregloDatos[subpartida]'";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			$this->mensaje = "error al borrar subpartida";
			$this->estilo = $this->estilo_error;
			return TRUE;
  	}
  }
}
?>