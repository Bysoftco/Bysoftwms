<?php
require_once(DB.'BDControlador.php'); 
	
class Movimiento extends BDControlador {
  var $db;

  function Movimiento() {
    $this->db = $_SESSION['conexion'];
    $this->estilo_error = "ui-state-error";
    $this->estilo_ok = "ui-state-highlight";
  }
            
	//Función para Cargar Listas
  function lista($tabla,$condicion=NULL,$campoCondicion=NULL) {
    $sql = "SELECT codigo,nombre FROM $tabla WHERE codigo not in('0')";

    if($condicion<> NULL and $condicion <> '%') {
      $sql .= " AND $campoCondicion IN ('$condicion')" ;
    }

    $sql .= "	ORDER BY nombre";
    $this->db->query($sql); 
    if($this->_lastError) {
      return FALSE;
    } else {
      $arreglo = array();
      while($obj=$this->db->fetch()) {
        $arreglo[$obj->codigo] = ucwords(strtolower($obj->nombre));
      }
    }

    return $arreglo;
  }
    	
	function addMovimiento($arregloDatos) {
		if(empty($arregloDatos[peso_naci])) { $arregloDatos[peso_naci] = 0; }
		if(empty($arregloDatos[peso_nonac])) { $arregloDatos[peso_nonac] = 0; }
		if(empty($arregloDatos[cantidad_nac])) { $arregloDatos[cantidad_nac] = 0; }
		if(empty($arregloDatos[cantidad_nonac])) { $arregloDatos[cantidad_nonac] = 0; }
		if(empty($arregloDatos[fob_naci])){ $arregloDatos[fob_naci] = 0; }
		if(empty($arregloDatos[fob_naci])){ $arregloDatos[fob_naci] = 0; }
		$sql = "INSERT INTO inventario_movimientos(inventario,fecha,movimiento,peso_naci,
              peso_nonac,cantidad_nac,cantidad_nonac,fob_naci,fob_nonac)
            VALUES($arregloDatos[inventario],'$arregloDatos[fecha]',
              '$arregloDatos[movimiento]','$arregloDatos[peso_naci]',
              '$arregloDatos[peso_nonac]','$arregloDatos[cantidad_nac]',
              '$arregloDatos[cantidad_nonac]','$arregloDatos[fob_naci]',
              '$arregloDatos[fob_nonac]')";

    $this->db->query($sql);
    if($this->_lastError) {
      echo $sql;
      $this->mensaje = "error al crear registro de  Movimiento ";
      $this->estilo	= $this->estilo_error;
      return TRUE;
    }
  }
}  
?>